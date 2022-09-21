<?php
    include('core/header.php');

    if (isset($_POST['submit']) && $_POST['submit'] != '') {
        //default user: test@test.nl
        //default password: test123
        $email = $con->real_escape_string($_POST['email']);

        $liqry = $con->prepare("SELECT admin_user_id,AES_DECRYPT(email,'{$_ENV['SALT']}') FROM admin_user WHERE AES_DECRYPT(email,'{$_ENV['SALT']}') = ? LIMIT 1;");
        if($liqry === false) {
           echo mysqli_error($con);
        } else{
            $liqry->bind_param('s',$email);
            $liqry->bind_result($adminId,$email);
            if($liqry->execute()){
                $liqry->store_result();
                $liqry->fetch();
                if($liqry->num_rows == '1'){

                    $token = sha1(mt_rand(1, 90000) . 'WEBSHOP2021-1wdv');

                    $url = BASEURL_CMS.'verify_password.php?token='.$token;
                    
                    $query1 = $con->prepare("UPDATE admin_user SET password_token = ? WHERE admin_user_id = ? LIMIT 1;");
                    if ($query1 === false) {
                        echo mysqli_error($con);
                    }
                    
                    $query1->bind_param('si',$token,$adminId);
                    if ($query1->execute() === false) {
                        echo mysqli_error($con);
                    } 
                    $query1->close();
                    
                    
                    echo "Er is een mail verstuud met een link voor het resetten van het wachtwoord";
                    $message =  "<a href='{$url}'>".$url."</a>";
                    echo $message;
                    // mail($email, 'Wachtwoord vergeten Presentie Check In', $message);
                    exit();
                } else {
                    echo "ERROR tijdens inloggen";
                }
            }
            $liqry->close();
        }
    }
?>

<main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1559925393-8be0ec4767c8?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1771&q=80');">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container my-auto">
            <div class="row">
                <div class="col-lg-4 col-md-8 col-12 mx-auto">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-info shadow-primary border-radius-lg py-3 pe-1">
                                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Forgot password</h4>
                                
                            </div>
                        </div>
                        <div class="card-body">
                        <form role="form" action="forgot_password.php" method="post" class="text-start">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                           
                            <div class="text-center">
                                <button type="submit" class="btn bg-gradient-info w-100 my-4 mb-2" name="submit" value="Request password">Request password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<form action="forgot_password.php" method="post">
    <input type="email" name="email" id="" placeholder="Email">
    <input type="submit" name="submit" value="Request password">
    <a href="index.php">Login</a>
</form>
<?php
    include('core/footer.php');
?>