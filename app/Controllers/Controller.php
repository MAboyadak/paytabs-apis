<?php

namespace App\Controllers;

class Controller
{

    public function __construct()
    {
        session_start();
        include_once(HELPERS_PATH . 'auth.php');
        include_once(HELPERS_PATH . 'views.php');
        include_once(HELPERS_PATH . 'validation.php');
    }
}

?>