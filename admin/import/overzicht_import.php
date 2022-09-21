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
            <a href="index.php" class="btn btn-info">Import</a>
            <a href="overzicht_import.php" class="btn">Toon alle users</a>
            <a href="overzicht_klassen.php" class="btn btn-info">Toon alle klassen</a>
          </div>
        </div>
        <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Overzicht studenten</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Student</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Klas</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opleiding</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ACTIES</th>
                    </tr>
                  </thead>
                  <tbody>
<?php
        $liqry = $con->prepare("SELECT student_id,AES_DECRYPT(std_nr,'{$_ENV['SALT']}'), AES_DECRYPT(std_voornaam,'{$_ENV['SALT']}'), AES_DECRYPT(std_tussenvoegsel,'{$_ENV['SALT']}'), AES_DECRYPT(std_achternaam,'{$_ENV['SALT']}') AS achternaam, AES_DECRYPT(std_opleiding,'{$_ENV['SALT']}'), AES_DECRYPT(std_klas,'{$_ENV['SALT']}') FROM student WHERE deleted = '0' ORDER BY achternaam");
        if($liqry === false) {
           echo mysqli_error($con);
        } else{
            $liqry->bind_result($std_id,$std_nr,$std_voornaam,$std_tussenvoegsel,$std_achternaam,$std_opleiding,$std_klas );
            if($liqry->execute()){
                $liqry->store_result();
                // while($liqry->fetch()) {
                //     echo 'admin id :' . $adminId . " - ";
                //     echo 'email :' . $email . " - ";
                //     echo '<a href="edit_user.php?uid='.$adminId.'">edit</a><br>';
                // }

                // table>tr*1>td*4
                while ($liqry->fetch() ) { ?>
                    <tr id="item_<?= $std_id;?>">
                        <td>
                            <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm"><?= $std_voornaam.' '.$std_achternaam;?></h6>
                                <p class="text-xs text-secondary mb-0"><?= $std_nr;?></p>
                            </div>
                            </div>
                        </td>
                        <td>
                            <p class="text-xs font-weight-bold mb-0"><?= $std_klas;?></p>
                        </td>
                        <td class="align-middle text-center text-sm">
                            <span class="badge badge-sm bg-gradient-success"><?= $std_opleiding;?></span>
                        </td>

                        <td>
                            <a class="btn btn-danger _delrow" lang="<?= $std_id;?>" href="#" >Delete</ad>
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