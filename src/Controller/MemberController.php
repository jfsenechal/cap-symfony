<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Bottin\BottinApiRepository;
use Cap\Commercio\Bottin\BottinUtils;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Form\CheckMemberType;
use Cap\Commercio\Form\MemberType;
use Cap\Commercio\Form\NameSearchType;
use Cap\Commercio\Mailer\MailerCap;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Cap\Commercio\Repository\PaymentOrderRepository;
use Cap\Commercio\Shop\MemberHandler;
use Cap\Commercio\Shop\SendExpiredForm;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[Route(path: '/member')]
#[IsGranted('ROLE_CAP')]
class MemberController extends AbstractController
{
    public function __construct(
        private readonly CommercioCommercantRepository $commercantRepository,
        private readonly PaymentOrderRepository $paymentOrderRepository,
        private readonly MailerCap $mailer,
        private readonly BottinApiRepository $bottinApiRepository,
        private readonly MemberHandler $memberHandler,
        private readonly BottinUtils $bottinUtils
    ) {
    }

    #[Route('/', name: 'cap_member_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(NameSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $commercants = $this->commercantRepository->search($data['name'], 1);
        } else {
            $commercants = $this->commercantRepository->findMembers();
        }

        foreach ($commercants as $commercant) {
            $commercant->complete = $this->memberHandler->isMemberCompleted($commercant);
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_ACCEPTED : Response::HTTP_OK);

        return $this->render('@CapCommercio/member/index.html.twig', [
            'commercants' => $commercants,
            'form' => $form->createView(),
        ], $response);
    }

    #[Route('/select', name: 'cap_member_select_from_bottin', methods: ['GET', 'POST'])]
    public function select(): Response
    {
        try {
            $fiches = $this->bottinApiRepository->getCommerces();
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Impossible d\'obtenir la liste des commerces');

            return $this->redirectToRoute('cap_home');
        }

        return $this->render('@CapCommercio/member/select.html.twig', [
            'fiches' => $fiches,
        ]);
    }

    #[Route('/new/{id}', name: 'cap_member_new_from_bottin', methods: ['GET', 'POST'])]
    public function new(Request $request, int $id): Response
    {
        try {
            $fiche = $this->bottinApiRepository->findCommerceById($id);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Impossible d\'obtenir la liste des commerces');

            return $this->redirectToRoute('cap_home');
        }

        $commercant = $this->bottinUtils->newFromBottin($fiche);
        $commercant->setIsMember(true);
        $commercant->generateOrder = true;

        $form = $this->createForm(MemberType::class, $commercant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $order = $this->memberHandler->newMember($commercant, $form->get('generateOrder')->getData());
                $this->addFlash('success', 'Le nouveau membre a bien été ajouté');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur: '.$e->getMessage());

                return $this->redirectToRoute('cap_commercant_show', ['id' => $commercant->getId()]);
            }

            if ($order) {
                try {
                    $this->memberHandler->generateOrderPdf($order);
                } catch (Html2PdfException|LoaderError|RuntimeError|SyntaxError $e) {
                    $this->addFlash('danger', 'Erreur pour la création du pdf '.$e->getMessage());
                }

                try {
                    $this->mailer->sendNewAffiliation($commercant, $order);
                    $this->addFlash('success', 'Le bon a bien été envoyé par mail');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'envoie du mail: '.$e->getMessage());
                }
            }

            return $this->redirectToRoute('cap_commercant_show', ['id' => $commercant->getId()]);
        }

        return $this->render('@CapCommercio/member/new.html.twig', [
            'fiche' => $fiche,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/set', name: 'cap_member_set', methods: ['GET', 'POST'])]
    public function setMember(
        Request $request,
        CommercioCommercant $commercant
    ): Response {
        $form = $this->createForm(CheckMemberType::class, $commercant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($data->isIsMember() === false) {
                $this->memberHandler->disaffiliated($commercant->getId());
                $this->addFlash('success', 'Le membre a été désaffilié');
            } else {
                $this->memberHandler->affiliated($commercant->getId());
                $this->addFlash('success', 'Le commerçant a été affilié');
            }

            return $this->redirectToRoute(
                'cap_commercant_show',
                ['id' => $commercant->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('@CapCommercio/member/set.html.twig', [
            'commercant' => $commercant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/reminder', name: 'cap_member_reminder', methods: ['GET', 'POST'])]
    public function reminder(
        Request $request,
        CommercioCommercant $commercant
    ): Response {

        $order = $orderCommercant = null;
        $orders = $this->paymentOrderRepository->findByCommercantIdAndNotPaid($commercant->getId());
        if (count($orders) == 1) {
            $order = $orders[0];
            $orderCommercant = $order->getOrderCommercant();
        }

        $form = $this->createForm(SendExpiredForm::class, $commercant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$order) {
                try {
                    $order = $this->memberHandler->generateNewOrder($commercant);
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Erreur pour générer un ordre de paiement: '.$e->getMessage());

                    return $this->redirectToRoute('cap_commercant_show', ['id' => $commercant->getId()]);
                }
            }

            if ($order) {
                try {
                    $this->memberHandler->generateOrderPdf($order);
                } catch (Html2PdfException|LoaderError|RuntimeError|SyntaxError $e) {
                    $this->addFlash('danger', 'Erreur pour la création du pdf '.$e->getMessage());
                }
                try {
                    $this->mailer->sendAffiliationExpired($commercant, $order);
                    $this->addFlash('success', 'Le mail a bien été envoyé');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'envoie du mail: '.$e->getMessage());
                }
            }

            return $this->redirectToRoute(
                'cap_commercant_show',
                ['id' => $commercant->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('@CapCommercio/member/reminder.html.twig', [
            'commercant' => $commercant,
            'paymentOrder' => $order,
            'orderCommercant' => $orderCommercant,
            'form' => $form,
        ]);
    }
}