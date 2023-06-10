<?php
session_start();


include('db.php');

try {
    if(!isset($_POST['account']) || !isset($_POST['password'])) {
        header("Location: nav.php");
        exit();
    }

    if (empty($_POST['name'])||empty($_POST['phonenumber'])||empty($_POST['account'])||empty($_POST['password'])
        ||empty($_POST['re-password'])||empty($_POST['latitude'])||empty($_POST['longitude'])) 
    {
        throw new Exception('Some fields are blank.');
    }

    $name = $_POST['name'];
    $phonenumber = $_POST['phonenumber'];
    $account = $_POST['account'];
    $password = $_POST['password'];
    $repwd = $_POST['re-password'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $latitudecheck = floatval($_POST['latitude']);
    $longitudecheck = floatval($_POST['longitude']);
    if($repwd != $password) throw new Exception('Passwords do NOT match.');
    if(!ctype_digit($phonenumber) || strlen($phonenumber) != 10) throw new Exception('Phonenumber may only consist of numbers and small than 10 digits.');
    if(!ctype_alnum($account)) throw new Exception('Account may only consist of characters and numbers.');
    if(!ctype_alnum($password)) throw new Exception('Password may only consist of characters and numbers.');
    if(!ctype_alpha($name)) throw new Exception('Name may only consist of characters.');
    if(!is_float($latitudecheck) || !is_float($longitudecheck)) throw new Exception('Latitude and Longtitude should be float type.');
    if($latitudecheck < -90.0 || $latitudecheck > 90.0) throw new Exception('Latitude out of range, should be the number between -90 and 90.');
    if($latitudecheck < -180.0 || $latitudecheck > 180.0) throw new Exception('Longitude out of range, should be the number between -180 and 180.');


    $stmt = $conn->prepare("select account from users where account=:account");
    $stmt->execute(array('account' => $account));
    //check if account repeat
    if($stmt->rowCount()==0)
    {
        
        $salt = strval(rand(1000,9999));
        $hashvalue = hash('sha256', $salt.$password);


        $stmt = $conn->prepare("insert into users (name, phonenumber, account, password, salt, latitude, longitude
                            , location) values (:name, :phonenumber, :account, :password, :salt, :latitude, :longitude,  ST_GeomFromText('POINT($longitude $latitude)'))");
        
        $stmt->execute(array('name' => $name, 'phonenumber' => $phonenumber, 'account' => $account, 
                        'password' => $hashvalue, 'salt' => $salt, 'latitude' => $latitude, 'longitude' => $longitude));

        echo <<<EOT
            <!DOCTYPE html>
            <html>
                <body>
                    <script>
                    alert("Register success !!");
                    window.location.replace("index.html");
                    </script>
                </body>
            </html>
        EOT;
        exit();
    }
    else
        throw new Exception('Account has been registered !!');
}
catch(Exception $e) 
{

    $msg = $e->getMessage();
    session_unset();
    session_destroy();
    echo '<script>alert($msg)</script>';
    echo <<<EOT
        <!DOCTYPE html>
        <html>
            <body>
                <script>
                alert("$msg");
                window.location.replace("sign-up.html");
                </script>
            </body>
        </html>
    EOT;
}
?>