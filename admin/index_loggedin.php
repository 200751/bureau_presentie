<?php
    include('core/header.php');
    include('core/checklogin_admin.php');

    list($check__out) = mysqli_fetch_array($con->query("SELECT COUNT(presentie_id) FROM `presentie` WHERE check_date=CURRENT_DATE() AND check_type='out'"));
    list($check__in) = mysqli_fetch_array($con->query("SELECT COUNT(presentie_id) FROM `presentie` WHERE check_date=CURRENT_DATE() AND check_type='in'"));
    $pending=($check__in-$check__out);

    list($check_yesterday_out) = mysqli_fetch_array($con->query("SELECT COUNT(presentie_id) FROM `presentie` WHERE check_date=CURRENT_DATE() + INTERVAL - 1 DAY AND check_type='out';"));
    list($check_yesterday_in) = mysqli_fetch_array($con->query("SELECT COUNT(presentie_id) FROM `presentie` WHERE check_date=CURRENT_DATE() + INTERVAL - 1 DAY AND check_type='in';"));
    if ($check_yesterday_in>0 && $check__in>0){
        $checkin_comp=($check__in/$check_yesterday_in*100);
        if($checkin_comp>100){
            $check_in_color='success';
            $checkin_comp="+". ($checkin_comp-100) . "%";
        }else if($checkin_comp<100){
            $check_in_color='warning';
            $checkin_comp=($checkin_comp-100) . "%";
    
        }else if($checkin_comp==100){
            $check_in_color='info';
            $checkin_comp="equal";
        }
    }else{
        //no results yesterday
        //cannot compare to 0
        $check_in_color='danger';
        $checkin_comp='';
    }

    if ($check_yesterday_out>0 && $check__out>0){
        $checkout_comp=($check__out/$check_yesterday_out*100);
        if($checkout_comp>100){
            $check_out_color='success';
            $checkout_comp="+". ($checkout_comp-100) . "%";
        }else if($checkin_comp<100){
            $check_out_color='warning';
            $checkout_comp=($checkout_comp-100) . "%";
    
        }else if($checkin_comp==100){
            $check_out_color='info';
            $checkout_comp="equal";
        }
    }else{
        //no results yesterday
        //cannot compare to 0
        $check_out_color='danger';
        $checkout_comp='';
    }

?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Presentie CMS</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Dashboard</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="row">
            <h2 class="mb-5">Presentie status</h2>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-success shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">login</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Today Check INS</p>
                            <h4 class="mb-0"><?php echo $check__in; ?></h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0"><span class="text-<?php echo $check_in_color; ?> text-sm font-weight-bolder"><?php echo $checkin_comp; ?> 
                    </span>
                    <?php
                    if ($checkout_comp==''){
                        echo 'no results yesterday';
                    }else{
                        echo 'compared to yesterday';
                    }
                    ?>
                </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-info shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">logout</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Today Check OUTS</p>
                            <h4 class="mb-0"><?php echo $check__out; ?></h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0"><span class="text-<?php echo $check_out_color; ?> text-sm font-weight-bolder">
                        <?php echo $checkout_comp; ?> 
                    </span>
                    <?php
                    if ($checkout_comp==''){
                        echo 'no results yesterday';
                    }else{
                        echo 'compared to yesterday';
                    }
                    ?>
                    </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-warning shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">laptop</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Today Pending Check OUTS</p>
                            <h4 class="mb-0"><?php echo $pending; ?></h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0"><span class="text-success text-sm font-weight-bolder"></span>people that are yet to be checked out today</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
    include('core/footer.php');
?>