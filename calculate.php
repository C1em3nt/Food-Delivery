<?php
session_start();
include('db.php');

try {
    $_SESSION['Ordertype'] = $_POST['ordertype'];
    $_SESSION['Caltotal'] = 0;
    $_SESSION['Fee'] = 0;
    $_SESSION['Count']= 0;
    $_SESSION['O_shopname'] = $_POST['o_shopname'];
    $pid = $_POST['calpid'];
    $length = count($pid);
    for($i=0; $i < $length; $i++)
    {
        $j = $_SESSION['Count'];
        if(!ctype_digit($_POST['calnum'][$i])) throw new Exception('Order quantity should be positive numbers.');
        if($_POST['calnum'][$i] > 0) {
            $_SESSION['Calpid'][$j] = $_POST['calpid'][$i];
            $_SESSION['Calname'][$j] = $_POST['calname'][$i];
            $_SESSION['Calprice'][$j] = $_POST['calprice'][$i];
            $_SESSION['Calnum'][$j] = $_POST['calnum'][$i];

            $_SESSION['Caltotal'] += $_SESSION['Calprice'][$j] * $_SESSION['Calnum'][$j];
            $_SESSION['Count']++;
        }
    }
    $stmt = $conn->prepare("select ST_Distance_Sphere(s_location, :location) as dist from shop where shopname=:shopname");
    $stmt->execute(array('location' => $_SESSION['Location'], 'shopname' => $_POST['o_shopname']));
    $row = $stmt->fetch();
    if($_POST['ordertype'] == 'Delivery'){
        if(round($row['dist']/100) < 10){
            $_SESSION['Fee'] = 10;
        }
        else{
            $_SESSION['Fee'] =  round($row['dist']/100);
        }
    }

    $_SESSION['Totalamount'] = $_SESSION['Caltotal'] + $_SESSION['Fee'];
    header("Location: orderpage.php");
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