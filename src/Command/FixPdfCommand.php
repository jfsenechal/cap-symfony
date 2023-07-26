<?php

namespace Cap\Commercio\Command;

use Cap\Commercio\Entity\PaymentBill;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Pdf\PdfGenerator;
use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsCommand(
    name: 'cap:fix-pdf',
    description: 'Parcours les commandes et paiements pour vérifier les pdfs',
)]
class FixPdfCommand extends Command
{
    private string $cap_path;
    private SymfonyStyle $io;
    private bool $flush = false;

    public function __construct(
        private PaymentOrderRepository $paymentOrderRepository,
        private PaymentBillRepository $paymentBillRepository,
        private PdfGenerator $pdfGenerator,
        private ParameterBagInterface $parameterBag
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
        $this->cap_path = $this->parameterBag->get('CAP_PATH');

        $this->io->section('Commandes');

        foreach ($this->paymentOrderRepository->findAll() as $order) {
            if ($order->getPdfPath() == null) {
                $this->io->writeln('Order Pas de path en db '.$order->getId());
                if ($fix) {
                    $this->fixMissingPdf($order);
                }
                continue;
            }
            if (!is_readable($this->getAbsolutePathPdf($order))) {
                $this->io->writeln('Order Pdf non lisable '.$this->getAbsolutePathPdf($order));
                if ($fix) {
                    $this->fixMissingPdf($order);
                }
                continue;
            }
            if ($this->isAbsolutePath($order->getPdfPath())) {
                $this->io->writeln($order->getPdfPath());
            }
        }

        $this->io->section('Paiements');
        foreach ($this->paymentBillRepository->findAll() as $bill) {
            if ($bill->getPdfPath() == null) {
                $this->io->writeln('Bill Pas de path en db '.$bill->getId());
                if ($fix) {
                    $this->fixMissingPdf($bill);
                }
                continue;
            }
            if (!is_readable($this->getAbsolutePathPdf($bill))) {
                $this->io->writeln('Bill Pdf non lisable '.$this->getAbsolutePathPdf($bill));
                if ($fix) {
                    $this->fixMissingPdf($bill);
                }
                continue;
            }
            if ($this->isAbsolutePath($bill->getPdfPath())) {
                $this->io->writeln($bill->getPdfPath());
                if ($fix) {
                    $this->fixPath($bill);
                }
            }
        }

        return Command::SUCCESS;
    }

    private function fixMissingPdf(PaymentBill|PaymentOrder $object): void
    {
        $html = $fileName = '';
        if ($object instanceof PaymentOrder) {
            try {
                $html = $this->pdfGenerator->generateContentForOrder($object);
            } catch (LoaderError|RuntimeError|SyntaxError $e) {
                $this->io->error('Content error '.$e->getMessage());

                return;
            }
            $fileName = 'order-'.$object->getUuid().'.pdf';
        }

        if ($object instanceof PaymentBill) {
            try {
                $html = $this->pdfGenerator->generateContentForBill($object);
            } catch (LoaderError|RuntimeError|SyntaxError $e) {
                $this->io->error('Content error '.$e->getMessage());

                return;
            }
            $fileName = 'bill-'.$object->getUuid().'.pdf';
        }

        try {
            $this->pdfGenerator->savePdfToDisk($html, $fileName);
        } catch (Html2PdfException $e) {
            $this->io->error('Not save disk '.$e->getMessage());

            return;
        }
        $object->setPdfPath('pdf-docs/'.$fileName);

        if ($this->flush) {
            $this->paymentBillRepository->flush();
        }
    }

    private function isAbsolutePath(string $path): bool
    {
        return str_contains($path, $this->cap_path);
    }

    private function fixPath(PaymentBill $bill): void
    {
        if ($this->isAbsolutePath($bill->getPdfPath())) {
            $newPath = str_replace($this->cap_path, "", $bill->getPdfPath());
            $bill->setPdfPath($newPath);
        }
    }

    private function getAbsolutePathPdf(PaymentBill|PaymentOrder $object): string
    {
        list($name) = explode('?', $object->getPdfPath());

        return $this->cap_path.$name;
    }
}
