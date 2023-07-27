<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Stripe\StripeCap;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/stripe')]
#[IsGranted('ROLE_CAP')]
class StripeController extends AbstractController
{
    public function __construct(
        private StripeCap $stripeCap
    ) {
    }

    #[Route(path: '/', name: 'stripe_home', methods: ['GET'])]
    public function index(): Response
    {
        try {
            $customers = $this->stripeCap->customersAll();
        } catch (ApiErrorException $e) {
            $this->addFlash('danger', $e->getMessage());

            return $this->redirectToRoute('stripe_home');
        }

        return $this->render(
            '@CapCommercio/stripe/index.html.twig',
            [
                'customers' => $customers,
            ]
        );
    }

    #[Route(path: '/{id}/show', name: 'stripe_customer_show', methods: ['GET'])]
    public function showCustomer(string $id): Response
    {
        try {
            $customer = $this->stripeCap->customerDetails($id);
        } catch (ApiErrorException $e) {
            $this->addFlash('danger', $e->getMessage());

            return $this->redirectToRoute('stripe_home');
        }

        try {
            $payments = $this->stripeCap->listPayment($id);
        } catch (ApiErrorException $e) {

            $this->addFlash('danger', $e->getMessage());
            $payments = [];
        }

        return $this->render(
            '@CapCommercio/stripe/customer_show.html.twig',
            [
                'customer' => $customer,
                'payments' => $payments,
            ]
        );
    }

    #[Route(path: '/{id}/new', name: 'stripe_customer_new', methods: ['POST'])]
    public function newCustomer(Request $request, CommercioCommercant $commercant): Response
    {
        if ($this->isCsrfTokenValid('paid'.$commercant->getId(), $request->request->get('_token'))) {

            if ($commercant->getStripeUserRef()) {
                $this->addFlash('warning', 'Ce commerçant a déjà un compte stripe');

                return $this->redirectToRoute('stripe_customer_show', ['id' => $commercant->getStripeUserRef()]);
            }

            try {
                $customer = $this->stripeCap->createPaymentCustomer($commercant);
                $commercant->setStripeUserRef($customer->id);

                return $this->redirectToRoute('stripe_customer_show', ['id' => $customer->id]);

            } catch (ApiErrorException $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->redirectToRoute('stripe_home');
    }

}
