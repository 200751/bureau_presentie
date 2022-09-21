<?php

function handleSign($type){
    global $con;

    doMes();

    $result = false;
    foreach($_POST as $key => $val){
        $$key = $val;
    }

    // dd($output,true);

    $liqry = $con->prepare("INSERT INTO presentie (check_time,check_type,check_number,check_signature,check_date) VALUES (NOW(),'{$type}',AES_ENCRYPT(?,'{$_ENV['SALT']}'),AES_ENCRYPT(?,'{$_ENV['SALT']}'),NOW())");
    if($liqry === false) {
        echo mysqli_error($con);
    } else{
        $liqry->bind_param('ss',$std_nr,$output);
      
        if($liqry->execute()){
           $result = true;
        }
        echo mysqli_error($con);
    }
    $liqry->close();

    return $result;

}
