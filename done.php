<?php
session_start();

include('db.php');

try{
    
    $oid = $_POST['oid'];
    $stmt = $conn->prepare("select * from orders where OID=:OID");
    $stmt->execute(array('OID' => $oid));
    if($stmt->rowCount() < 1){
        throw new Exception("Order does not exist!");
    }
    $order = $stmt->fetch();
    if($order['status'] != "Not Finished"){
        throw new Exception("Done Failed!");
    }

    $stmt = $conn->prepare("update orders set status='Finished', end_t=NOW() where OID=:OID");
    $stmt->execute(array('OID' => $oid));

    echo <<<EOT
    <!DOCTYPE html>
    <html>
        <body>
            <script>
            alert("Order doned!");
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
                alert("$msg");
                window.location.replace("nav.php");
                </script>
            </body>
        </html>
    EOT;
}
?>