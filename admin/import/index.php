<?php
  // onderstaand bestand wordt ingeladen
  include('../core/header.php');
  include('../core/checklogin_admin.php');
  use Shuchkin\SimpleXLSX;

  if(isset($_POST['submit']) && $_POST['submit'] != ''){
    

    if ( $xlsx = SimpleXLSX::parse($_FILES['file']['tmp_name']) ) {
        
        $successarray = [];
        $errorarray = [];
        
        foreach($xlsx->rows() as $key => $val){
            // dd(count($val),true);
            if(count($val) == 7){
                if($key > 1){
                    $voornaam = mes($val[1]);
                    $tussenvoegsel = mes($val[2]);
                    $achternaam = mes($val[3]);
                    $std_nr = mes($val[4]);
                    $klas = mes($val[5]);
                    $opleiding = mes($val[6]);
                    if (str_contains(strtolower($opleiding), 'webdeveloper')) {
                        $opleiding = 'webdeveloper';
                    }else if (str_contains(strtolower($opleiding), 'webdesign')) {
                        $opleiding = 'webdesign';
                    }else if (str_contains(strtolower($opleiding), 'grafisch')) {
                      $opleiding = 'grafisch vormgeven';
                    }else if (str_contains(strtolower($opleiding), 'animatie')) {
                      $opleiding = 'animatie';
                    }else if (str_contains(strtolower($opleiding), 'crossmedia')) {
                      $opleiding = 'crossmedia';
                    }
                    
                    $liqry = $con->prepare("INSERT INTO student (std_nr,std_voornaam,std_tussenvoegsel,std_achternaam,std_opleiding,std_klas) VALUES (AES_ENCRYPT(?,'{$_ENV["SALT"]}'),AES_ENCRYPT(?,'{$_ENV["SALT"]}'),AES_ENCRYPT(?,'{$_ENV["SALT"]}'),AES_ENCRYPT(?,'{$_ENV["SALT"]}'),AES_ENCRYPT(?,'{$_ENV["SALT"]}'),AES_ENCRYPT(?,'{$_ENV["SALT"]}'))");
                    if($liqry === false) {
                        echo mysqli_error($con);
                    } else{
                        $liqry->bind_param('ssssss',$std_nr,$voornaam,$tussenvoegsel,$achternaam,$opleiding,$klas);
                        if($liqry->execute()){
                            $successarray[] = "Gelukt voor studentnr:".$std_nr."<br>";
                        }else{
                            $liqry2 = $con->prepare("UPDATE student SET deleted = '0', std_klas = AES_ENCRYPT(?,'{$_ENV["SALT"]}') WHERE AES_DECRYPT(std_nr,'{$_ENV["SALT"]}') = ? LIMIT 1;");
                            if($liqry2 === false) {
                                echo mysqli_error($con);
                            } else{
                                $liqry2->bind_param('ss',$klas,$std_nr);
                                if($liqry2->execute()){
                                    $successarray[] ="Gelukt voor studentnr:".$std_nr."<br>";;
                                }else{
                                  $errorarray[] = "Is al ingevoerd voor std_nr ".$std_nr;
                                }
                            }
                            $liqry2->close();
                            
                        }
                    }
                    $liqry->close();
                }
            }else{
                $errorarray[0] = "Excel komt niet overeen met voorbeeld ";
            }
      }
    } else {
        echo SimpleXLSX::parseError();
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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Import van TP</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Import van TP</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
              <a href="index.php" class="btn">Import</a>
              <a href="overzicht_import.php" class="btn btn-info">Toon alle users</a>
              <a href="overzicht_klassen.php" class="btn btn-info">Toon alle klassen</a>
            </div>
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
            <div class="col-12">
              <div class="alert alert-warning">
                Download een export van Trajectplanner met de volgende velden<br>
                <img src="<?= $_ENV['BASEURL_CMS'];?>assets/img/TP-import.png" class="img-fluid" alt="" />
              </div>
            </div>
        </div>
      <div class="row">
        <div class="col-12">
          <div class="card card-plain">
            <div class="card-body">
              <form role="form" enctype="multipart/form-data" method="post" action="index.php">
                <div class="input-group input-group-outline mb-3">
                  <!-- <label class="form-label">CSV from TP</label> -->
                  <input type="file" name="file" class="form-control">
                </div>
                <div class="text-center">
                  <button type="submit" name="submit" value="Opslaan" class="btn btn-lg bg-gradient-info btn-lg w-100 mt-4 mb-0">Opslaan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php
        // $liqry = $con->prepare("SELECT product_id, product.name AS productName, price, category.name AS catName FROM product INNER JOIN category ON product.category_id = category.category_id");
        // if($liqry === false) {
        //    echo mysqli_error($con);
        // } else{
        //     $liqry->bind_result($product_id, $name, $price, $category );
        //     if($liqry->execute()){
        //         $liqry->store_result();
        //         // while($liqry->fetch()) {
        //         //     echo 'admin id :' . $adminId . " - ";
        //         //     echo 'email :' . $email . " - ";
        //         //     echo '<a href="edit_user.php?uid='.$adminId.'">edit</a><br>';
        //         // }

        //         // table>tr*1>td*4
        //         echo '<table border=1>
        //                 <tr>
        //                     <td>product ID</td>
        //                     <td>product naam</td>
        //                     <td>product prijs</td>
        //                     <td>categorie</td>
        //                     <td>edit</td>
        //                     <td>delete</td>
        //                 </tr>';
        //         while ($liqry->fetch() ) { ?>
        <!-- //             <tr>
        //                 <td><?php echo $product_id; ?></td>
        //                 <td><?php echo $name; ?></td>
        //                 <td><?php echo $price; ?></td>
        //                 <td><?php echo $category; ?></td>
        //                 <td><a href="edit_product.php?uid=<?php echo $product_id; ?>">edit</a></td>
        //                 <td><a href="delete_product.php?uid=<?php echo $product_id; ?>">delete</a></td>
        //             </tr> -->
                     <?php 
        //         }
        //         echo '</table>';
        //     }

        //     $liqry->close();
        // }

?>
        </div>      
    </div>
</main>
<?php
    include('../core/footer.php');
?>
