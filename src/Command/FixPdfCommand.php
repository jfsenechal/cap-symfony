<?php

namespace Cap\Commercio\Command;

use Cap\Commercio\Entity\PaymentBill;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'cap:fix-pdf',
    description: 'Add a short description for your command',
)]
class FixPdfCommand extends Command
{
    private string $path = '/var/www/sites/commercio/';

    public function __construct(
        private PaymentOrderRepository $paymentOrderRepository,
        private PaymentBillRepository $paymentBillRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('fix', null, InputOption::VALUE_NONE, 'Option description')
            ->addOption('flush', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fix = $input->getOption('fix');
        $flush = $input->getOption('flush');

        $io->section('Commandes');
        foreach ($this->paymentOrderRepository->findAll() as $order) {
            if ($order->getPdfPath() == null) {
                $io->error(
                    'Pas de chemin en bd id '.$order->getId().' '.$order->getOrderCommercant()->getCompanyName(
                    ).' '.$order->getModifyDate()->format('Y-m-d')
                );
                continue;
            }
            if ($this->isAbsolutePath($order->getPdfPath())) {
                $io->writeln($order->getPdfPath());
            }
            if (!is_readable($this->getAbsolutePathPdf($order))) {
                $io->error('not readable id: '.$order->getId().' '.$this->getAbsolutePathPdf($order));
            }

        }
        $io->section('Paiements');
        foreach ($this->paymentBillRepository->findAll() as $bill) {
            if ($bill->getPdfPath() == null) {
                $io->error(
                    'Pas de chemin en db id '.$bill->getId().' '.$bill->getOrder()->getOrderCommercant()->getCompanyName(
                    ).' '.$bill->getModifyDate()->format('Y-m-d')
                );
                continue;
            }
            if ($this->isAbsolutePath($bill->getPdfPath())) {
                $io->writeln($bill->getPdfPath());
            }
            if ($fix) {
                $this->fixPath($bill);
                if ($flush) {
                    $this->paymentBillRepository->flush();
                }
            }
            if (!is_readable($this->getAbsolutePathPdf($bill))) {
                $io->error('not readable: '.$this->getAbsolutePathPdf($bill));
            }
        }

        return Command::SUCCESS;
    }

    private function isAbsolutePath(string $path): bool
    {
        return str_contains($path, $this->path);
    }

    private function fixPath(PaymentBill $bill)
    {
        if ($this->isAbsolutePath($bill->getPdfPath())) {
            $newPath = str_replace($this->path, "", $bill->getPdfPath());
            $bill->setPdfPath($newPath);
        }
    }

    private function getAbsolutePathPdf(PaymentBill|PaymentOrder $object)
    {
        list($name) = explode('?', $object->getPdfPath());

        return $this->path.$name;
    }
}
