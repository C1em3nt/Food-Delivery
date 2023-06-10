<?php
session_start();

include('db.php');

try {


    if (empty($_POST['longitude']))
    {
        throw new Exception('Longitude fields required!');
    }

    if (empty($_POST['latitude']))
    {
        throw new Exception('Latitude fields required!');
    }

    $longitude = floatval($_POST['longitude']);
    $latitude = floatval($_POST['latitude']);

    $stmt = $conn->prepare("update users set longitude=$longitude, latitude=$latitude, location=ST_GeomFromText('POINT($longitude $latitude)') where account=:account");
    $stmt->execute(array('account' => $_SESSION['Account']));

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