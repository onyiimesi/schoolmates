<?php

use App\Services\ImageKit\ImageKitService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use ImageKit\ImageKit;

if (!function_exists('defer_email')) {
    function defer_email($email, $action)
    {
        defer(function () use ($email, $action) {
            Mail::to($email)->send($action);
        });
    }
}

if (!function_exists('userAuth')) {
    function userAuth()
    {
        return Auth::user();
    }
}

if (!function_exists('getLocation')) {
    function getLocation($lat, $lon)
    {
        $client = new Client();

        $response = $client->get("https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}");
        $data = json_decode($response->getBody(), true);

        if (isset($data['display_name'])) {
            return $data['display_name'];
        } else {
            return 'Location not found';
        }
    }
}

if (!function_exists('getImageKit')) {
    function getImageKit()
    {
        return new ImageKit(
            config('services.imagekit.public_key'),
            config('services.imagekit.private_key'),
            config('services.imagekit.endpoint_key')
        );
    }
}

if (!function_exists('uploadImage')) {
    function uploadImage($file, $folder, $schId, $fileId = null) {
        $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
        $replace = substr($file, 0, strpos($file, ',')+1);
        $image = str_replace($replace, '', $file);
        $image = str_replace(' ', '+', $image);
        $file_name = time().'.'.$extension;

        $folderPath = $file_name;
        $folderName = $folder . '/' . $schId;

        return (new ImageKitService($file, $folderPath, $folderName, $fileId))->run();
    }
}

if (!function_exists('uploadSignature')) {
    function uploadSignature($file, $folder, $schId, $fileId = null) {
        $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
        $replace = substr($file, 0, strpos($file, ',')+1);
        $image = str_replace($replace, '', $file);
        $image = str_replace(' ', '+', $image);
        $file_name = uniqid().'.'.$extension;

        $folderPath = $file_name;
        $folderName = $folder . '/' . $schId;

        return (new ImageKitService($file, $folderPath, $folderName, $fileId))->run();
    }
}

