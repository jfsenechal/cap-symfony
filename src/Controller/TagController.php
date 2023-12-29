<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\BlogTag;
use Cap\Commercio\Form\BlogTagType;
use Cap\Commercio\Repository\BlogTagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/blog_tag')]
#[IsGranted('ROLE_CAP')]
class TagController extends AbstractController
{
    public function __construct(
        private readonly BlogTagRepository $blog_tagRepository,
    ) {
    }

    #[Route('/', name: 'cap_blog_tag_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        $tags = $this->blog_tagRepository->findAllOrdered();
        return $this->render('@CapCommercio/blog_tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    #[Route('/new', name: 'cap_blog_tag_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $blogPost = new BlogTag();
        $form = $this->createForm(BlogTagType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->blog_tagRepository->persist($blogPost);
            $this->blog_tagRepository->flush();

            return $this->redirectToRoute('cap_blog_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@CapCommercio/blog_tag/new.html.twig', [
            'tag' => $blogPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cap_blog_tag_show', methods: ['GET'])]
    public function show(BlogTag $blog_tag): Response
    {
        return $this->render('@CapCommercio/blog_tag/show.html.twig', [
            'tag' => $blog_tag,
        ]);
    }

    #[Route('/{id}/edit', name: 'cap_blog_tag_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        BlogTag $blog_tag,
    ): Response {
        $form = $this->createForm(BlogTagType::class, $blog_tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->blog_tagRepository->flush();
            $this->addFlash('success', 'La modification a été faite');

            return $this->redirectToRoute(
                'cap_blog_tag_show',
                ['id' => $blog_tag->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('@CapCommercio/blog_tag/edit.html.twig', [
            'tag' => $blog_tag,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cap_blog_tag_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        BlogTag $blogPost,
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $blogPost->getId(), $request->request->get('_token'))) {
            $this->blog_tagRepository->remove($blogPost);
            $this->blog_tagRepository->flush();
        }

        return $this->redirectToRoute('cap_blog_tag_index', [], Response::HTTP_SEE_OTHER);
    }
}
