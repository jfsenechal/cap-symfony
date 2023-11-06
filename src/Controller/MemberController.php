<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Bottin\BottinApiRepository;
use Cap\Commercio\Bottin\BottinUtils;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Form\CheckMemberType;
use Cap\Commercio\Form\MemberType;
use Cap\Commercio\Mailer\MailerCap;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Cap\Commercio\Shop\MemberHandler;
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
        private readonly MailerCap $mailer,
        private readonly BottinApiRepository $bottinApiRepository,
        private readonly MemberHandler $memberHandler,
        private readonly BottinUtils $bottinUtils
    ) {
    }

    #[Route('/', name: 'cap_member_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        $commercants = $this->commercantRepository->membres();

        return $this->render('@CapCommercio/member/index.html.twig', [
            'commercants' => $commercants,
        ]);
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

        $commercioCommercant = $this->bottinUtils->newFromBottin($fiche);
        $commercioCommercant->generateOrder = true;

        $form = $this->createForm(MemberType::class, $commercioCommercant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $order = $this->memberHandler->newMember($commercioCommercant, $form->get('generateOrder')->getData());
            $this->addFlash('success', 'Le nouveau membre a bien été ajouté');

            if ($order) {
                try {
                    $this->memberHandler->generatePdf($order);
                } catch (Html2PdfException|LoaderError|RuntimeError|SyntaxError $e) {
                    $this->addFlash('danger', 'Erreur pour la création du pdf '.$e->getMessage());
                }
            }

            return $this->redirectToRoute('cap_commercant_show', ['id' => $commercioCommercant->getId()]);
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

        return $this->render('@CapCommercio/member/set.html.twig', [
            'commercant' => $commercant,
            'form' => $form,
        ]);
    }
}