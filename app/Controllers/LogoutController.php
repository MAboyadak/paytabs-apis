<?php

namespace App\Controllers;

use App\Helpers\SQL;
use Conf\DB;

class LogoutController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        
        if(! isAuthenticated()){
            header('Location: /inisev/login');
            exit;
        }
    }

    public function logout()
    {
        if( isset($_SESSION) && isset($_SESSION['user_id']) ){
            clearSession();
            session_start();
            $_SESSION['success'] = 'See you soon';
            header('Location: /inisev/login');
            exit;    
        }
    }

    

}

?>