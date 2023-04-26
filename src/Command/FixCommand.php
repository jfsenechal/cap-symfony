<?php

namespace Cap\Commercio\Command;

use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'cap:fix',
    description: 'Add a short description for your command',
)]
class FixCommand extends Command
{
    public function __construct(
        private PaymentOrderRepository $paymentOrderRepository,
        private PaymentBillRepository $paymentBillRepository
    ) {
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
        foreach ($this->paymentOrderRepository->findAll() as $order) {
            if ($order->getPdfPath() == null) {
                $io->error('Vide'.$order->getId().$order->getOrderCommercant()->getCompanyName());
                continue;
            }
            $io->writeln($order->getPdfPath());
        }
        $io->section('BILL');
        foreach ($this->paymentBillRepository->findAll() as $order) {
            if ($order->getPdfPath() == null) {
                $io->error('Vide'.$order->getId().$order->getOrder()->getOrderCommercant()->getCompanyName());
                continue;
            }
            if (str_contains("/var/www/sites/commercio/", $order->getPdfPath())) {
                $newPath = preg_replace('#/var/www/sites/commercio/#', "", $order->getPdfPath());
                $io->writeln("new ".$newPath);
            }
            $io->writeln($order->getPdfPath());
        }

        return Command::SUCCESS;
    }
}
