<?php
  session_start();
  include('db.php');
  
  if(!isset($_SESSION['Account'])) {
    header("Location: index.html");
    exit();
  }
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <title>Hello, world!</title>
</head>

<div class="container">
    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <form action="order.php" method="post">
                <div class="row">
                  <div class="  col-xs-8">
                    <table class="table" style=" margin-top: 15px;">
                      <thead>
                        <tr>
                          <th scope="col">Picture</th>
                          <th scope="col">meal name</th>
                          <th scope="col">price</th>
                          <th scope="col">Order Quantity</th>
                        </tr>
                      </thead>
                      <?php
                      $length = $_SESSION['Count'];
                      $_SESSION['Caltotal'] = 0;
                      for($i=0 ; $i < $length; $i++){
                        $stmt = $conn->prepare("select * from product where PID=:PID");
                        $stmt->execute(array('PID' => $_SESSION['Calpid'][$i]));
                        $product = $stmt->fetch();
                        $img=$product["img"];
                        $logodata = $img;
                        $_SESSION['Caltotal'] += $product['price'] * $_SESSION['Calnum'][$i]; 
                        ?>
                        <tbody>
                          <tr>
                            <td><?='<img src="data:'.$product['imgType'].';base64,' . $logodata . '"/ width="90" height="90" alt="Hamburger" >';?></td>
                            <td><?=$_SESSION['Calname'][$i]?></td>
                            <td><?=$product['price']?></td>
                            <td><?=$_SESSION['Calnum'][$i]?></td>
                            <input type="hidden" name="orderid[]" value=<?=$_SESSION['Calpid'][$i]?>>
                            <input type="hidden" name="ordername[]" value=<?=$_SESSION['Calname'][$i]?>>
                            <input type="hidden" name="ordernum[]" value=<?=$_SESSION['Calnum'][$i]?>>
                          </tr>
                        </tbody>
                      <?php }?>
                    </table>
                  </div>
                </div> 
                <?php $_SESSION['Totalamount'] = $_SESSION['Caltotal'] + $_SESSION['Fee']; ?>
            <div class="col-xs-10">
                  Subtotal: <?= $_SESSION['Caltotal']?></br>
                  Delivery fee: <?= $_SESSION['Fee']; ?></br>
                  Total price: <?=$_SESSION['Totalamount']; ?></br>
            </div>
            
            <button type="submit" class="btn btn-default">Order</button>
            </form>
        </div> <!--home-->
    </div>
</div>