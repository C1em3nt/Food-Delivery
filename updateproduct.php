<?php
session_start();

include('db.php');

try {


    if (empty($_POST['price']) && empty($_POST['quantity'])) 
    {
        throw new Exception('Fields required!');
    }


    $id = $_POST['id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    if (!empty($_POST['price'])) 
    {
        $stmt = $conn->prepare("update product set price=:price where PID=:PID ");
        $stmt->execute(array('price' => $price, 'PID' => $id));
    }
    
    if (!empty($_POST['quantity'])) 
    {
        $stmt = $conn->prepare("update product set quantity=:quantity where PID=:PID ");
        $stmt->execute(array('quantity' => $quantity, 'PID' => $id));
    }


    echo <<<EOT
        <!DOCTYPE html>
        <html>
            <body>
                <script>
                alert("Edit success !!");
                window.location.replace("nav.php");
                </script>
            </body>
        </html>
    EOT;
    exit();
    
 
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