<?php
    // onderstaand bestand wordt ingeladen
    include('../core/header.php');
    include('../core/checklogin_admin.php');

    //TODO ophalen van signature!!!!
    include(INCLUDE_FILES."/admin/core/library/sigToSvg.php");

  $date = new DateTime();
  $curdate = $date->format('d-m-Y');
  $titleDate = $curdate;
  $dateForm = dateRewrite($curdate);
  $where_date = " AND check_date = CURRENT_DATE()";
  $where_filter = "";
  if(!empty($_GET['submit']) && $_GET['submit'] != ''){
    if(!empty($_GET['klas']) && $_GET['klas'] != ''){
      $klas = strip($_GET['klas']);
      $where_filter .= " AND AES_DECRYPT(std_klas,'{$_ENV['SALT']}') = '{$klas}'";
    }

    if(!empty($_GET['opleiding']) && $_GET['opleiding'] != ''){
      $opleiding = strip($_GET['opleiding']);
      $where_filter .= " AND AES_DECRYPT(std_opleiding,'{$_ENV['SALT']}') = '{$opleiding}'";
    }

    if(!empty($_GET['date']) && $_GET['date'] != ''){
      $dateForm = strip($_GET['date']);
      $where_date = " AND check_date = '{$dateForm}'";
      $titleDate = dateRewrite($dateForm);
    }

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
              <form action="index.php" method="get">
                <div class="row">
                  <div class="col">
                    <div class="form-group input-group input-group-outline is-filled">
                      <label class="form-label">Datum</label>
                      <input type="date" name="date" value="<?= $dateForm;?>" class="form-control">
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group input-group input-group-outline is-filled">
                      <label class="form-label">Klas</label>
                      <select name="klas" id="klas" class="form-control">
                        <option value="">-</option>
                        <?php
                        $optionqry = $con->prepare("SELECT DISTINCT(AES_DECRYPT(std_klas,'{$_ENV['SALT']}')) AS klas FROM student WHERE deleted = '0' ORDER BY klas ASC;");
                        if($optionqry === false) {
                           echo mysqli_error($con);
                        } else{
                            $optionqry->bind_result( $std_klas );
                            if($optionqry->execute()){
                                $optionqry->store_result();
                                while ($optionqry->fetch() ) { ?>
                                    <option value="<?= $std_klas;?>" <?= (isset($_GET['klas']) && $_GET['klas'] == $std_klas) ? 'selected' : '';?>><?= $std_klas;?></option>
                                     <?php 
                                }
                            }
                
                            $optionqry->close();
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group input-group input-group-outline is-filled">
                      <label class="form-label">Opleiding</label>
                      <select name="opleiding" id="opleiding" class="form-control">
                        <option value="">-</option>
                        <?php
                        $optionqry2 = $con->prepare("SELECT DISTINCT(AES_DECRYPT(std_opleiding,'{$_ENV['SALT']}')) AS opleiding FROM student WHERE deleted = '0' ORDER BY opleiding ASC;");
                        if($optionqry2 === false) {
                           echo mysqli_error($con);
                        } else{
                            $optionqry2->bind_result( $std_opleiding );
                            if($optionqry2->execute()){
                                $optionqry2->store_result();
                                while ($optionqry2->fetch() ) { ?>
                                    <option value="<?= $std_opleiding;?>" <?= (isset($_GET['opleiding']) && $_GET['opleiding'] == $std_opleiding) ? 'selected' : '';?>><?= $std_opleiding;?></option><?php 
                                }
                            }
                
                            $optionqry2->close();
                        }
                        ?>
                      </select>
                    </div>
                    
                  </div>
                  <div class="col">
                    <div class="form-group">
                        <button class="btn btn-info" type="submit" name="submit" value="Filteren">Filteren</button>
                    </div>
                    
                  </div>
                </div>
              </form>
            </div>
        </div>
        <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Overzicht studenten van <u><?= $titleDate;?></u></h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Student</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Check IN time</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Check IN handtekening</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Check OUT time</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Check OUT handtekening</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Klas</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opleiding</th>
                    </tr>
                  </thead>
                  <tbody>
<?php
        $liqry = $con->prepare("SELECT 
          AES_DECRYPT(std_nr,'{$_ENV['SALT']}') AS std_nr,
          AES_DECRYPT(std_voornaam,'{$_ENV['SALT']}'), 
          AES_DECRYPT(std_tussenvoegsel,'{$_ENV['SALT']}'),
          AES_DECRYPT(std_achternaam,'{$_ENV['SALT']}') AS achternaam,
          AES_DECRYPT(std_opleiding,'{$_ENV['SALT']}') AS opleiding,
          AES_DECRYPT(std_klas,'{$_ENV['SALT']}') AS klas
        FROM 
          student 
        WHERE deleted = '0' {$where_filter}
        ORDER BY achternaam;");


        if($liqry === false) {
           echo mysqli_error($con);
        } else{
            $liqry->bind_result( $std_nr,$std_voornaam,$std_tussenvoegsel,$std_achternaam,$std_opleiding,$std_klas );
            if($liqry->execute()){
                $liqry->store_result();

                while ($liqry->fetch() ) { 

                  list($check_time_qry_in) = mysqli_fetch_array($con->query("SELECT 
                  GROUP_CONCAT(DATE_FORMAT(check_time,'%d-%m-%Y | %H:%i'),'|',check_type)
                  FROM presentie WHERE AES_DECRYPT(presentie.check_number,'{$_ENV['SALT']}') = '{$std_nr}' AND check_type = 'in' {$where_date};"));

                  list($check_time_qry_out) = mysqli_fetch_array($con->query("SELECT 
                  GROUP_CONCAT(DATE_FORMAT(check_time,'%d-%m-%Y | %H:%i'),'|',check_type)
                  FROM presentie WHERE AES_DECRYPT(presentie.check_number,'{$_ENV['SALT']}') = '{$std_nr}' AND check_type = 'out' {$where_date};"));

                  list($check_signature_in) = mysqli_fetch_array($con->query("SELECT 
                  AES_DECRYPT(check_signature,'{$_ENV['SALT']}')
                  FROM presentie WHERE AES_DECRYPT(presentie.check_number,'{$_ENV['SALT']}') = '{$std_nr}' AND check_type = 'in' {$where_date} LIMIT 1;"));

                  list($check_signature_out) = mysqli_fetch_array($con->query("SELECT 
                  AES_DECRYPT(check_signature,'{$_ENV['SALT']}')
                  FROM presentie WHERE AES_DECRYPT(presentie.check_number,'{$_ENV['SALT']}') = '{$std_nr}' AND check_type = 'out' {$where_date} LIMIT 1;"));

                  // dd($check_signature_in)÷;

                  $rowClass = "";
                  $rowClassText = "";
                  $checkedIn = false;
                  $checkedOut = false;
                  if($check_time_qry_in != ''){
                    $check_time_in = explode(',',$check_time_qry_in);
                    if(!empty($check_time_in)){
                        $check_type = explode('|',$check_time_in[0]);
                        $check_in = $check_type;
                        $rowClass = "bg-gradient-warning";
                        $rowClassText = "text-white";
                        $checkedIn = true;
                    }
                  }else{
                    $rowClass = "bg-gradient-danger";
                    $rowClassText = "text-white";
                  }
                  
                  if($check_time_qry_out != ''){
                    $check_time_out = explode(',',$check_time_qry_out);
                    if(!empty($check_time_out)){
                        $check_type = explode('|',$check_time_out[0]);
                        $check_out = $check_type;
                        $rowClass = "bg-gradient-warning";
                        $rowClassText = "text-white";
                        $checkedOut = true;
                    }
                  }

                  if($checkedIn && $checkedOut){
                        $rowClass = "";
                        $rowClassText = "";
                  }
                  // try {
                  //   $sig = $check_signature;
                  //   // $sig = json_decode($sig); // can accept either JSON string or the native PHP decoded array.
                  //   $svg = new sigToSvg($sig, array('penWidth' => 5));
                  //   echo $svg->getImage();
                  // } catch (Exception $e) {
                  //   dd($e->getMessage());
                  // }
                  
                  ?>
                    <tr class="<?= $rowClass." ".$rowClassText;?>">
                        <td>
                            <div class="d-flex px-2 py-1">
                              <div class="d-flex flex-column justify-content-center">
                                  <h6 class="mb-0 text-sm <?= $rowClassText;?>"><a href="user.php?user=<?= $std_nr;?>"><?= $std_voornaam.' '.$std_achternaam;?></a></h6>
                                  <p class="text-xs text-secondary mb-0 <?= $rowClassText;?>"><?= $std_nr;?></p>
                              </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex px-2 py-1">
                              <div class="d-flex flex-column justify-content-center">
                                  <h6 class="mb-0 text-sm <?= $rowClassText;?>"><?= (!empty($check_in)) ? $check_in[1]: '';?></h6>
                                  <p class="text-xs text-secondary mb-0 <?= $rowClassText;?>"><?= (!empty($check_in)) ? $check_in[0] : '';?></p>
                              </div>
                            </div>
                        </td>
                        <td>
                            <p class="text-xs font-weight-bold mb-0">
                              <?php
                                  if(!empty($check_signature_in)){?>
                                    <div class="_signature">
                                      <span class="_show-signature badge badge-sm bg-gradient-info">Show Signature</span><br>
                                      <span class="_signature-item d-none">
                                        <?php
                                        // dd($check_signature_in);
                                        try {
                                          $sig = strip($check_signature_in);
                                          // $sig = json_decode($sig); // can accept either JSON string or the native PHP decoded array.
                                          $svg = new sigToSvg($sig);
                                          echo $svg->getImage();
                                        } catch (Exception $e) {
                                          dd($e->getMessage());
                                        }
                                        ?>
                                      </span>
                                    </div>
                                    <?php
                                  }?>
                            </p>
                        </td>
                        <td>
                            <div class="d-flex px-2 py-1">
                              <div class="d-flex flex-column justify-content-center">
                                  <h6 class="mb-0 text-sm <?= $rowClassText;?>"><?= (!empty($check_out)) ? $check_out[1]: '';?></h6>
                                  <p class="text-xs text-secondary mb-0 <?= $rowClassText;?>"><?= (!empty($check_out)) ? $check_out[0] : '';?></p>
                              </div>
                            </div>
                        </td>
                        <td>
                            <p class="text-xs font-weight-bold mb-0">
                              <?php
                                  if(!empty($check_signature_out)){?>
                                    <div class="_signature">
                                      <span class="_show-signature badge badge-sm bg-gradient-info">Show Signature</span><br>
                                      <span class="_signature-item d-none">
                                        <?php
                                        // dd($check_signature_in);
                                        try {
                                          $sig = strip($check_signature_out);
                                          // $sig = json_decode($sig); // can accept either JSON string or the native PHP decoded array.
                                          $svg = new sigToSvg($sig);
                                          echo $svg->getImage();
                                        } catch (Exception $e) {
                                          dd($e->getMessage());
                                        }?>
                                    </span>
                                  </div>
                                  <?php
                                  }?>
                            </p>
                        </td>
                        <td>
                            <p class="text-xs font-weight-bold mb-0"><?= $std_klas;?></p>
                        </td>
                        <td class="align-middle text-center text-sm">
                            <span class="badge badge-sm bg-gradient-success <?= $rowClassText;?>"><?= $std_opleiding;?></span>
                        </td>
                        
                    </tr>
                     <?php 
                     $check_in = [];
                     $check_out = [];
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
