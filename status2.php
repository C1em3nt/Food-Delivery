<?php
session_start();
include('db.php');
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
<body>
    <div class="tab-content">
        <div class="row">
          <div class="  col-xs-12">
              <table class="table" style=" margin-top: 5px;">
              <thead>
                <tr>
                  <th scope="col">Order ID</th>
                  <th scope="col">Status</th>
                  <th scope="col">Start</th>
                  <th scope="col">End</th>
                  <th scope="col">Shop name</th>
                  <th scope="col">Total Price</th>
                  <th scope="col">Order Details</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $q = $_POST['str'];
                if($q == 'all'){
                    $stmt = $conn->prepare("select * from orders where shop=:shop ORDER by OID ASC");
                    $stmt->execute(array('shop' => $_SESSION['Shopname']));
                    $row = $stmt->fetchall();
                }
                else{
                    $stmt = $conn->prepare("select * from orders where shop=:shop and status=:status ORDER by OID ASC");
                    $stmt->execute(array('shop' => $_SESSION['Shopname'], 'status' => $q));
                    $row = $stmt->fetchall();
                }
                foreach($row as $olist){?>
                <tr>
                  <td scope="row"><?=$olist['OID']?></td>
                  <td><?=$olist['status']?></td>
                  <td><?=$olist['start_t']?></td>
                  <td><?=$olist['end_t']?></td>
                  <td><?=$olist['shop']?></td>
                  <td><?=$olist['tot_amount']?></td>
                  <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#ordered2<?=$olist['OID']?>">
                  Order Details</button>
                  <!-- Modal -->
                  <div class="modal fade" id="ordered2<?=$olist['OID']?>" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="staticBackdropLabel">Order <?=$olist['OID']?> details</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-xs-12">
                              <table class="table" style=" margin-top: 10px;word-break: keep-all;">
                                <thead>
                                  <tr>
                                    <th scope="col">Picture</th>
                                    <th scope="col">meal name</th>
                                    <th scope="col">price</th>
                                    <th scope="col">Order Quantity</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $stmt2 = $conn->prepare("select * from orderlist where OID=:OID ORDER by OID ASC");
                                  $stmt2->execute(array('OID' => $olist['OID']));
                                  $row2 = $stmt2->fetchall();
                                  foreach($row2 as $odetail){?>
                                    <tr>
                                      <?php
                                      $stmt3 = $conn->prepare("select * from product where PID=:PID");
                                      $stmt3->execute(array('PID' => $odetail['PID']));
                                      $row3 = $stmt3->fetch();
                                      $img=$row3["img"];
                                      $logodata = $img;
                                      ?>
                                      <td><?= '<img src="data:'.$row3['imgType'].';base64,' . $logodata . '"/ width="80" heigh="80" alt="Hamburger" >';?></td>
                                      <td><?=$row3['mealname'] ?></td>
                                      <td><?=$row3['price'] ?> </td>
                                      <td><?=$odetail['quantity'] ?> </td>
                                    </tr>
                                  <?php }?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          Subtotal: <?=($olist['tot_amount']-$olist['fee'])?></br>
                          Delivery fee: <?=$olist['fee']?></br>
                          Total price: <?=$olist['tot_amount']?></br>
                        </div>
                      </div>
                    </div>
                  </div></td>
                  <?php if($olist['status']=='Not Finished'){?>
                  <form name="shopform<?=$olist['OID'] ?>" method="post">
                    <input type="hidden" name="oid" value="<?=$olist['OID']?>">
                    <td><button type="button" class="btn btn-success" onclick="done<?=$olist['OID'] ?>()">Done</button></td>
                    <td><button type="button" class="btn btn-danger" onclick="shopcancel<?=$olist['OID']?>()">Cancel</button></td>
                  </form>
                  <script>
                      function done<?=$olist['OID']?>() { 
                        shopform<?=$olist['OID']?>.action="done.php";
                        shopform<?=$olist['OID']?>.submit();
                      }
                      function shopcancel<?=$olist['OID']?>() { 
                        shopform<?=$olist['OID']?>.action="shopcancel.php";
                        shopform<?=$olist['OID']?>.submit();
                      }
                  </script>
                  <?php }?>
                </tr>
                <?php }?>
              </tbody>
            </table>
          </div>
        </div>
                    
                    </div>
                    </body>