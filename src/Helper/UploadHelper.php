<?php

namespace Cap\Commercio\Helper;

use Cap\Commercio\Entity\BlogPost;
use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\CommercioCommercantGallery;
use Cap\Commercio\Entity\News;
use Cap\Commercio\Repository\CommercantGalleryRepository;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class UploadHelper
{
    public function __construct(
        #[Autowire('%env(CAP_PATH)%')] private readonly string $capPath,
        #[Autowire('%env(CAP_PATH_SF)%')] private readonly string $capSfPath,
        private readonly CommercantGalleryRepository $galleryRepository,
    ) {
    }

    /**
     * @param UploadedFile $file
     * @param CommercioCommercant $commercant
     * @return void
     * @throws \Exception
     */
    public function treatmentFile(UploadedFile $file, CommercioCommercant $commercant): void
    {
        $image = new CommercioCommercantGallery();
        $image->setInsertDate(new \DateTime());
        $image->setModifyDate(new \DateTime());
        $image->setUuid(Uuid::v4());
        $image->setCommercioCommercant($commercant);

        $fileName = Uuid::v4().'.'.$file->guessClientExtension();
        $pathToCopyToCap = $this->capPath.'/media/';

        $image->setMediaPath('/media/'.$fileName);

        try {
            $this->moveAndCopyFile($file, $pathToCopyToCap, $fileName);
        } catch (FileException|\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        $this->galleryRepository->persist($image);
        if (count($this->galleryRepository->findByCommercant($commercant)) === 0) {
            $commercant->setCommercialWordMediaPath('/media/'.$fileName);
        }
        $this->galleryRepository->flush();
    }


    public function upload(BlogPost|News $post): void
    {
        $file = $post->image;

        if ($file instanceof UploadedFile) {

            $fileName = Uuid::v4().'.'.$file->guessClientExtension();
            $pathToCopyToCap = $this->capPath.'/media/';
            $post->setMediaPath('/media/'.$fileName);

            try {
                $this->moveAndCopyFile($file, $pathToCopyToCap, $fileName);
            } catch (FileException|\Exception $exception) {
                throw new \Exception($exception->getMessage());
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function moveAndCopyFile(UploadedFile $file, string $pathToCopyToCap, string $fileName): void
    {
        try {
            $file->move(
                $pathToCopyToCap,
                $fileName
            );
        } catch (FileException|\Exception $exception) {
            throw new \Exception('Erreur upload image: '.$exception->getMessage());
        }

        try {
            $filesystem = new Filesystem();
            $pathToCopyToCapSf = $this->capSfPath.'/public/media/'.$fileName;
            $filesystem->copy($pathToCopyToCap.$fileName, $pathToCopyToCapSf);
        } catch (FileException|\Exception $exception) {
            throw new \Exception('Erreur copy image: '.$exception->getMessage());
        }

    }
}