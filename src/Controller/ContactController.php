<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Repository\ContactParamsRepository;
use Cap\Commercio\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/contact')]
#[IsGranted('ROLE_CAP')]
class ContactController extends AbstractController
{
    public function __construct(
        private readonly ContactRepository $contactRepository,
        private readonly ContactParamsRepository $contactParamsRepository
    ) {
    }

    #[Route(path: '/', name: 'cap_contact', methods: ['GET'])]
    public function contact(): Response
    {
        $contacts = $this->contactRepository->findAllOrdered();

        return $this->render(
            '@CapCommercio/contact/index.html.twig',
            [
                'contacts' => $contacts,
            ]
        );
    }

    #[Route(path: '/show/{id}', name: 'cap_contact_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $contact = $this->contactRepository->find($id);
        $params = $this->contactParamsRepository->findByContact($contact);

        return $this->render(
            '@CapCommercio/contact/show.html.twig',
            [
                'contact' => $contact,
                'params' => $params,
            ]
        );
    }
}
