<?php

namespace App\Services\ImageKit;

use Illuminate\Http\JsonResponse;
use ImageKit\ImageKit;

class ImageKitService {

    public $file;
    public $folderPath;
    public $folderName;

    public function __construct($file, $folderPath, $folderName)
    {
        $this->file = $file;
        $this->folderPath = $folderPath;
        $this->folderName = $folderName;
    }

    public function run(): JsonResponse
    {
        $imageKit = new ImageKit(
            config('services.imagekit.public_key'),
            config('services.imagekit.private_key'),
            config('services.imagekit.endpoint_key')
        );

        $uploadFile = $imageKit->uploadFile([
            'file' => $this->file,
            'fileName' => $this->folderPath,
            'folder' => $this->folderName
        ]);

        $url = $uploadFile->result->url;
        $fileId = $uploadFile->result->fileId;

        return response()->json([
            'url' => $url,
            'file_id' => $fileId
        ]);
    }
}

