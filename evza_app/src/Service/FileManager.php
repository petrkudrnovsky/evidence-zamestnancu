<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
    private string $uploadedImagesDirectory;

    public function __construct(string $uploadedImagesDirectory)
    {
        $this->uploadedImagesDirectory = $uploadedImagesDirectory;
    }

    public function uploadImage(UploadedFile $file): ?string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newImageName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move(
                $this->uploadedImagesDirectory,
                $newImageName
            );
        } catch (FileException $e) {
            $newImageName = null;
        }

        return $newImageName;
    }
}