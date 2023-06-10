<?php
session_start();
include('db.php');

try {

    if(!empty($_POST['minprice'])) {
        if(!ctype_digit($_POST['minprice'])) throw new Exception('Price should be a positive number.');
    }
    if(!empty($_POST['maxprice'])) {
        if(!ctype_digit($_POST['maxprice'])) throw new Exception('Price should be a positive number.');
    }
    //if(!ctype_alnum($_POST['category'])) throw new Exception('Category may only consist of characters.');
    //if(!ctype_alnum($_POST['shopname'])) throw new Exception('Shop name may only consist of characters.');
    //if(!ctype_alnum($_POST['meal'])) throw new Exception('Meal name may only consist of characters.');

    $_SESSION['Sname'] = $_POST['shopname'];
    $_SESSION['Dist'] = $_POST['distance'];
    $_SESSION['Minp'] = $_POST['minprice'];
    $_SESSION['Maxp'] = $_POST['maxprice'];
    $_SESSION['Mname'] = $_POST['meal'];
    $_SESSION['Category'] = $_POST['category'];
    $_SESSION['Search'] = "1";
    
    $stmt = $conn->prepare("select * from shop where ST_Distance_Sphere(s_location, :location) < 15000 ");
    $stmt->execute(array('location' => $_SESSION['Location']));
    if($stmt->rowCount() > 0)
    {
        $row = $stmt->fetchall();
        foreach($row as $slist){
            $stmt = $conn->prepare("update shop set distance='near' where SID=:SID");
            $stmt->execute(array('SID' => $slist['SID']));
        }

    }

    $stmt = $conn->prepare("select * from shop where ST_Distance_Sphere(s_location, :location) Between 15000 and 25000 ");
    $stmt->execute(array('location' => $_SESSION['Location']));
    if($stmt->rowCount() > 0)
    {
        $row = $stmt->fetchall();
        foreach($row as $slist){
            $stmt = $conn->prepare("update shop set distance='medium' where SID=:SID");
            $stmt->execute(array('SID' => $slist['SID']));
        }
    }

    $stmt = $conn->prepare("select * from shop where ST_Distance_Sphere(s_location, :location) > 25000 ");
    $stmt->execute(array('location' => $_SESSION['Location']));

    if($stmt->rowCount() > 0)
    {
        $row = $stmt->fetchall();
        foreach($row as $slist){
            $stmt = $conn->prepare("update shop set distance='far' where SID=:SID");
            $stmt->execute(array('SID' => $slist['SID']));
        }
    }

    header("Location: nav.php");
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