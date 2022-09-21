<?php
    include($_SERVER['DOCUMENT_ROOT'].'/bureau/presentie-glu-main/core/db_connect.php');

    //admin/index.php
    $showSideBar = true;

    if (str_contains($_SERVER['PHP_SELF'], 'admin/index.php') || str_contains($_SERVER['PHP_SELF'], 'admin/verify_password.php') || str_contains($_SERVER['PHP_SELF'], 'admin/forgot_password.php')) {
        $showSideBar = false;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="<?= BASEURL_CMS;?>assets/css/material-dashboard.css?v=3.0.2" rel="stylesheet" />
    <link id="pagestyle" href="<?= BASEURL_CMS;?>assets/css/calendar.css?v=3.0.2" rel="stylesheet" />
    <style>
        ._show-signature{
            cursor:pointer;
        }
    </style>
    <title>Admin Panel - Presentie GLU</title>
</head>
<body class="<?= ($showSideBar) ? 'g-sidenav-show' : '';?>  bg-gray-200">
<?php if($showSideBar){?>
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
        <div class="sidenav-header text-white">
            <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="<?= BASEURL_CMS;?>">
                <i class="material-icons text-white">fact_check</i>
                <span class="ms-1 font-weight-bold text-white">Presentie CMS</span>
            </a>
        </div>
        <hr class="horizontal light mt-0 mb-2">
        <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white <?= (str_contains($_SERVER['PHP_SELF'], 'admin/index_loggedin.php')) ? 'active bg-gradient-info': '';?> " href="<?= BASEURL_CMS;?>index_loggedin.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= (str_contains($_SERVER['PHP_SELF'], 'in_house/')) ? 'active bg-gradient-info': '';?>" href="<?= BASEURL_CMS;?>in_house/index.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">verified_user</i>
                        </div>
                        <span class="nav-link-text ms-1">Presentie</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= (str_contains($_SERVER['PHP_SELF'], 'import/')) ? 'active bg-gradient-info': '';?>" href="<?= BASEURL_CMS;?>import/index.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">publish</i>
                        </div>
                        <span class="nav-link-text ms-1">Import from TP</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= (str_contains($_SERVER['PHP_SELF'], 'users/')) ? 'active bg-gradient-info': '';?>" href="<?= BASEURL_CMS;?>users/index.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">people</i>
                        </div>
                        <span class="nav-link-text ms-1">Admin users</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
<?php }?>