<?php

namespace App\Controllers;

use App\Helpers\SQL;
use Conf\DB;

class LoginController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        
        if(isAuthenticated()){
            header('Location: /inisev/contact');
            exit;
        }
    }

    public function showLoginForm()
    {
        return view('login','','auth-layout');
    }

    public function loginUser()
    {
        $errors = [];
        
        $_POST = _trim($_POST);
        $this->sanitizeData($_POST);
        $this->validateData($_POST, $errors);

        if($errors){
            $_SESSION['errors'] = $errors;
            header('Location: /inisev/login');
            return;
        }

        $user = [
            'email'=>$_POST['email'] ,
            'password' => $_POST['password']
        ];

        try{
            $user = attempt($user);
        }catch(\PDOException $e){

            if( isset($_SESSION) && isset($_SESSION['user_id']) ){
                clearSession();
            }

            $_SESSION['error'] = 'Error in Login :: ' . $e->getMessage();
            header('Location: /inisev/login');
            return;
        }
        
        
        if(! $user){
            if( isset($_SESSION) && isset($_SESSION['user_id']) ){
                clearSession();
            }
            $_SESSION['error'] = 'Not-Valid Credentials';
            header('Location: /inisev/login');
            exit;
        }

        // valid user
        setAuthenticated($user['id']);
        $_SESSION['success'] = 'Welcome back '. $user['name'];

        header('Location: /inisev/contact');
        return;
    }

    private function validateData($data, &$errors)
    {
        if (isEmpty($data['email'])) {
            $errors['email'] = 'Email Field Can\'t be empty';
        }

        if (isset($data['email']) &&  ( !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ) ) {
            $errors['email'] = 'Email Field is not valid';
        }

        if (isEmpty($data['password'])) {
            $errors['password'] = 'Password Field Can\'t be empty';
        }

    }

    private function sanitizeData()
    {
        $_POST['email'] = sanitize($_POST['email'], 'email');
        $_POST['password'] = sanitize($_POST['password']);
        return;
    }

}

?>