<?php
session_start();
$_SESSION['Authenticated'] = false;

include('db.php');

try {
    
    if (empty($_POST['sname'])) 
    {
        throw new Exception('Shop name field required!');
    }
    if (empty($_POST['category'])) 
    {
        throw new Exception('Category field required!');
    }
    if (empty($_POST['latitude'])) 
    {
        throw new Exception('latitude field required!');
    }
    if (empty($_POST['longitude'])) 
    {
        throw new Exception('longitude field required!');
    }

    $name = $_POST['sname'];
    $category = $_POST['category'];
    $latitude = floatval($_POST['latitude']);
    $longitude = floatval($_POST['longitude']);
    $owner = $_SESSION['Account'];
    $stmt = $conn->prepare("select shopname from shop where shopname=:shopname");
    $stmt->execute(array('shopname' => $name));

    //if(!ctype_alpha($name)) throw new Exception('Shopname may only consist of characters.');
    //if(!ctype_alpha($category)) throw new Exception('Category may only consist of characters.');
    if(!is_float($latitude) || !is_float($longitude)) throw new Exception('Latitude and Longtitude should be float type.');
    if($latitude < -90.0 || $latitude > 90.0) throw new Exception('Latitude out of range, should be the number between -90 and 90.');
    if($longitude < -180.0 || $longitude > 180.0) throw new Exception('Longitude out of range, should be the number between -180 and 180.');
    //check shop name repeat or not
    
    if($stmt->rowCount()==0)
    {
        
        $stmt = $conn->prepare("insert into shop (shopname, category, longitude, latitude, s_location, owner) 
                                values (:shopname, :category, :longitude, :latitude, ST_GeomFromText('POINT($longitude $latitude)'), :owner)");
        
        $stmt->execute(array('shopname' => $name, 'category' => $category, 'longitude' => $longitude, 'latitude' => $latitude, 'owner' => $owner));
        
        $stmt = $conn->prepare("update users set identity='Shopkeeper' where account=:account");
        $stmt->execute(array('account' => $_SESSION['Account']));

        $_SESSION['Authenticated'] = true;
        $_SESSION['Shopname'] = $name;
        echo <<<EOT
            <!DOCTYPE html>
            <html>
                <body>
                    <script>
                    alert("Start success !!");
                    window.location.replace("nav.php");
                    </script>
                </body>
            </html>
        EOT;
        exit();
    }
    else
        throw new Exception('Shop name has been registered !!');
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