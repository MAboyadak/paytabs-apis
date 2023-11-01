<?php

namespace App\Controllers;

use App\Helpers\SQL;
use Conf\DB;

class RegisterController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        
        if(isAuthenticated()){
            header('Location: /inisev/contact');
            exit;
        }
    }

    public function showRegisterForm()
    {
        return view('register','','auth-layout');
    }

    public function createNewUser()
    {
        $errors = [];
        
        $_POST = _trim($_POST);
        $this->sanitizeData($_POST);
        $this->validateData($_POST, $errors);

        if($errors){
            $_SESSION['errors'] = $errors;
            header('Location: /inisev/register');
            return;
        }
                
        $params = [
            ':name' => $_POST['name'],
            ':email' => $_POST['email'],
            ':password' => $_POST['password'],
        ];

        
        try{
            DB::connect()->beginTransaction();

            $query = 'insert into `users` (`name`, `email`, `password`) VALUES (:name, :email, :password)';
            
            $user_id = SQL::save($query, $params);
            
            setAuthenticated($user_id);

            $_SESSION['success'] = 'Registeration Completed Successfully';

            DB::connect()->commit();

            header('Location: /inisev/contact');
            return;

        }catch(\PDOException $e){

            DB::connect()->rollBack();

            if( isset($_SESSION) && isset($_SESSION['user_id']) ){
                clearSession();
            }

            $_SESSION['error'] = 'Error in Registeration :: ' . $e->getMessage();
            header('Location: /inisev/register');
            return;
        }
      
    }

    private function validateData($data, &$errors)
    {
        if (isEmpty($data['name'])) {
            $errors['name'] = 'Name Field Can\'t be empty';
        }

        if (isEmpty($data['email'])) {
            $errors['email'] = 'Email Field Can\'t be empty';
        }

        if (isset($data['email']) &&  ( !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ) ) {
            $errors['email'] = 'Email Field is not valid';
        }

        if (isEmpty($data['password'])) {
            $errors['password'] = 'Password Field Can\'t be empty';
        }

        if (isEmpty($data['confirm_password'])) {
            $errors['confirm_password'] = 'Confirm Password Field Can\'t be empty';
        }

        if ($data['confirm_password'] !== $data['password']){
            $errors['password'] = 'Password and cofirmation are not the same !';
        }

    }

    private function sanitizeData()
    {
        $_POST['name'] = sanitize($_POST['name']);
        $_POST['email'] = sanitize($_POST['email'], 'email');
        $_POST['password'] = sanitize($_POST['password']);
        $_POST['confirm-password'] = sanitize($_POST['confirm-password']);
        return;
    }

}

?>