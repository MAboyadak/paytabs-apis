<?php

namespace App\Controllers;

use App\Helpers\SQL;
use stdClass;

class ContactController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        if(! isAuthenticated()){
            $_SESSION['error'] = 'You must be authenticated first';
            header('Location: /inisev/login');
            exit;
        }
    }

    public function showForm()
    {
        $fileName = 'contact-form';
        return view($fileName);
    }

    public function storeMessage()
    {
        $errors = [];
        $_POST = _trim($_POST);
        $this->sanitizeData();
        $this->validateData($_POST, $errors);

        if($errors){
            $_SESSION['errors'] = $errors;
            header('Location: /inisev/contact');
            return;
        }
        
        $params = [
            ':name' => $_POST['name'],
            ':email' => $_POST['email'],
            ':subject' => $_POST['subject'],
            ':message' => $_POST['message'],
        ];
        
        $query = 'insert into `messages` (`id`, `name`, `email`, `subject`, `message`) VALUES (NULL, :name, :email, :subject, :message)';
        try{
            SQL::save($query, $params);
        }catch(\PDOException $e){
            $_SESSION['error'] = 'Error in sending message :: ' . $e->getMessage();
            header('Location: /inisev/contact');
            return;
        }

        $_SESSION['success'] = 'Message Stored Successfully';
        header('Location: /inisev/messages');
        return;
    }

    public function getMessages()
    {
        if(! isAuthorized()){
            $_SESSION['error'] = 'You don\'t have permission to visit this page';
            header('Location: /inisev/contact');
            exit;
        }

        $fileName = 'messages';
        $messages = SQL::getAll('messages');
        $data = new stdClass();
        $data->messages = $messages;
        view($fileName, $data);
        return;
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
                
        if (isEmpty($data['subject'])) {
            $errors['subject'] = 'Subject Field Can\'t be empty';
        }

        if (isEmpty($data['message'])) {
            $errors['message'] = 'Message Field Can\'t be empty';
        }
        return;
    }

    private function sanitizeData()
    {
        $_POST['name'] = sanitize($_POST['name']);
        $_POST['email'] = sanitize($_POST['email'], 'email');
        $_POST['subject'] = sanitize($_POST['subject']);
        $_POST['message'] = sanitize($_POST['message']);
        return;
    }

}

?>