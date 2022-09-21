<?php
    
    // onderstaand bestand wordt ingeladen
    include('../core/header.php');
    include('../core/checklogin_admin.php');

    $successarray = [];
    $errorarray = [];

    if (isset($_POST["submit"]) && $_POST["submit"] != '') {
      $klas = mes($_POST['klas']);
      
      $liqry = $con->prepare("UPDATE student SET deleted = '1' WHERE AES_DECRYPT(std_klas,'{$_ENV["SALT"]}') = ?;");
      if($liqry === false) {
          echo mysqli_error($con);
      } else{
          $liqry->bind_param('s',$klas);
          if($liqry->execute()){
              $successarray[] = "Gelukt voor klas:".$klas."<br>";
          }else{
              $errorarray[] = "Is al ingevoerd voor klas ".$klas;
          }
      }
      $liqry->close();
    }
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
        <div class="row">
          <div class="col-12">
            <a href="index.php" class="btn btn-info">Import</a>
            <a href="overzicht_import.php" class="btn btn-info">Toon alle users</a>
            <a href="overzicht_klassen.php" class="btn">Toon alle klassen</a>
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
                <table class="table align-items-center b-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Klas</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ACTIES</th>
                    </tr>
                  </thead>
                  <tbody>
<?php
        $liqry = $con->prepare("SELECT AES_DECRYPT(std_klas,'{$_ENV['SALT']}') AS std_klas FROM student WHERE deleted = '0' GROUP BY std_klas");
        if($liqry === false) {
           echo mysqli_error($con);
        } else{
            $liqry->bind_result($std_klas );
            if($liqry->execute()){
                $liqry->store_result();
                // while($liqry->fetch()) {
                //     echo 'admin id :' . $adminId . " - ";
                //     echo 'email :' . $email . " - ";
                //     echo '<a href="edit_user.php?uid='.$adminId.'">edit</a><br>';
                // }

                // table>tr*1>td*4
                while ($liqry->fetch() ) { ?>
                    <tr id="item_<?= $std_klas;?>">
                        <td>
                            <p class="text-xs font-weight-bold mb-0"><?= $std_klas;?></p>
                        </td>
                        <td>
                          <form action="overzicht_klassen.php" method="post">
                            <input type="hidden" name="klas" value="<?= $std_klas;?>"/>
                            <input type="submit" name="submit" class="btn btn-danger" value="Delete" />
                          </form>
                            
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