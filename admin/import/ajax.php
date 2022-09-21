<?php
include($_SERVER['DOCUMENT_ROOT'].'/presentie-glu/core/db_connect.php');

if(!empty($_POST)){
	if(isset($_POST['id']) && $_POST['id'] != '') {
		$key = mes($_POST['id']);
        
	}

	switch ($_GET['type']) {
		case 'del':
			$con->query("UPDATE student SET deleted = '1' WHERE student_id = '".$key."' LIMIT 1;");
        	echo 'true';
			break;
		
		default:
			# code...
			break;
	}
}

$con->close();
