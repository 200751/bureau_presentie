<?php
    include('core/header.php');

    if (isset($_POST['submit']) && $_POST['submit'] != '') {
        //default user: test@test.nl
        //default password: test123
        $token = $con->real_escape_string($_GET['token']);
        $password_1 = $con->real_escape_string($_POST['password_1']);
        $password_2 = $con->real_escape_string($_POST['password_2']);
        
        $liqry = $con->prepare("SELECT admin_user_id,AES_DECRYPT(email,'{$_ENV['SALT']}') FROM admin_user WHERE password_token = ? LIMIT 1;");
        if($liqry === false) {
           echo mysqli_error($con);
        } else{
            $liqry->bind_param('s',$token);
            $liqry->bind_result($adminId,$email);
            if($liqry->execute()){
                $liqry->store_result();
                $liqry->fetch();
                if($liqry->num_rows == '1' && $password_1 == $password_2){

                    $password = password_hash($password_1, PASSWORD_DEFAULT);
                    
                    $query1 = $con->prepare("UPDATE admin_user SET password = ?, password_token = '', password_changed = NOW() WHERE admin_user_id = ? LIMIT 1;");
                    if ($query1 === false) {
                        echo mysqli_error($con);
                    }
                    
                    $query1->bind_param('si',$password,$adminId);
                    if ($query1->execute() === false) {
                        echo mysqli_error($con);
                    } 
                    $query1->close();
                    
                    echo "Gelukt, u wordt doorgestuurd... <meta http-equiv=\"refresh\" content=\"2; URL=index.php\">";
                    exit();
                } else {
                    echo "ERROR tijdens verzenden. Komen de wachtwoorden overeen?";
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
                                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Verify new  password</h4>
                                
                            </div>
                        </div>
                        <div class="card-body">
                        <form role="form" action="verify_password.php?token=<?= $_GET['token'];?>" method="post" class="text-start">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password_1" class="form-control">
                            </div>
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Repeat Password</label>
                                <input type="password" name="password_2" class="form-control">
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
<?php
    include('core/footer.php');
?>