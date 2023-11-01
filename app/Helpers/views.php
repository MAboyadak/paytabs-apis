<?php 

function view($fileName, $data = null, $layout = null)
{
    $layoutContent = renderLayout($layout);
    $viewContent = renderView($fileName, $data);
    echo str_replace('{{content}}', $viewContent, $layoutContent);
}

function renderLayout($layout = null)
{
    ob_start();
    if(!$layout){
        include_once( VIEWS_PATH . 'layouts/' . 'layout.php');
    }else{
        include_once( VIEWS_PATH . 'layouts/' . $layout . '.php');
    }
    return ob_get_clean();


}

function renderView($file, $data = null)
{
    ob_start();
    include_once( VIEWS_PATH . $file .'.php');
    return ob_get_clean();
}

?>