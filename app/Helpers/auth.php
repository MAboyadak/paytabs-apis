<?php

use App\Helpers\SQL;
use Conf\DB;

function isAuthenticated()
{
    if( isset($_SESSION) && isset($_SESSION['user_id']) ){
        return true;
    }else{
        return false;
    }
}

function isAuthorized($role = 'user')
{
    $user = getAuth();
    if ($user['role'] == $role){
        return true;
    }else{
        return false;
    }
}

function getAuth()
{
    if(isAuthenticated()){
        return SQL::findById('users', getUserId());
    }else{
        return null;
    }
}

function getUserId()
{
    if(isAuthenticated()){
        return $_SESSION['user_id'];
    }
    return false;
}

function attempt($user)
{       
    $query = 'select * from users where email = :email';
    $params = [
        ':email' => $user['email'],
    ];
    $foundUser = SQL::findWithParams($query, $params);

    if($user['password'] === $foundUser['password']){
        return $foundUser;
    }else{
        return false;
    }

}

function setAuthenticated($user_id)
{
    clearSession();
    session_start();
    $_SESSION['user_id'] = $user_id;
}

function clearSession()
{
    session_unset();
    session_destroy();
}

?>