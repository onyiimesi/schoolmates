<?php

use Illuminate\Support\Facades\Mail;

if (!function_exists('defer_email')) {
    function defer_email($email, $action) {
        defer(function () use ($email, $action) {
            Mail::to($email)->send($action);
        });
    }
}









