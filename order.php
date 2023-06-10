<?php
  session_start();
  include('db.php');
  
  try {

    if(!isset($_POST['orderid'])){
        throw new Exception('There is no order product.');
    }

    $stmt = $conn->prepare("select balance from users where account=:account");
    $stmt->execute(array('account' => $_SESSION['Account']));
    $user = $stmt->fetch();

    if($user['balance'] < $_SESSION['Totalamount']) {
        throw new Exception('Insufficient Balance.');
    }

    $cnt = $_POST['ordername'];
    $_SESSION['Caltotal'] = 0;
    $length = count($cnt);
    for($i=0; $i < $length; $i++)
    {
        $stmt = $conn->prepare("select * from product where PID=:PID");
        $stmt->execute(array('PID' => $_POST['orderid'][$i]));
        if($stmt->rowCount() < 1){
            throw new Exception($_POST['ordername'][$i].' does not exist!');
        }
        $product = $stmt->fetch();
        if($_POST['ordernum'][$i] > $product['quantity']) {
            throw new Exception($_POST['ordername'][$i].'數量不足!');
        }
        if(!ctype_digit($_POST['ordernum'][$i])) {
            throw new Exception('Order quantity should be positive numbers.');
        }
        $_SESSION['Caltotal'] += $product['price'] * $_POST['ordername'][$i];
    }

    $_SESSION['Totalamount'] = $_SESSION['Caltotal'] + $_SESSION['Fee'];
    
    $stmt = $conn->prepare("insert into orders (start_t, tot_amount, type, buyer, shop, fee) values 
    (NOW(), :tot_amount, :type, :buyer, :shop, :fee)");
    $stmt->execute(array('tot_amount' => $_SESSION['Totalamount'], 'type' => $_SESSION['Ordertype'], 
    'buyer' => $_SESSION['Account'], 'shop' => $_SESSION['O_shopname'], 'fee' => $_SESSION['Fee']));

    //money
    $stmt = $conn->query("select max(OID) as count from orders");
    $cont = $stmt->fetch();

    $stmt = $conn->prepare("update users set balance = balance - :total where account=:account");
    $stmt->execute(array('total' => $_SESSION['Totalamount'], 'account' => $_SESSION['Account']));
    
    $stmt = $conn->prepare("select * from shop inner join users on owner=account where shopname=:shopname");
    $stmt->execute(array('shopname' => $_SESSION['O_shopname']));
    $shop = $stmt->fetch();
    $stmt = $conn->prepare("update users set balance = balance + :total where account=:account");
    $stmt->execute(array('total' => $_SESSION['Caltotal'] ,'account' => $shop['owner']));

    //stuff
    for($i=0; $i < $length; $i++)
    {
        $stmt = $conn->prepare("update product set quantity = quantity - :o_quantity where PID=:PID");
        $stmt->execute(array('o_quantity' => $_POST['ordernum'][$i], 'PID' => $_POST['orderid'][$i]));

        $stmt = $conn->prepare("insert into orderlist (OID, PID, quantity) values (:OID, :PID, :quantity)");
        $stmt->execute(array('OID' => $cont['count'], 'PID' => $_POST['orderid'][$i], 'quantity' => $_POST['ordernum'][$i]));
    }

    //transaction
    $stmt = $conn->prepare("insert into transaction (action, amount, time, UID, trader) values ('Payment', :amount, NOW(), :UID, :trader)");
    $stmt->execute(array('amount' => "-".$_SESSION['Totalamount'], 'UID' => $_SESSION['Account'], 'trader' => $_SESSION['O_shopname']));

    $stmt = $conn->prepare("insert into transaction (action, amount, time, UID, trader) values ('Recieve', :amount, NOW(), :UID, :trader)");
    $stmt->execute(array('amount' => "+".$_SESSION['Caltotal'] , 'UID' => $shop['owner'], 'trader' => $_SESSION['Account']));
    
    echo <<<EOT
    <!DOCTYPE html>
    <html>
        <body>
            <script>
            alert("Order success !!");
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