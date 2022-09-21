<?php
    include('core/header.php');
    // dd($_SERVER['REMOTE_ADDR']);
    if(!isAllowed($_SERVER['REMOTE_ADDR'])){
        exit("Helaas mag je hier niet bij vanaf jouw locatie!");
    }

    $view = '';
    if(!empty($_GET['view'])){
        $view = mes($_GET['view']);
        $view = str_replace("/","",$view);
    }

    switch($view){
        case '':
            $page = "home";
            break;
        case 'sign-in':
            $signtype = "in";
            $page = "sign.form";
            break;
        case 'sign-out':
            $signtype = "out";
            $page = "sign.form";
            break;
        default:
            $page = "home";
            break;
    }

    require_once("view/{$page}.php");
    

    include('core/footer.php');
?>