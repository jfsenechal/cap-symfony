<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Bottin\BottinUtils;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Form\CheckMemberType;
use Cap\Commercio\Form\CommercantSearchType;
use Cap\Commercio\Form\CommercantType;
use Cap\Commercio\Mailer\MailerCap;
use Cap\Commercio\Repository\CommercantGalleryRepository;
use Cap\Commercio\Repository\CommercioCommercantHolidayRepository;
use Cap\Commercio\Repository\CommercioCommercantHoursRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commercant')]
#[IsGranted('ROLE_CAP')]
class CommercantController extends AbstractController
{
    public function __construct(
        private CommercioCommercantRepository $commercantRepository,
        private BottinUtils $bottinUtils,
        private CommercantGalleryRepository $commercantGalleryRepository,
        private PaymentOrderRepository $paymentOrderRepository,
        private PaymentBillRepository $paymentBillRepository,
        private CommercioCommercantHoursRepository $commercioCommercantHoursRepository,
        private CommercioCommercantHolidayRepository $commercioCommercantHolidayRepository,
        private MailerCap $mailer
    ) {
    }

    #[Route('/', name: 'cap_commercant_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(CommercantSearchType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $commercants = $this->commercantRepository->search($data['name'], $data['isMember']);
        } else {
            $commercants = $this->commercantRepository->findAllOrdered();
        }

        return $this->render('@CapCommercio/commercant/index.html.twig', [
            'commercants' => $commercants,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/membres', name: 'cap_commercant_membres', methods: ['GET', 'POST'])]
    public function membres(): Response
    {
        $commercants = $this->commercantRepository->membres();

        return $this->render('@CapCommercio/commercant/members.html.twig', [
            'commercants' => $commercants,
        ]);
    }

    #[Route('/new', name: 'cap_commercant_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $commercioCommercant = new CommercioCommercant();
        $form = $this->createForm(CommercantType::class, $commercioCommercant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commercantRepository->persist($commercioCommercant);
            $this->commercantRepository->flush();

            return $this->redirectToRoute('cap_commercant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@CapCommercio/commercant/new.html.twig', [
            'commercant' => $commercioCommercant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cap_commercant_show', methods: ['GET'])]
    public function show(CommercioCommercant $commercant): Response
    {
        $gallery = $this->commercantGalleryRepository->findByCommercant($commercant);
        $orders = $this->paymentOrderRepository->findByCommercantId($commercant->getId());
        $bills = $this->paymentBillRepository->findByCommercant($commercant);
        $hours = $this->commercioCommercantHoursRepository->findByCommercerant($commercant);
        $holidays = $this->commercioCommercantHolidayRepository->findByCommercerant($commercant);
        $urlCap = $this->bottinUtils->urlCap($commercant);

        return $this->render('@CapCommercio/commercant/show.html.twig', [
            'commercant' => $commercant,
            'gallery' => $gallery,
            'orders' => $orders,
            'bills' => $bills,
            'hours' => $hours,
            'holidays' => $holidays,
            'urlCap' => $urlCap,
        ]);
    }

    #[Route('/{id}/edit', name: 'cap_commercant_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        CommercioCommercant $commercant
    ): Response {
        $form = $this->createForm(CommercantType::class, $commercant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commercantRepository->flush();
            $this->addFlash('success', 'La modification a été faite');

            return $this->redirectToRoute(
                'cap_commercant_show',
                ['id' => $commercant->getId()],
                Response::HTTP_SEE_OTHER
            );

        }

        return $this->render('@CapCommercio/commercant/edit.html.twig', [
            'commercant' => $commercant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/member', name: 'cap_commercant_member', methods: ['GET', 'POST'])]
    public function member(
        Request $request,
        CommercioCommercant $commercant
    ): Response {
        $form = $this->createForm(CheckMemberType::class, $commercant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($data->isIsMember() === false) {
                $commercant->setAffiliationDate(null);
            }
            $this->commercantRepository->flush();
            if ($data->sendMailExpired) {
                try {
                    $this->mailer->sendAffiliationExpired($commercant, $this->getParameter('kernel.environment'));
                    $this->addFlash('success', 'Le mail a bien été envoyé');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'envoie du mail: '.$e->getMessage());
                }
            }
            $this->addFlash('success', 'Le commerçant a été modifié');

            return $this->redirectToRoute(
                'cap_commercant_show',
                ['id' => $commercant->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('@CapCommercio/commercant/member.html.twig', [
            'commercant' => $commercant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cap_commercant_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        CommercioCommercant $commercioCommercant,
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$commercioCommercant->getId(), $request->request->get('_token'))) {
            $this->commercantRepository->remove($commercioCommercant);
            $this->commercantRepository->flush();
        }

        return $this->redirectToRoute('cap_commercant_index', [], Response::HTTP_SEE_OTHER);
    }
}
