<?php

namespace Cap\Commercio\Command;

use Cap\Commercio\Repository\CommercioBottinRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'cap:fix',
    description: 'Add a short description for your command',
)]
class FixCommand extends Command
{
    public function __construct(
        private readonly CommercioBottinRepository $commercioBottinRepository,
        private readonly CommercioCommercantRepository $commercioCommercantRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
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

        return Command::SUCCESS;
    }
}
