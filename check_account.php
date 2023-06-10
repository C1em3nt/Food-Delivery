<?php
include('db.php');

try {
    if(!isset($_REQUEST['account']) || empty($_REQUEST['account'])){
        echo 'FAILED';
        exit();
    }
    
    $account = $_REQUEST['account'];
    
    $stmt = $conn->prepare("select account from users where account=:account");
    $stmt->execute(array('account' => $account));
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