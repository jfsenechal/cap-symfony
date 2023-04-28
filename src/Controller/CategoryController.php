<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\BlogCategory;
use Cap\Commercio\Form\BlogCategoryType;
use Cap\Commercio\Repository\BlogCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/blog_category')]
#[IsGranted('ROLE_CAP')]
class CategoryController extends AbstractController
{
    public function __construct(
        private BlogCategoryRepository $blog_categoryRepository,
    ) {
    }

    #[Route('/', name: 'cap_blog_category_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $categorys = $this->blog_categoryRepository->findAllOrdered();

        return $this->render('@CapCommercio/blog_category/index.html.twig', [
            'categorys' => $categorys,
        ]);
    }

    #[Route('/new', name: 'cap_blog_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $blogPost = new BlogCategory();
        $form = $this->createForm(BlogCategoryType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->blog_categoryRepository->persist($blogPost);
            $this->blog_categoryRepository->flush();

            return $this->redirectToRoute('cap_blog_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@CapCommercio/blog_category/new.html.twig', [
            'category' => $blogPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cap_blog_category_show', methods: ['GET'])]
    public function show(BlogCategory $blog_category): Response
    {
        return $this->render('@CapCommercio/blog_category/show.html.twig', [
            'category' => $blog_category,
        ]);
    }

    #[Route('/{id}/edit', name: 'cap_blog_category_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        BlogCategory $blog_category,
    ): Response {
        $form = $this->createForm(BlogCategoryType::class, $blog_category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->blog_categoryRepository->flush();
            $this->addFlash('success', 'La modification a été faite');

            return $this->redirectToRoute(
                'cap_blog_category_show',
                ['id' => $blog_category->getId()],
                Response::HTTP_SEE_OTHER
            );

        }

        return $this->render('@CapCommercio/blog_category/edit.html.twig', [
            'category' => $blog_category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cap_blog_category_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        BlogCategory $blogPost,
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$blogPost->getId(), $request->request->get('_token'))) {
            $this->blog_categoryRepository->remove($blogPost);
            $this->blog_categoryRepository->flush();
        }

        return $this->redirectToRoute('cap_blog_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
