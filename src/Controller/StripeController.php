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
        private readonly StripeCap $stripeCap
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

    #[Route(path: '/customers', name: 'stripe_customer_index', methods: ['GET'])]
    public function customers(): Response
    {
        try {
            $customers = $this->stripeCap->customersAll();
        } catch (ApiErrorException $e) {
            $this->addFlash('danger', $e->getMessage());

            return $this->redirectToRoute('stripe_home');
        }

        return $this->render(
            '@CapCommercio/stripe/customers.html.twig',
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

        return $this->render(
            '@CapCommercio/stripe/customer_show.html.twig',
            [
                'customer' => $customer,
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
                $customer = $this->stripeCap->createCustomer($commercant);
                $commercant->setStripeUserRef($customer->id);

                return $this->redirectToRoute('stripe_customer_show', ['id' => $customer->id]);

            } catch (ApiErrorException $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->redirectToRoute('stripe_home');
    }

    #[Route(path: '/payments', name: 'stripe_payment_index', methods: ['GET'])]
    public function payments(): Response
    {
        try {
            $payments = $this->stripeCap->listPayment();
        } catch (ApiErrorException $e) {

            $this->addFlash('danger', $e->getMessage());
            $payments = [];
        }

        return $this->render(
            '@CapCommercio/stripe/payments.html.twig',
            [
                'payments' => $payments,
            ]
        );
    }

}
