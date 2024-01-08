<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Bottin\BottinUtils;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Form\CommercantSearchType;
use Cap\Commercio\Form\CommercantType;
use Cap\Commercio\Repository\CommercantGalleryRepository;
use Cap\Commercio\Repository\CommercioCommercantAddressRepository;
use Cap\Commercio\Repository\CommercioCommercantHolidayRepository;
use Cap\Commercio\Repository\CommercioCommercantHoursRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Cap\Commercio\Repository\PaymentBillRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Cap\Commercio\Shop\MemberHandler;
use Cap\Commercio\Shop\ShopHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commercant')]
#[IsGranted('ROLE_CAP')]
class CommercantController extends AbstractController
{
    public function __construct(
        private readonly CommercioCommercantRepository $commercantRepository,
        private readonly BottinUtils $bottinUtils,
        private readonly CommercantGalleryRepository $commercantGalleryRepository,
        private readonly PaymentOrderRepository $paymentOrderRepository,
        private readonly PaymentBillRepository $paymentBillRepository,
        public readonly CommercioCommercantAddressRepository $commercioCommercantAddressRepository,
        private readonly CommercioCommercantHoursRepository $commercioCommercantHoursRepository,
        private readonly CommercioCommercantHolidayRepository $commercioCommercantHolidayRepository,
        private readonly ShopHandler $shopHandler,
        private readonly MemberHandler $memberHandler,
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
            'form' => $form,
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
        $hours = $this->commercioCommercantHoursRepository->findByCommercant($commercant);
        $holidays = $this->commercioCommercantHolidayRepository->findByCommercant($commercant);
        $address = null;
        $commercantAddress = $this->commercioCommercantAddressRepository->findOneByCommercant($commercant);
        if ($commercantAddress) {
            $address = $commercantAddress->getAddress();
        }
        $urlCap = $this->bottinUtils->urlCap($commercant);
        $isMemberComplete = $this->memberHandler->isMemberCompleted($commercant);

        return $this->render('@CapCommercio/commercant/show.html.twig', [
            'commercant' => $commercant,
            'address' => $address,
            'gallery' => $gallery,
            'orders' => $orders,
            'bills' => $bills,
            'hours' => $hours,
            'holidays' => $holidays,
            'urlCap' => $urlCap,
            'isMemberComplete' => $isMemberComplete,
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

    #[Route('/{id}', name: 'cap_commercant_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        CommercioCommercant $commercioCommercant,
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$commercioCommercant->getId(), $request->request->get('_token'))) {
            $this->shopHandler->removeCommercant($commercioCommercant);
            $this->addFlash('success', 'Le commerçant a bien été supprimé');
        }

        return $this->redirectToRoute('cap_commercant_index', [], Response::HTTP_SEE_OTHER);
    }
}
