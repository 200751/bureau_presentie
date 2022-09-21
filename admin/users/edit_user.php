<?php
    
    // onderstaand bestand wordt ingeladen
    include('../core/header.php');
    include('../core/checklogin_admin.php');


//prettyDump($_POST);
    if (isset($_POST['submit']) && $_POST['submit'] != '') {
        //default user: test@test.nl
        //default password: test123
        $uid = $con->real_escape_string($_GET['uid']);
        $email = $con->real_escape_string($_POST['email']);
        $query1 = $con->prepare("UPDATE admin_user SET email = AES_ENCRYPT(?,'{$_ENV['SALT']}') WHERE admin_user_id = ? LIMIT 1;");
        if ($query1 === false) {
            $errorarray[] = mysqli_error($con);
        }
                    
        $query1->bind_param('si',$email,$uid);
        if ($query1->execute() === false) {
            $errorarray[] = mysqli_error($con);
        } else {
            $successarray[] = "Gebruiker aangepast";
        }
        $query1->close();
                    
    }
?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
     <!-- Navbar -->
     <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Presentie CMS</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Wijzig Admin User</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Terug naar overzicht</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container py-4">
        <div class="row">
            <div class="col-12"><a href="index.php" class="btn btn-info">Toon alle users</a></div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php
                    if(!empty($successarray)){
                        ?>
                        <div class="alert alert-success">
                        <?php
                        foreach($successarray as $key => $val){
                            echo $val."<br>";
                        }
                        ?>
                        </div>
                        <?php
                    }
                    if(!empty($errorarray)){
                        ?>
                        <div class="alert alert-danger">
                        <?php
                        foreach($errorarray as $key => $val){
                            echo $val."<br>";
                        }
                        ?>
                        </div>
                        <?php
                    }?>
            </div>
        </div>
      
    </div>
        </div>      
    </div>
    
<?php
    if (isset($_GET['uid']) && $_GET['uid'] != '') {
        $uid = $con->real_escape_string($_GET['uid']);

        $liqry = $con->prepare("SELECT admin_user_id,AES_DECRYPT(email,'{$_ENV['SALT']}') FROM admin_user WHERE admin_user_id = ? LIMIT 1;");
        if($liqry === false) {
           echo mysqli_error($con);
        } else{
            $liqry->bind_param('i',$uid);
            $liqry->bind_result($adminId,$email);
            if($liqry->execute()){
                $liqry->store_result();
                $liqry->fetch();
                if($liqry->num_rows == '1'){?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-plain">
                                <div class="card-body">
                                <form role="form" enctype="multipart/form-data" method="post" action="edit_user.php?uid=<?=$adminId;?>">
                                    <div class="input-group input-group-outline mb-3">
                                        <!-- <label class="form-label">CSV from TP</label> -->
                                        <input type="text" name="email" class="form-control" value="<?= $email;?>">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" name="submit" value="Opslaan" class="btn btn-lg bg-gradient-info btn-lg w-100 mt-4 mb-0">Opslaan</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                <?php }
            }
        }
        $liqry->close();

    }
?>


<?php
    include('../core/footer.php');
?>