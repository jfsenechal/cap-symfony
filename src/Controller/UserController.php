<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\RightAccess;
use Cap\Commercio\Form\UserNewType;
use Cap\Commercio\Form\UserPasswordType;
use Cap\Commercio\Form\UserSearchType;
use Cap\Commercio\Form\UserType;
use Cap\Commercio\Repository\AccessDemandRepository;
use Cap\Commercio\Repository\AdministratorRepository;
use Cap\Commercio\Repository\CommercioCommercantRepository;
use Cap\Commercio\Repository\RightAccessRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/user')]
#[IsGranted('ROLE_CAP')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly RightAccessRepository $rightAccessRepository,
        private readonly AdministratorRepository $administratorRepository,
        private readonly CommercioCommercantRepository $commercantRepository,
        private readonly AccessDemandRepository $accessDemandRepository,
    ) {
    }

    #[Route(path: '/', name: 'cap_user_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(UserSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $users = $this->rightAccessRepository->search($data['name']);
        } else {
            $users = $this->rightAccessRepository->findAll();
        }

        $response = new Response(null, $form->isSubmitted() ? Response::HTTP_ACCEPTED : Response::HTTP_OK);

        return $this->render(
            '@CapCommercio/user/index.html.twig',
            [
                'users' => $users,
                'form' => $form,
            ]
            , $response
        );
    }

    #[Route('/{id}/new', name: 'cap_user_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        CommercioCommercant $commercant,
    ): Response {
        $user = new RightAccess();
        $user->setEmail($commercant->getLegalEmail());
        $form = $this->createForm(UserNewType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->rightAccessRepository->findByEmail($user->getEmail()) instanceof RightAccess) {
                $this->addFlash('danger', 'L\'adresse email est déjà prise sur un autre compte');

                return $this->redirectToRoute(
                    'cap_user_new',
                    ['id' => $commercant->getId()],
                    Response::HTTP_SEE_OTHER
                );
            } else {
                $commercant->setRightAccess($user);
                $data = $form->getData();
                $password = md5((string)$data->password_plain);
                $user->setUuid($user->generateUuid());
                $user->setPrivilegeId(3);
                $user->setPassword($password);
                $user->setInsertDate(new \DateTime());
                $user->setModifyDate(new \DateTime());
                $this->rightAccessRepository->persist($user);
                $this->rightAccessRepository->flush();
                $this->addFlash('success', 'L\'utilisateur a bien été créé');
            }

            return $this->redirectToRoute(
                'cap_user_show',
                ['id' => $user->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('@CapCommercio/user/new.html.twig', [
            'commercant' => $commercant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cap_user_show', methods: ['GET'])]
    public function show(RightAccess $rightAccess): Response
    {
        $commercants = $this->commercantRepository->findBy(['rightAccess' => $rightAccess]);

        return $this->render('@CapCommercio/user/show.html.twig', [
            'user' => $rightAccess,
            'commercants' => $commercants,
        ]);
    }

    #[Route('/{id}/edit', name: 'cap_user_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        RightAccess $user,
    ): Response {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($this->rightAccessRepository->checkExist($data->getEmail(), $user) instanceof RightAccess) {
                $this->addFlash('danger', 'L\'adresse email est déjà prise sur un autre compte');

                return $this->redirectToRoute(
                    'cap_user_show',
                    ['id' => $user->getId()],
                    Response::HTTP_SEE_OTHER
                );
            } else {
                $this->rightAccessRepository->flush();
                $this->addFlash('success', 'La modification a été faite');
            }

            return $this->redirectToRoute(
                'cap_user_show',
                ['id' => $user->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('@CapCommercio/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/password', name: 'cap_user_password', methods: ['GET', 'POST'])]
    public function password(
        Request $request,
        RightAccess $user,
    ): Response {
        $form = $this->createForm(UserPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $password = md5((string)$data->password_plain);
            $user->setPassword($password);

            $this->rightAccessRepository->flush();
            $this->addFlash('success', 'La modification a été faite');

            return $this->redirectToRoute(
                'cap_user_show',
                ['id' => $user->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('@CapCommercio/user/password.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route(path: '/administrator', name: 'cap_administrator', methods: ['GET'])]
    public function administrator(): Response
    {
        $administrators = $this->administratorRepository->findAll();

        return $this->render(
            '@CapCommercio/user/administrator.html.twig',
            [
                'administrators' => $administrators,
            ]
        );
    }

    #[Route(path: '/access/demand', name: 'cap_access_demand', methods: ['GET'])]
    public function accessDemand(): Response
    {
        $demands = $this->accessDemandRepository->findAllOrdered();

        return $this->render(
            '@CapCommercio/user/demands.html.twig',
            [
                'demands' => $demands,
            ]
        );
    }

    #[Route('/{id}', name: 'cap_user_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        RightAccess $rightAccess,
    ): Response {

        if ($this->isCsrfTokenValid('delete'.$rightAccess->getId(), $request->request->get('_token'))) {
            if ($commercioCommercant = $this->commercantRepository->findByRightAccess($rightAccess)) {
                $commercioCommercant->setRightAccess(null);
                if ($demand = $this->accessDemandRepository->findByRightAccess($rightAccess)) {
                    $this->rightAccessRepository->remove($demand);
                }
            }
            $this->rightAccessRepository->remove($rightAccess);
            $this->rightAccessRepository->flush();
            $this->addFlash('success', 'L\'utilisateur a bien été supprimé');
        }

        return $this->redirectToRoute('cap_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
