<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\ContactParams;
use Cap\Commercio\Repository\ContactParamsRepository;
use Cap\Commercio\Repository\ContactRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/contact')]
#[IsGranted(data: 'ROLE_CAP')]
class ContactController extends AbstractController
{
    public function __construct(
        private ContactRepository $contactRepository,
        private ContactParamsRepository $contactParamsRepository
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