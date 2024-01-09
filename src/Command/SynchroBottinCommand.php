<?php

namespace Cap\Commercio\Command;

use Cap\Commercio\Bottin\BottinApiRepository;
use Cap\Commercio\Entity\CommercioBottin;
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
            ->addOption('fix', null, InputOption::VALUE_NONE, 'Flush database')
            ->addOption('compare', null, InputOption::VALUE_NONE, 'Compare commercant/bottin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fix = $input->getOption('fix');
        $compare = $input->getOption('compare');
        $notFound = [];

        if ($compare) {
            $this->compare($output);

            return Command::SUCCESS;
        }

        foreach ($this->commercioCommercantRepository->findAllOrdered() as $commercant) {
            try {
                $fiche = $this->bottinApiRepository->findCommerceById($commercant->getId());
            } catch (\Exception $e) {
                if ($e->getCode() === 404) {
                    $notFound[] = $commercant;
                } else {
                    $io->error(
                        'Error api: '.$commercant->getLegalEntity().' Error: '.$e->getMessage().' Code '.$e->getCode()
                    );
                }

                continue;
            }
            if (isset($fiche->error)) {
                $io->error('Error '.$fiche->error.' => '.$commercant->getLegalEntity());

                continue;
            }
            if (!$commercioBottin = $this->commercioBottinRepository->findByFicheId($fiche->id)) {
                $commercioBottin = new CommercioBottin();
                $commercioBottin->setCommercantId($fiche->id);
                $commercioBottin->setInsertDate(new \DateTime());
                $io->writeln('new commercio bottin: '.$commercant->getLegalEntity());
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
        $io->section('Shop to delete. Add --fix to flush');
        foreach ($notFound as $commercant) {
            if (!$this->commercioBottinRepository->findByFicheId($commercant->getId()) instanceof CommercioBottin) {
                $io->writeln($commercant->getLegalEntity());
                if ($fix) {
                    $this->shopHandler->removeCommercant($commercant);
                }
            }
        }
        if ($fix) {
            $this->commercioBottinRepository->flush();
        }

        return Command::SUCCESS;
    }

    private function compare(OutputInterface $output): void
    {
        $table = new Table($output);
        $table
            ->setHeaders(['Cap', 'Bottin']);
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
        $table->setRows($rows);
        $table->render();

    }
}
