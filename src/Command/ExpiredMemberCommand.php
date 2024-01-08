<?php

namespace Cap\Commercio\Command;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Cap\Commercio\Shop\MemberHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'cap:members',
    description: 'Add a short description for your command',
)]
class ExpiredMemberCommand extends Command
{
    private array $rows = [];

    public function __construct(
        private CommercioCommercantRepository $commercantRepository,
        private readonly MemberHandler $memberHandler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('expired', null, InputOption::VALUE_NONE, 'Get expired')
            ->addOption('not-complete', null, InputOption::VALUE_NONE, 'Get no complete');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $expired = $input->getOption('expired');
        $notComplete = $input->getOption('not-complete');

        if (!$expired && !$notComplete) {
            $io->error('Veuillez choisir une des options: --expired ou --not-complete');

            return Command::FAILURE;
        }

        if ($notComplete) {
            $commercants = $this->commercantRepository->findMembers();
        } else {
            $today = new \DateTime('-1 year');
            $commercants = $this->commercantRepository->findExpired($today);
        }

        foreach ($commercants as $commercant) {
            $completed = $this->memberHandler->isMemberCompleted($commercant);
            if ($notComplete) {
                if ($completed) {
                    $this->addRows($commercant, $completed);
                }
            } else {
                $this->addRows($commercant, $completed);
            }
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Nom', 'Date expiration', 'Fiche complete'])
            ->setRows($this->rows);
        $table->render();

        return Command::SUCCESS;
    }

    private function addRows(CommercioCommercant $commercant, bool $completed)
    {
        $this->rows[] = [
            $commercant->getLegalEntity(),
            $commercant->getAffiliationDate()->format('Y-m-d'),
            $completed,
        ];
    }
}
