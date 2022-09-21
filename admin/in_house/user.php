<?php
    // onderstaand bestand wordt ingeladen
    include('../core/header.php');
    include('../core/checklogin_admin.php');
    include(INCLUDE_FILES."/admin/core/library/Calendar.php");

    $user_nr = 0;
    if(isset($_GET['user']) && $_GET['user'] != ''){
        $user_nr = $_GET['user'];
    }
    $calendar = new Calendar(date('Y-m-d'),$user_nr);
    if(isset($_GET['date']) && $_GET['date'] != ''){
        $calendar = new Calendar(date($_GET['date']),$user_nr);
    }
   
    // $calendar->add_event('Birthday', '2021-02-03', 1, 'green');
    // $calendar->add_event('Doctors', '2021-02-04', 1, 'red');
    // $calendar->add_event('Holiday', '2021-02-16', 7);

   

    
    // $calendar = new Calendar(date('Y-m-d'));
?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
     <!-- Navbar -->
     <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Presentie CMS</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Presentie</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Presentie</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <a href="index.php" class="btn btn-info">Overzicht users</a>
                <a href="user.php?user=<?= $user_nr;?>&date=<?= date('Y-m-d');?>" class="btn btn-warning">Vandaag</a>
               
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?= $calendar;?>
            </div>
        </div>
    </div>
</main>

<?php
    include('../core/footer.php');
?>
