<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Stripe\StripeCap;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        return $this->render(
            '@CapCommercio/stripe/customer_show.html.twig',
            [
                'customer' => $customer,
            ]
        );
    }


}
