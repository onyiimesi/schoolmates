<?php

namespace App\Services\Upload;

use App\Services\ImageKit\DeleteService;
use App\Services\ImageKit\ImageKitService;
use Illuminate\Support\Facades\App;

class UploadService
{
    public $file;
    public $name;
    public $schId;
    public $data;

    public function __construct($file, $name, $schId, $data = null)
    {
        $this->file = $file;
        $this->name = $name;
        $this->schId = $schId;
        $this->data = $data;
    }

    public function run()
    {
        if(App::environment('production')){

            $file = $this->file;
            $folderName = $this->name;

            // Extract the file's MIME type
            $rawMimeType = substr($file, 5, strpos($file, ';') - 5);
            $mimeType = str_replace('@file/', '', $rawMimeType);

            // Map MIME types to file extensions
            $mimeToExt = [
                'application/pdf' => 'pdf',
                'application/msword' => 'doc',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                'vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
            ];

            $extension = isset($mimeToExt[$mimeType]) ? $mimeToExt[$mimeType] : 'bin';

            // Remove the base64 header
            $replace = substr($file, 0, strpos($file, ',') + 1);
            $base64Content = str_replace($replace, '', $file);
            $base64Content = str_replace(' ', '+', $base64Content);

            $file_name = time().'.'.$extension;
            $sch = $this->schId;
            $path = $folderName . '/' . $sch;

            if($this->data !== null){
                $fileId = $this->data->file_id;
                (new DeleteService($fileId, null))->run();
            }

            $response = (new ImageKitService($file, $file_name, $path))->run();
            $data = $response->getData();

        } elseif(App::environment(['staging', 'local'])){

            $file = $this->file;
            $folderName = config('services.base_url').$this->name;

            // Extract the file's MIME type
            $rawMimeType = substr($file, 5, strpos($file, ';') - 5);
            $mimeType = str_replace('@file/', '', $rawMimeType);

            // Map MIME types to file extensions
            $mimeToExt = [
                'application/pdf' => 'pdf',
                'application/msword' => 'doc',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                'vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
            ];

            $extension = isset($mimeToExt[$mimeType]) ? $mimeToExt[$mimeType] : 'bin';

            // Remove the base64 header
            $replace = substr($file, 0, strpos($file, ',') + 1);
            $base64Content = str_replace($replace, '', $file);
            $base64Content = str_replace(' ', '+', $base64Content);

            $file_name = uniqid().'.'.$extension;
            $sch = $this->schId;

            $folderPath = '/'. $this->name .'/'. $sch;
            if (!file_exists(public_path($folderPath))) {
                mkdir(public_path($folderPath), 0777, true);
            }

            $path = public_path().'/'.$this->name.'/'. $sch. '/'. $file_name;
            $success = file_put_contents($path, base64_decode($base64Content));

            if ($success === false) {
                throw new \Exception("Failed to write file to disk.");
            }

            $data = $folderName .'/'. $sch .'/'. $file_name;
        }

        return $data;
    }
}



