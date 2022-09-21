<?php
    
    // onderstaand bestand wordt ingeladen
    include('../core/header.php');
    include('../core/checklogin_admin.php');
?>

<h1>Gebruiker verwijderen</h1>

<?php
//prettyDump($_POST);
    if (isset($_POST['submit']) && $_POST['submit'] != '') {
        //default user: test@test.nl
        //default password: test123
        $uid = $con->real_escape_string($_POST['uid']);
        $query1 = $con->prepare("DELETE FROM admin_user WHERE admin_user_id = ? LIMIT 1;");
        if ($query1 === false) {
            echo mysqli_error($con);
        }
                    
        $query1->bind_param('i',$uid);
        if ($query1->execute() === false) {
            echo mysqli_error($con);
        } else {
            echo '<div style="border: 2px solid red">Gebruiker met admin_user_id '.$uid.' verwijderd!</div>';
        }
        $query1->close();
                    
    }
?>


<?php
    if (isset($_GET['uid']) && $_GET['uid'] != '') {

        ?>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">

        <h2 style="color: red">weet je zeker dat je deze gebruiker wilt verwijderen?</h2><?php

        $uid = $con->real_escape_string($_GET['uid']);

        $liqry = $con->prepare("SELECT admin_user_id,email FROM admin_user WHERE admin_user_id = ? LIMIT 1;");
        if($liqry === false) {
           echo mysqli_error($con);
        } else{
            $liqry->bind_param('i',$uid);
            $liqry->bind_result($adminId,$email);
            if($liqry->execute()){
                $liqry->store_result();
                $liqry->fetch();
                if($liqry->num_rows == '1'){
                    echo '$adminId: ' . $adminId . '<br>';
                    echo '<input type="hidden" name="uid" value="' . $adminId . '" />';
                      echo '$email: ' . $email . '<br>';
                }
            }
        }
        $liqry->close();

        ?>
        <br>
        <input type="submit" name="submit" value="Ja, verwijderen!">
        </form>
        <?php

    }
?>

<?php
    include('../core/footer.php');
?>