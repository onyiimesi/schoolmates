<?php

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
        return auth()->user();
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



