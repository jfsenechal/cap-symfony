<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\News;
use Cap\Commercio\Form\NewsType;
use Cap\Commercio\Mailer\NewsMailer;
use Cap\Commercio\Repository\NewsRepository;
use Cap\Commercio\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/news')]
#[IsGranted('ROLE_CAP')]
class NewsController extends AbstractController
{
    public function __construct(
        private readonly NewsRepository $newsRepository,
        private readonly ImageService $imageService
    ) {
    }

    #[Route('/', name: 'cap_news_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        $news = $this->newsRepository->findAllOrdered();

        return $this->render('@CapCommercio/news/index.html.twig', [
            'news' => $news,
        ]);
    }

    #[Route('/new', name: 'cap_news_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $imageName = $this->imageService->upload($news->image);
                $news->setUuid($news->generateUuid());
                $news->setMediaPath($imageName);
                $news->setInsertDate(new \DateTime());
                $news->setModifyDate(new \DateTime());
                $this->newsRepository->persist($news);
                $this->newsRepository->flush();
                $this->addFlash('success', 'News ajoutée');
            } catch (\Exception $exception) {
                $this->addFlash('danger', $exception->getMessage());
            }

            return $this->redirectToRoute('cap_news_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@CapCommercio/news/new.html.twig', [
            'news' => $news,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cap_news_show', methods: ['GET'])]
    public function show(News $news): Response
    {
        $mark = $this->markdownHelper->markdownToHtml($news->getDescription());

        return $this->render('@CapCommercio/news/show.html.twig', [
            'news' => $news,
            'mark' => $mark,
        ]);
    }

    #[Route('/{id}/edit', name: 'cap_news_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        News $news,
    ): Response {
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->newsRepository->flush();
            $this->addFlash('success', 'La modification a été faite');

            return $this->redirectToRoute(
                'cap_news_show',
                ['id' => $news->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('@CapCommercio/news/edit.html.twig', [
            'news' => $news,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'cap_news_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        News $news,
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$news->getId(), $request->request->get('_token'))) {
            $this->newsRepository->remove($news);
            $this->newsRepository->flush();
        }

        return $this->redirectToRoute('cap_news_index', [], Response::HTTP_SEE_OTHER);
    }
}
