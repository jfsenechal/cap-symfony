<?php

namespace Cap\Commercio\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class ImageService
{
    public function __construct(#[Autowire(env: 'CAP_PATH')] private readonly string $capPath)
    {
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $fileName = Uuid::v4().'.'.$file->getClientOriginalExtension();
        $file->move($this->capPath.'media', $fileName);

        return 'media'.$fileName;
    }
}