<?php

namespace Cap\Commercio\Controller;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\CommercioCommercantGallery;
use Cap\Commercio\Form\ImageDropZoneType;
use Cap\Commercio\Repository\CommercantGalleryRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;

#[Route('/gallery')]
#[IsGranted('ROLE_CAP')]
class GalleryController extends AbstractController
{
    public function __construct(
        private readonly CommercantGalleryRepository $galleryRepository,
    ) {
    }

    #[Route('/{id}', name: 'cap_gallery_show', methods: ['GET'])]
    public function show(CommercioCommercant $commercant): Response
    {
        $gallery = $this->galleryRepository->findByCommercant($commercant);

        return $this->render('@CapCommercio/gallery/show.html.twig', [
            'commercant' => $commercant,
            'gallery' => $gallery,
        ]);
    }

    #[Route('/{id}/edit', name: 'cap_gallery_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        CommercioCommercant $commercant
    ): Response {
        $form = $this->createForm(ImageDropZoneType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UploadedFile[] $data
             */
            $data = $form->getData();
            foreach ($data['file'] as $file) {
                if ($file instanceof UploadedFile) {
                    $this->treatmentFile($file, $commercant);
                }
            }

            $this->addFlash('success', 'La modification a été faite');

            return $this->redirectToRoute(
                'cap_gallery_show',
                ['id' => $commercant->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('@CapCommercio/gallery/edit.html.twig', [
            'commercant' => $commercant,
            'form' => $form,
        ]);
    }

    private function treatmentFile(UploadedFile $file, CommercioCommercant $commercant): void
    {
        $image = new CommercioCommercantGallery();
        $image->setInsertDate(new DateTime());
        $image->setModifyDate(new DateTime());
        $image->setUuid(Uuid::v4());
        $image->setCommercioCommercant($commercant);

        $fileName = Uuid::v4() . '.' . $file->guessClientExtension();
        $pathToCopy = $this->getParameter('CAP_PATH') . '/' . $this->getParameter('CAP_FOLDER_IMAGE') . '/' . $fileName;

        $image->setMediaPath($this->getParameter('CAP_FOLDER_IMAGE') . '/' . $fileName);

        try {
            $file->move(
                $pathToCopy,
                $fileName
            );
        } catch (FileException|Exception $exception) {
            $this->addFlash('danger', 'Erreur upload image: ' . $exception->getMessage());

            return;
        }
        $this->galleryRepository->persist($image);
        $this->galleryRepository->flush();
    }

    #[Route('/delete', name: 'cap_gallery_delete', methods: ['POST'])]
    public function delete(
        Request $request,
    ): Response {
        $id = $request->request->getInt('imageid');
        if (0 === $id) {
            $this->addFlash('danger', 'Image non trouvée');

            return $this->redirectToRoute('cap_commercant_index');
        }

        $gallery = $this->galleryRepository->find(($id));
        if (!$gallery instanceof CommercioCommercantGallery) {
            $this->addFlash('danger', 'Image non trouvée');

            return $this->redirectToRoute('cap_commercant_index');
        }

        $id = $gallery->getCommercioCommercant()->getId();

        if ($this->isCsrfTokenValid('deleteimage', $request->request->get('_token'))) {
            $this->galleryRepository->remove($gallery);
            $this->galleryRepository->flush();
        }

        return $this->redirectToRoute('cap_gallery_show', ['id' => $id], Response::HTTP_SEE_OTHER);
    }
}
