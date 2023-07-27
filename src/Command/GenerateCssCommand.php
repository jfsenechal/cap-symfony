<?php

namespace Cap\Commercio\Command;

use Cap\Commercio\Pdf\PdfDownloaderTrait;
use Cap\Commercio\Pdf\PdfGenerator;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

#[AsCommand(
    name: 'cap:css',
    description: 'Add a short description for your command',
)]
class GenerateCssCommand extends Command
{
    use PdfDownloaderTrait;

    public function __construct(
        private readonly PdfGenerator $pdfGenerator,
        private readonly PaymentOrderRepository $paymentOrderRepository
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

        $cssToInlineStyles = new CssToInlineStyles();

        $order = $this->paymentOrderRepository->find(875);
        $html = $this->pdfGenerator->generateContentForOrder($order);
        $css = file_get_contents($this->parameterBag->get('kernel.project_dir').'/public/dist/output.css');

        $compiled = $cssToInlineStyles->convert($html, $css);
        echo $compiled;

        //$this->downloadPdfDom($compiled, 'zeze');


        return Command::SUCCESS;
    }

}
