<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Bottin\BottinApiRepository;
use Cap\Commercio\Bottin\BottinUtils;
use Cap\Commercio\Entity\PaymentBill;
use Cap\Commercio\Entity\PaymentOrder;
use Cap\Commercio\Repository\CommercioBottinRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/checkup')]
#[IsGranted('ROLE_CAP')]
class CheckupController extends AbstractController
{
    public function __construct(
        private readonly PaymentOrderRepository $paymentOrderRepository,
        private readonly PaymentBillRepository $paymentBillRepository,
        private readonly CommercioCommercantRepository $commercantRepository,
        private readonly BottinApiRepository $bottinApiRepository,
        private readonly CommercioBottinRepository $commercioBottinRepository,
        private readonly CommercioCommercantRepository $commercioCommercantRepository
    ) {
    }

    #[Route(path: '/', name: 'cap_checkup_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render(
            '@CapCommercio/checkup/index.html.twig',
            [

            ]
        );
    }

    #[Route(path: '/order/nopaid', name: 'cap_checkup_order_without_paid', methods: ['GET', 'POST'])]
    public function nopaid(): Response
    {
        $orders = $this->paymentOrderRepository->findPaid();
        $noBill = [];
        foreach ($orders as $order) {
            $bill = $this->paymentBillRepository->findOneByOrder($order);
            if (!$bill) {
                $noBill[] = $order;
            }
        }

        return $this->render(
            '@CapCommercio/checkup/nobill.html.twig',
            [
                'orders' => $noBill,
            ]
        );
    }

    #[Route(path: '/expired', name: 'cap_checkup_expired', methods: ['GET', 'POST'])]
    public function expired(): Response
    {
        $today = new DateTime('-1 year');
        $commercants = $this->commercantRepository->findExpired($today);

        return $this->render(
            '@CapCommercio/checkup/expired.html.twig',
            [
                'commercants' => $commercants,
                'today' => $today,
            ]
        );
    }

    #[Route(path: '/missingpath', name: 'cap_checkup_missing_path', methods: ['GET'])]
    public function missingPath(): Response
    {
        $ordersMissing = $billsMissing = [];
        foreach ($this->paymentOrderRepository->findAll() as $order) {
            if ($order->getPdfPath() == null) {
                $ordersMissing[] = $order;
            }
            if (!is_readable($this->getAbsolutePathPdf($order))) {
                $ordersMissing[] = $order;
            }
        }

        foreach ($this->paymentBillRepository->findAll() as $bill) {
            if ($bill->getPdfPath() == null) {
                $billsMissing[] = $bill;
            }
            if (!is_readable($this->getAbsolutePathPdf($bill))) {
                $billsMissing[] = $bill;
            }
        }

        return $this->render(
            '@CapCommercio/checkup/missing.html.twig',
            [
                'ordersMissing' => $ordersMissing,
                'billsMissing' => $billsMissing,
            ]
        );
    }

    #[Route(path: '/noinbottin', name: 'cap_checkup_no_in_bottin', methods: ['GET', 'POST'])]
    public function noMoreInBottin(): Response
    {
        $commercants = [];
        foreach ($this->commercioCommercantRepository->findAllOrdered() as $commercant) {

            $bottin = $this->commercioBottinRepository->findByCommercantId($commercant->getId());
            if ($bottin) {
                try {
                    $fiche = $this->bottinApiRepository->findByFicheId($bottin->bottinId);
                } catch (\Exception $e) {

                    if ($e->getCode() === 404) {
                        $commercants[] = $commercant;
                    } else {
                        $this->addFlash(
                            'danger',
                            'Impossible d\'obtenir le detail du commerce: '.$commercant->getLegalEntity(
                            ).' Erreur '.$e->getMessage()
                        );
                    }

                    continue;
                }
                if (isset($fiche->error)) {
                    $commercant->urlBottin = BottinUtils::urlBottin($bottin->bottinId);
                    $commercants[] = $commercant;
                }
            }
        }

        return $this->render(
            '@CapCommercio/checkup/not_in_bottin.html.twig',
            [
                'commercants' => $commercants,
            ]
        );
    }

    private function getAbsolutePathPdf(PaymentBill|PaymentOrder $object): string
    {
        [$name] = explode('?', $object->getPdfPath());
        $path = $this->getParameter('CAP_PATH');

        return $path.$name;
    }
}
