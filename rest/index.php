<?php

require "../vendor/autoload.php";
require "./services/MidtermService.php";
require "./services/FinalService.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::register('midtermService', 'MidtermService');
Flight::register('finalService', 'FinalService');

require 'routes/MidtermRoutes.php';
require 'routes/FinalRoutes.php';



/** TODO
 * Add middleware to protect routes rest/final/share_classes AND rest/final/share_class_categories
 */

Flight::route('/*', function () {
    //perform JWT decode
    $path = Flight::request()->url;
    if ($path == '/final/login' || $path == '/final/investor') return TRUE; // exclude login route from middleware

    $headers = getallheaders();

    if (@!$headers['Authorization']) {
        Flight::json(["message" => "Authorization is missing"], 403);
        return FALSE;
    } else {
        try {
            $decoded = (array)JWT::decode($headers['Authorization'], new Key('ABC123', 'HS256'));
            Flight::set('user', $decoded);
            return TRUE;
        } catch (\Exception $e) {
            Flight::json(["message" => "Authorization token is not valid"], 403);
            return FALSE;
        }
    }
});





Flight::start();
