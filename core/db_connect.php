<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT']."/bureau/presentie-glu-main/vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']."/bureau/presentie-glu-main/");
$dotenv->load();

$dbhost = $_ENV["DBHOST"];
$dbuser = $_ENV["DBUSER"];
$dbpass = $_ENV["DBPASS"];
$dbname = $_ENV["DBNAME"];

$con = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($con -> connect_errno) {
    echo "Failed to connect to MySQL: " . $con -> connect_error;
    exit();
}

define("BASEURL",$_ENV["BASEURL"]);
define("BASEURL_CMS",$_ENV["BASEURL_CMS"]);
define("INCLUDE_FILES",$_SERVER['DOCUMENT_ROOT']."/bureau/presentie-glu-main");


function dd ( $var, $exit=false ) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    if($exit){
        exit();
    }
}

function mes($value){
    global $con;
    return $con->real_escape_string($value);
}

function doMes(){
    global $con;

    foreach($_POST as $key => $val){
        $_POST[$key] = $con->real_escape_string($_POST[$key]);
    }
}

function strip($input){
    return stripslashes($input);
}

function dateRewrite($date){
    $date = explode("-",$date);
    return $date[2]."-".$date[1]."-".$date[0];
}

function isAllowed($ip){
    $whitelist = ['127.0.0.1','::1','192.87.140.150','192.87.140.151','192.87.140.152','192.87.140.153', '10.52.5.*', '172.16.31.*', '10.52.12.*'];

    //$whitelist = ['111.111.111.111', '112.112.112.112', '10.52.12.*', '172.16.31.*'];

    // If the ip is matched, return true
    if(in_array($ip, $whitelist)) {
        return true;
    }

    foreach($whitelist as $i){
        $wildcardPos = strpos($i, "*");

        // Check if the ip has a wildcard
        if($wildcardPos !== false && substr($ip, 0, $wildcardPos) . "*" == $i) {
            return true;
        }
    }

    return false;
}