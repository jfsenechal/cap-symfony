<?php

namespace Cap\Commercio\Command;

use Cap\Commercio\Repository\CommercioBottinRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'cap:fix-synchro',
    description: 'Synchro avec le bottin',
)]
class SynchroBottinCommand extends Command
{
    public function __construct(
        private CommercioCommercantRepository $commercantRepository,
        private CommercioBottinRepository $commercioBottinRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('fix', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fix = $input->getOption('fix');

        foreach ($this->commercantRepository->findAllOrdered() as $commercant) {
            if (!$this->commercioBottinRepository->findByCommercerant($commercant)) {
                $io->writeln($commercant->getLegalEntity());
                if ($fix) {
                    $this->commercantRepository->remove($commercant);
                    $this->commercantRepository->flush();
                }
            }
        }
        if ($fix) {
            $this->commercantRepository->flush();
        }

        return Command::SUCCESS;
    }

}
