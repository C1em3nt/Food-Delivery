<?php
session_start();

include('db.php');

try{
    //money
    $oid = $_POST['oid'];
    $stmt = $conn->prepare("select * from orders where OID=:OID");
    $stmt->execute(array('OID' => $oid));
    if($stmt->rowCount() < 1){
        throw new Exception("Cancel Failed!");
    }
    $order = $stmt->fetch();
    if($order['status'] != "Not Finished"){
        throw new Exception("Cancel Failed!");
    }
    $stmt = $conn->prepare("select * from shop inner join users on owner=account where shopname=:shopname");
    $stmt->execute(array('shopname' => $order['shop']));
    $shop = $stmt->fetch();

    $stmt = $conn->prepare("update users set balance = balance - :tot_amount + :fee where account=:account");
    $stmt->execute(array('tot_amount' => $order['tot_amount'], 'fee' => $order['fee'], 'account' => $_SESSION['Account']));
    
    $stmt = $conn->prepare("update users set balance = balance + :tot_amount where account=:account");
    $stmt->execute(array('tot_amount' => $order['tot_amount'], 'account' => $order['buyer']));

    //stuff
    $stmt = $conn->prepare("select * from orderlist where OID=:OID");
    $stmt->execute(array('OID' => $oid));
    $product = $stmt->fetchall();
    foreach($product as $p){
        $stmt = $conn->prepare("update product set quantity = quantity + :o_quantity where PID=:PID");
        $stmt->execute(array('o_quantity' => $p['quantity'] ,'PID' => $p['PID']));
    }

    //transaction
    $stmt = $conn->prepare("insert into transaction (action, amount, time, UID, trader) values ('Payment', :amount, NOW(), :UID, :trader)");
    $stmt->execute(array('amount' => ($order['fee'] - $order['tot_amount']), 'UID' => $_SESSION['Account'], 'trader' => $order['buyer']));

    $stmt = $conn->prepare("insert into transaction (action, amount, time, UID, trader) values ('Receive', :amount, NOW(), :UID, :trader)");
    $stmt->execute(array('amount' => "+".$order['tot_amount'], 'UID' => $order['buyer'], 'trader' => $_SESSION['Shopname']));
    
    $stmt = $conn->prepare("update orders set status='Canceled', end_t=NOW() where OID=:OID");
    $stmt->execute(array('OID' => $oid));

    echo <<<EOT
    <!DOCTYPE html>
    <html>
        <body>
            <script>
            alert("Cancel success!");
            window.location.replace("nav.php");
            </script>
        </body>
    </html>
    EOT;

}
catch(Exception $e) 
{

    $msg = $e->getMessage();
    echo <<<EOT
        <!DOCTYPE html>
        <html>
            <body>
                <script>
                alert("Cancel faied!");
                window.location.replace("nav.php");
                </script>
            </body>
        </html>
    EOT;
}
?>