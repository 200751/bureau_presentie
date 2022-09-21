<?php
    
    // onderstaand bestand wordt ingeladen
    include('../core/header.php');
    include('../core/checklogin_admin.php');
?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
     <!-- Navbar -->
     <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Presentie CMS</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Admin users</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Admin users</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-6"><a href="index.php" class="btn btn-info">Terug naar overzicht</a></div>
            <div class="col-6"><a href="add_user.php" class="btn btn-info">Nieuwe Admin toevoegen</a></div>
        </div>
        <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Overzicht admin users</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Admin ID</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Admin user</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acties</th>
                    </tr>
                  </thead>
                  <tbody>
<?php
        $liqry = $con->prepare("SELECT admin_user_id, AES_DECRYPT(email,'{$_ENV['SALT']}') FROM admin_user ORDER BY admin_user_id;");
        if($liqry === false) {
           echo mysqli_error($con);
        } else{
            $liqry->bind_result($admin_user_id, $email );
            if($liqry->execute()){
                $liqry->store_result();
                while ($liqry->fetch() ) { ?>
                    <tr>
                        <td>
                            <p class="text-xs font-weight-bold mb-0"><?= $admin_user_id;?></p>
                        </td>
                        <td>
                            <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm"><?= $email;?></h6>
                            </div>
                            </div>
                        </td>
                        
                        <td class="align-middle text-center text-sm">
                            <a href="<?= BASEURL_CMS;?>users/edit_user.php?uid=<?= $admin_user_id;?>" style="text-decoration:underline;">EDIT</a>
                        </td>
                        
                    </tr>
                     <?php 
                }
            }
            $liqry->close();
        }

    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>      
    </div>

</main>
<?php
    include('../core/footer.php');
?>
