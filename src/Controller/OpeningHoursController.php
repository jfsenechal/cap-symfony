<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\CommercioCommercantHoliday;
use Cap\Commercio\Entity\CommercioCommercantHours;
use Cap\Commercio\Form\HolidayType;
use Cap\Commercio\Form\HourType;
use Cap\Commercio\Repository\CommercioCommercantHolidayRepository;
use Cap\Commercio\Repository\CommercioCommercantHoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/hours/opening')]
#[IsGranted('ROLE_CAP')]
class OpeningHoursController extends AbstractController
{
    public function __construct(
        private readonly CommercioCommercantHoursRepository $commercioCommercantHoursRepository,
        private readonly CommercioCommercantHolidayRepository $commercioCommercantHolidayRepository,
    ) {
    }

    #[Route('/holiday/index', name: 'cap_holiday_index', methods: ['GET'])]
    public function holidays(): Response
    {
        $holidays = $this->commercioCommercantHolidayRepository->findAllOrdered();

        return $this->render('@CapCommercio/hours/holiday_index.html.twig', [
            'holidays' => $holidays,
        ]);
    }

    #[Route('/{id}/holiday/edit', name: 'cap_holiday_edit', methods: ['GET', 'POST'])]
    public function holidayEdit(
        Request $request,
        CommercioCommercantHoliday $holiday,
    ): Response {
        $form = $this->createForm(HolidayType::class, $holiday);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commercioCommercantHolidayRepository->flush();
            $this->addFlash('success', 'Le congé a été modifié');

            return $this->redirectToRoute(
                'cap_commercant_show',
                ['id' => $holiday->getCommercioCommercant()->getId()]
            );
        }

        return $this->render('@CapCommercio/hours/holiday_edit.html.twig', [
            '$holiday' => $holiday,
            'commercant' => $holiday->getCommercioCommercant(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/hour/edit', name: 'cap_hour_edit', methods: ['GET', 'POST'])]
    public function hoursEdit(
        Request $request,
        CommercioCommercantHours $hour,
    ): Response {
        $form = $this->createForm(HourType::class, $hour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commercioCommercantHoursRepository->flush();
            $this->addFlash('success', 'L\' horaire a été modifié');

            return $this->redirectToRoute('cap_commercant_show', ['id' => $hour->getCommercioCommercant()->getId()]);
        }

        return $this->render('@CapCommercio/hours/edit.html.twig', [
            'hour' => $hour,
            'commercant' => $hour->getCommercioCommercant(),
            'form' => $form,
        ]);
    }


    #[Route('/delete/holiday', name: 'cap_holiday_delete', methods: ['POST'])]
    public function holidayDelete(
        Request $request,
    ): Response {
        $holidayId = $request->request->getInt('holidayid');
        if (0 === $holidayId) {
            $this->addFlash('danger', 'Congé non trouvé');

            return $this->redirectToRoute('cap_commercant_index');
        }

        $holiday = $this->commercioCommercantHolidayRepository->find($holidayId);
        if (!$holiday instanceof CommercioCommercantHoliday) {
            $this->addFlash('danger', 'Congé non trouvé');

            return $this->redirectToRoute('cap_commercant_index');
        }

        $id = $holiday->getCommercioCommercant()->getId();
        if ($this->isCsrfTokenValid('deleteholiday', $request->request->get('_token'))) {
            $this->commercioCommercantHolidayRepository->remove($holiday);
            $this->commercioCommercantHolidayRepository->flush();
            $this->addFlash('success', 'Le congé a été effacé');
        }

        return $this->redirectToRoute('cap_commercant_show', ['id' => $id], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/holiday/{id}', name: 'cap_hour_delete', methods: ['POST'])]
    public function hourDelete(
        Request $request,
        CommercioCommercantHours $hours,
    ): Response {
        $id = $hours->getCommercioCommercant()->getId();
        if ($this->isCsrfTokenValid('delete' . $hours->getId(), $request->request->get('_token'))) {
            $this->commercioCommercantHoursRepository->remove($hours);
            $this->commercioCommercantHoursRepository->flush();
        }

        return $this->redirectToRoute('cap_commercant_show', ['id' => $id], Response::HTTP_SEE_OTHER);
    }
}
