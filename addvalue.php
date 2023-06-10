<?php
session_start();

include('db.php');

try {


    if (empty($_POST['money'])) 
    {
        throw new Exception('Add Failed! Value field required!');
    }

    $value = intval($_POST['money']);
    if(!ctype_digit($_POST['money'])) throw new Exception('Add Failed! Value should be a positive number.');
    
    $stmt = $conn->prepare("update users set balance = balance + :value where account=:account");
    $stmt->execute(array('value' => $value, 'account' => $_SESSION['Account']));

    $stmt = $conn->prepare("insert into transaction (action, amount, time, UID, trader) values ('Recharge', :amount, NOW(), :UID, :trader)");
    $stmt->execute(array('amount' => "+".$value, 'UID' => $_SESSION['Account'], 'trader' => $_SESSION['Account']));


    echo <<<EOT
    <!DOCTYPE html>
    <html>
        <body>
            <script>
            alert("Add success !!");
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