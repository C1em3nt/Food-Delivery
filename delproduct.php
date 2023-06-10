<?php
session_start();

include('db.php');


$id = $_POST['id'];
$stmt = $conn->prepare("delete from product where PID=:PID");
$stmt->execute(array('PID' => $id));

echo <<<EOT
    <!DOCTYPE html>
    <html>
        <body>
            <script>
            alert("Delete success !!");
            window.location.replace("nav.php");
            </script>
        </body>
    </html>
EOT;
exit();

?>