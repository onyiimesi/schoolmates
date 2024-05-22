<?php

namespace App\Services\ImageKit;

use Illuminate\Http\JsonResponse;
use ImageKit\ImageKit;

class DeleteService {

    public $fileId;
    public $imageIds;

    public function __construct($fileId = null, $imageIds = null)
    {
        $this->fileId = $fileId;
        $this->imageIds = $imageIds;
    }

    public function run(): void
    {
        $imageKit = new ImageKit(
            config('services.imagekit.public_key'),
            config('services.imagekit.private_key'),
            config('services.imagekit.endpoint_key')
        );

        if($this->fileId !== null){
            $imageKit->deleteFile($this->fileId);
        }

        if($this->imageIds !== null){
            $imageKit->bulkDeleteFiles($this->imageIds);
        }
    }
}

