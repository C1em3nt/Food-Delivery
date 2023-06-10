<?php
session_start();
$_SESSION['Authenticated'] = false;

include('db.php');

try {
    if(!isset($_POST['account']) || !isset($_POST['password'])) {
        header("Location: index.html");
        exit();
    }

    if (empty($_POST['account'])||empty($_POST['password'])) {
        throw new Exception('Please enter Account and Password.');
    }

    $account = $_POST['account'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("select account, password, name, salt from users where account=:account");
    $stmt->execute(array('account' => $account));

    if($stmt->rowCount() == 1)
    {
        $row = $stmt->fetch();
        if($row['password'] == hash('sha256', $row['salt'].$_POST['password'])){
            $_SESSION['Authenticated'] = true;
            $_SESSION['Account'] = $row['account'];
            $_SESSION['Search'] = "";
            $stmt = $conn->prepare("select * from shop where owner=:owner");
            $stmt->execute(array('owner' => $_SESSION['Account']));
            $row=$stmt->fetch();
            $_SESSION['Shopname'] = $row['shopname'];
            $_SESSION['Location'] = $row['location'];
            header("Location: nav.php");
            exit();
        }
        else
            throw new Exception('login failed.');
    }
    else
        throw new Exception('login failed.');
}
catch(Exception $e){

    $msg = $e->getMessage();
    session_unset();
    session_destroy();
    echo <<<EOT
        <!DOCTYPE html>
        <html>
            <body>
                <script>
                alert("$msg");
                window.location.replace("index.html");
                </script>
            </body>
        </html>
    EOT;
}
?>