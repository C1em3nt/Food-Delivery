<?php
include('db.php');

try {
    if(!isset($_REQUEST['sname']) || empty($_REQUEST['sname'])){
        echo 'FAILED';
        exit();
    }
    
    $sname = $_REQUEST['sname'];

    $stmt = $conn->prepare("select shopname from shop where shopname=:shopname");
    $stmt->execute(array('shopname' => $sname));
    if($stmt->rowCount()==0)
    {
        echo 'YES';
    }
    else
    {
        echo 'NO';
    }
}
catch(Exception $e){
    echo 'FAILED';
}

?>