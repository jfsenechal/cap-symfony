<?php

namespace Cap\Commercio\Command;

use Cap\Commercio\Entity\PaymentBill;
use Cap\Commercio\Repository\PaymentBillRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'cap:fix-pdf-absolute',
    description: 'Parcours les paiements pour vérifier les pdfs',
)]
class FixPdfAbsoluteCommand extends Command
{
    private SymfonyStyle $io;
    private bool $flush = false;

    public function __construct(
        private readonly PaymentBillRepository $paymentBillRepository,
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
        $this->io = new SymfonyStyle($input, $output);

        $fix = $input->getOption('fix');
        $this->flush = (bool)$input->getOption('flush');

        $this->io->section('Paiements');
        foreach ($this->paymentBillRepository->findAll() as $bill) {
            if ($bill->getPdfPath() !== null && $this->isAbsolutePath($bill->getPdfPath())) {
                $this->io->writeln($bill->getPdfPath());
                if ($fix) {
                    $this->fixPath($bill);
                }
            }
        }

        if ($this->flush) {
            $this->paymentBillRepository->flush();
        }

        return Command::SUCCESS;
    }

    private function isAbsolutePath(string $path): bool
    {
        return str_contains($path, 'var/www');
    }

    private function fixPath(PaymentBill $bill): void
    {
        if ($this->isAbsolutePath($bill->getPdfPath())) {
            list($a, $b) = explode('pdf-docs', $bill->getPdfPath());
            $newPath = 'pdf-docs'.$b;
            $this->io->success($newPath);
            $bill->setPdfPath($newPath);
        }
    }

}
