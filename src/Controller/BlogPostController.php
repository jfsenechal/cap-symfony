<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\BlogPost;
use Cap\Commercio\Form\BlogPostSearchType;
use Cap\Commercio\Form\BlogPostType;
use Cap\Commercio\Repository\BlogPostRepository;
use Cap\Commercio\Repository\RepositoryUtils;
use Cap\Commercio\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/blog_post')]
#[IsGranted('ROLE_CAP')]
class BlogPostController extends AbstractController
{
    public function __construct(
        private readonly BlogPostRepository $blog_postRepository,
        private readonly RepositoryUtils $repositoryUtils,
        private readonly ImageService $imageService
    ) {
    }

    #[Route('/', name: 'cap_blog_post_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(BlogPostSearchType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $posts = $this->blog_postRepository->search($data['name']);
        } else {
            $posts = $this->blog_postRepository->findAllOrdered();
        }

        return $this->render('@CapCommercio/blog_post/index.html.twig', [
            'posts' => $posts,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'cap_blog_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $blogPost = new BlogPost();
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $imageName = $this->imageService->upload($blogPost->image);
                $blogPost->setUuid($blogPost->generateUuid());
                $blogPost->setMediaPath($imageName);
                $blogPost->setInsertDate(new \DateTime());
                $blogPost->setModifyDate(new \DateTime());
                $blogPost->setPublishDate(new \DateTime());

                $this->blog_postRepository->persist($blogPost);
                $this->blog_postRepository->flush();
            } catch (\Exception $exception) {
                $this->addFlash('danger', $exception->getMessage());
            }

            return $this->redirectToRoute('cap_blog_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@CapCommercio/blog_post/new.html.twig', [
            'post' => $blogPost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cap_blog_post_show', methods: ['GET'])]
    public function show(BlogPost $blog_post): Response
    {
        $this->repositoryUtils->setTagsToPost($blog_post);
        $this->repositoryUtils->setCategoriesToPost($blog_post);

        return $this->render('@CapCommercio/blog_post/show.html.twig', [
            'post' => $blog_post,
        ]);
    }

    #[Route('/{id}/edit', name: 'cap_blog_post_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        BlogPost $blog_post,
    ): Response {
        $this->repositoryUtils->setTagsToPost($blog_post);
        $this->repositoryUtils->setCategoriesToPost($blog_post);

        $form = $this->createForm(BlogPostType::class, $blog_post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            foreach ($data->categories as $category) {
                $blog_post->addBlogCategory($category);
            }

            $this->blog_postRepository->flush();
            $this->addFlash('success', 'La modification a été faite');

            return $this->redirectToRoute(
                'cap_blog_post_show',
                ['id' => $blog_post->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('@CapCommercio/blog_post/edit.html.twig', [
            'post' => $blog_post,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cap_blog_post_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        BlogPost $blogPost,
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$blogPost->getId(), $request->request->get('_token'))) {
            $this->blog_postRepository->remove($blogPost);
            $this->blog_postRepository->flush();
        }

        return $this->redirectToRoute('cap_blog_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
