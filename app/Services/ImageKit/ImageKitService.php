<?php

namespace App\Services\ImageKit;

use ImageKit\ImageKit;

class ImageKitService {
    public function __construct(
        public $file,
        public $folderPath,
        public $folderName,
        public $fileId = null
    )
    {}

    public function run(): array
    {
        $imageKit = new ImageKit(
            config('services.imagekit.public_key'),
            config('services.imagekit.private_key'),
            config('services.imagekit.endpoint_key')
        );

        if ($this->fileId) {
            $imageKit->deleteFile($this->fileId);
        }

        $uploadFile = $imageKit->uploadFile([
            'file' => $this->file,
            'fileName' => $this->folderPath,
            'folder' => $this->folderName
        ]);

        $url = $uploadFile->result->url;
        $fileId = $uploadFile->result->fileId;

        return [
            'url' => $url,
            'file_id' => $fileId
        ];
    }
}

