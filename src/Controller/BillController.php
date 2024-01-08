<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\PaymentBill;
use Cap\Commercio\Form\BillSearchType;
use Cap\Commercio\Repository\PaymentBillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/bill')]
#[IsGranted('ROLE_CAP')]
class BillController extends AbstractController
{
    public function __construct(
        private readonly PaymentBillRepository $paymentBillRepository,
    ) {
    }

    #[Route(path: '/', name: 'cap_bill_all', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(BillSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $bills = $this->paymentBillRepository->search($data['number'], $data['name'], $data['year'], $data['paid']);
        } else {
            $bills = $this->paymentBillRepository->findAllOrdered();
        }

        return $this->render(
            '@CapCommercio/bill/index.html.twig',
            [
                'bills' => $bills,
                'form' => $form,
            ]
        );
    }

    #[Route(path: '/show/{id}', name: 'cap_bill_show', methods: ['GET', 'POST'])]
    public function show(PaymentBill $bill): Response
    {
        $order = $bill->getOrder();

        return $this->render(
            '@CapCommercio/bill/show.html.twig',
            [
                'order' => $order,
                'bill' => $bill,
            ]
        );
    }

    #[Route(path: '/delete/{id}', name: 'cap_bill_delete', methods: ['POST'])]
    public function delete(Request $request, PaymentBill $paymentBill): Response
    {
        $order = $paymentBill->getOrder();
        if ($this->isCsrfTokenValid('delete' . $paymentBill->getId(), $request->request->get('_token'))) {
            $this->paymentBillRepository->remove($paymentBill);
            $this->paymentBillRepository->flush();

            $this->addFlash('success', 'Commande supprimée');
        }

        return $this->redirectToRoute('cap_order_show', ['id' => $order->getId()]);
    }
}
