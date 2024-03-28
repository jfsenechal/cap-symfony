<?php

namespace Cap\Commercio\Command;

use Cap\Commercio\Bottin\BottinApiRepository;
use Cap\Commercio\Entity\CommercioBottin;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Repository\CommercioBottinRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Cap\Commercio\Shop\ShopHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Parcours la liste des commerces de la db de cap et vérifie s'il existe toujours dans le bottin
 * Si oui mets à jour la db commercio_bottin
 * Supprime de la db de cap, les commerçants qui ne sont plus dans le bottin
 */
#[AsCommand(
    name: 'cap:synchro',
    description: 'Synchronisation avec le bottin',
)]
class SynchroBottinCommand extends Command
{
    private SymfonyStyle $io;
    private OutputInterface $outPut;

    public function __construct(
        private readonly CommercioBottinRepository $commercioBottinRepository,
        private readonly CommercioCommercantRepository $commercioCommercantRepository,
        private readonly BottinApiRepository $bottinApiRepository,
        private readonly ShopHandler $shopHandler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('refresh', null, InputOption::VALUE_NONE, 'Refresh from zero database')
            ->addOption('fix', null, InputOption::VALUE_NONE, 'Flush database')
            ->addOption('remove', null, InputOption::VALUE_NONE, 'Remove not in bottin')
            ->addOption('compare', null, InputOption::VALUE_NONE, 'Compare commercant/bottin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->outPut = $output;

        $fix = $input->getOption('fix');
        $refresh = $input->getOption('refresh');
        $compare = $input->getOption('compare');
        $remove = $input->getOption('remove');
        $notFound = [];

        if ($compare) {
            $this->compare($output);

            return Command::SUCCESS;
        }

        if ($refresh) {
            $this->refresh();

            return Command::SUCCESS;
        }

        foreach ($this->commercioCommercantRepository->findAllOrdered() as $commercant) {
            $fiche = $this->findFiche($commercant);
            if (!$fiche) {
                $notFound[] = $commercant;
            }
            if (!$commercioBottin = $this->commercioBottinRepository->findByFicheId($fiche->id)) {
                $commercioBottin = new CommercioBottin($commercant->getId(), $fiche->id);
                $commercioBottin->setInsertDate(new \DateTime());
                $this->commercioBottinRepository->persist($commercioBottin);
                $this->io->writeln('new commercio bottin: '.$commercant->getLegalEntity());
            }
            $commercioBottin->setBottin($fiche);
            $commercioBottin->setModifyDate(new \DateTime());
        }

        if ($fix) {
            $this->commercioBottinRepository->flush();
        }

        /**
         * Remove if no more in bottin
         */
        if ($remove) {
            $this->io->section('Shop to delete. Add --fix to flush');
            foreach ($notFound as $commercant) {
                if (!$this->commercioBottinRepository->findByCommercantId(
                        $commercant->getId()
                    ) instanceof CommercioBottin) {
                    $this->io->writeln($commercant->getLegalEntity());
                    if ($fix) {
                        $this->shopHandler->removeCommercant($commercant);
                    }
                }
            }
            if ($fix) {
                $this->commercioBottinRepository->flush();
            }
        }

        return Command::SUCCESS;
    }

    private function compare(OutputInterface $output): void
    {
        $rows = [];
        foreach ($this->commercioCommercantRepository->findAllOrdered() as $commercant) {
            $row = [];
            $row[] = $commercant->getLegalEntity();
            $fiche = $this->commercioBottinRepository->findByFicheId(
                $commercant->getId()
            );
            if ($fiche) {
                $row[] = $fiche->getBottin()['societe'];
            } else {
                $row [] = 'not found';
            }
            $rows[] = $row;
        }
        $this->displayTable($rows);
    }

    private function refresh(): void
    {
        $notFound = $rows = [];
        foreach ($this->commercioCommercantRepository->findAllOrdered() as $commercant) {
            $fiche = $this->findFiche($commercant);
            if (!$fiche) {
                $notFound[] = $commercant;
                continue;
            }
            $rows[] = [$commercant->getLegalEntity(), $fiche->societe];
            //$this->io->writeln($fiche->societe);
        }
        //$this->displayTable($rows);

        foreach ($notFound as $commercant) {
            $this->io->writeln($commercant->getLegalEntity());
        }
    }

    private function findFiche(CommercioCommercant $commercant): ?\stdClass
    {
        try {
            $fiches = $this->bottinApiRepository->findCommerceBySociete(
                base64_encode($commercant->getLegalEntity())
            );
        } catch (\Exception $e) {
            $this->io->error(
                'Error api: '.$commercant->getLegalEntity().' Error: '.$e->getMessage().' Code '.$e->getCode()
            );
        }
        if (count($fiches) > 1) {
            //CARREFOUR MARKET
            // $io->section(count($fiches).' '.$commercant->getLegalEntity());
            return null;
        }
        if (count($fiches) == 1) {
            return $fiches[0];
        }

        try {
            $fiches = $this->bottinApiRepository->findCommerceByEmail($commercant->getLegalEmail());
        } catch (\Exception $e) {
            $this->io->error(
                'Error api: '.$commercant->getLegalEntity().' Error: '.$e->getMessage().' Code '.$e->getCode()
            );
        }
        if (count($fiches) > 1) {
            //CARREFOUR MARKET
            // $io->section(count($fiches).' '.$commercant->getLegalEntity());
            return null;
        }
        if (count($fiches) == 1) {
            return $fiches[0];
        }

        return null;
    }

    private function displayTable(array $rows)
    {
        $table = new Table($this->outPut);
        $table
            ->setHeaders(['Cap', 'Bottin']);
        $table->setRows($rows);
        $table->render();
    }

}
