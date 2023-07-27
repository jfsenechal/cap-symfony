<?php

namespace Cap\Commercio\Command;

use Cap\Commercio\Repository\CommercioCommercantRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'cap:fix-expired',
    description: 'Add a short description for your command',
)]
class ExpiredMemberCommand extends Command
{
    public function __construct(private CommercioCommercantRepository $commercantRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $today = new \DateTime('-1 year');
        $table = new Table($output);
        $rows = [];

        foreach ($this->commercantRepository->findExpired($today) as $commercant) {
            $rows[] = [$commercant->getLegalEntity(), $commercant->getAffiliationDate()->format('Y-m-d')];
        }

        $table
            ->setHeaders(['Nom', 'Date expiration'])
            ->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }
}
