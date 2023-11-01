<?php

function isEmpty($data)
{
    if (!isset($data) || empty($data)){
        return true;
    }else{
        return false;
    }
} 

function sanitize($data, $type='string')
{
    if($type == 'string' || $type == 'password'){
        return htmlspecialchars($data);
    }elseif($type == 'email'){
        return filter_var($data, FILTER_SANITIZE_EMAIL);
    }
}

function _trim($data)
{
    foreach ($data as $key => $val) {
        $data[$key] = trim($val);
    }
    return $data;
}