<?php
  session_start();
  include('db.php');
  $account = $_SESSION['Account'];
  $stmt = $conn->prepare("select * from users where account=:account");
  $stmt->execute(array('account' => $account));
  $row=$stmt->fetch();
  $_SESSION['Location'] = $row['location'];
  $_SESSION['Count'] = 0;
  
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
  <title>Order website</title>
</head>

<script>
function check_shopname(sname) {
	if(sname!=""){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			var message;
			var fontcolor;
			if(this.readyState == 4 && this.status == 200){
				switch(this.responseText){
					case 'NO':
						message = 'Shop name has been registered.';
						fontColor = "red";
						break;
					case 'YES':
						message = 'Shop name is available.';
						fontColor = "green";
						break;
					default:
						message = 'There is sth wrong.';
						break;
				}
				document.getElementById("msg").innerHTML = "<font color=" + fontColor + ">" + message + "</font>";
			}
		};
		xhttp.open("POST", "check_shopname.php", true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send("sname="+sname);
	}
	else
		document.getElementById("msg").innerHTML = "";
}
</script>
<script>
$(function() {
    var hash = window.location.hash;

    // do some validation on the hash here

    hash && $('ul.nav a[href="' + hash + '"]').tab('show');
});
</script>
<body>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand " href="#">Order website</a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <form action="logout.php">
          <button type="submit" class="btn btn-danger navbar-btn">Log out</button>
        </form>
      </ul>
      </div>
</nav>
  <div class="container" style="word-break: keep-all;">

    <ul class="nav nav-tabs">
      <li class="active"><a href="#home">Home</a></li>
      <li><a href="#menu1" >Shop</a></li>
      <li><a href="#menu2" >MyOrder</a></li>
      <li><a href="#menu3" >Shop Order</a></li>
      <li><a href="#menu4" >Transaction Record</a></li>
    </ul>

    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
          <h3>Profile</h3>
          <div class="row">
            <div class="col-xs-10">
              Account: <?= $row['account']; ?></br>
              Name: <?= $row['name']; ?></br>
              Identity: <?= $row['identity']; ?></br>
              Phone number: <?= $row['phonenumber']; ?></br>
              Longitude:<?=$row['longitude']; ?>  Latitude:<?= $row['latitude']; ?>
              <button type="button" class=" btn btn-primary " data-toggle="modal"
                data-target="#Hamburger-<?= $row['account']?>">Edit Location</button></br>
              <form action="editlocation.php" method="post">
                <div class="modal fade" id="Hamburger-<?= $row['account']?>"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog"  role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Location</h4>
                      </div>
                      <div class="modal-body">
                        <div class="row" >
                          <div class="col-xs-6">
                            <label for="ex71">Longitude</label>
                            <input class="form-control" id="ex71" type="text" name="longitude">
                          </div>
                          <div class="col-xs-6">
                            <label for="ex41">Latitude</label>
                            <input class="form-control" id="ex41" type="text" name="latitude">
                          </div>
                        </div>
                      </div>

                      <div class="modal-footer">
                        <button type="submit" class="btn btn-default">Edit</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              Wallet balance: <?= $row['balance']; ?>
              <!-- Modal -->
              
              <button type="button" style="margin-left: 5px;" class=" btn btn-primary " data-toggle="modal"
                data-target="#myModal<?= $row['account']?>">Add value</button>
              <form action="addvalue.php" method="post">
                <div class="modal fade" id="myModal<?= $row['account']?>"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog  modal-sm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add value</h4>
                      </div>
                      <div class="modal-body">
                        <input type="text" class="form-control" id="Meal" placeholder="enter add value" name="money">
                      </div>

                      <div class="modal-footer">
                        <button type="submit" class="btn btn-default" >Add</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>

          </div>
 
        <!-- -->
        <h3>Search</h3>
        <div class=" row  col-xs-8">
          <form class="form-horizontal" action="search.php" method="post">
            <div class="form-group">
              <label class="control-label col-sm-1" for="Shop">Shop</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="shopname" placeholder="Enter Shop name">
              </div>
              <label class="control-label col-sm-1" for="distance">distance</label>
              <div class="col-sm-5">
                <select class="form-control" id="sel1" name="distance">
                  <option>none</option>
                  <option>near</option>
                  <option>medium </option>
                  <option>far</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-1" for="Price">Price</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="minprice">
              </div>
              <label class="control-label col-sm-1" for="~">~</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="maxprice">
              </div>
              <label class="control-label col-sm-1" for="Meal">Meal</label>
              <div class="col-sm-5">
                <input type="text" list="Meals" class="form-control" id="Meal" placeholder="Enter Meal" name="meal">
                <datalist id="Meals">
                  <option value="Hamburger">
                  <option value="coffee">
                </datalist>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-1" for="category"> category</label>
                <div class="col-sm-5">
                  <input type="text" list="categorys" class="form-control" id="category" placeholder="Enter shop category" name="category">
                  <datalist id="categorys">
                    <option value="fast food">
                  </datalist>
                </div>
                <button type="submit" style="margin-left: 18px;"class="btn btn-primary" name="search">Search</button>
            </div>
          </form>
        </div>

        <div class="row">
          <div class="  col-xs-8">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">#</th>
                
                  <th scope="col">shop name</th>
                  <th scope="col">shop category</th>
                  <th scope="col">Distance</th>
               
                </tr>
              </thead>
              <tbody>
              
              <?php
              if($_SESSION['Search'] != ''){
                if(!empty($_SESSION['Sname'])){
                  $condition1 = "%$_SESSION[Sname]%";
                }
                else $condition1 = "%%";
                if(!empty($_SESSION['Dist'])){
                  if($_SESSION['Dist'] == 'near'){
                    $condition2 = "near";
                  }
                  if($_SESSION['Dist'] == 'medium'){
                    $condition2 = "medium";
                  }
                  if($_SESSION['Dist'] == 'far'){
                    $condition2 = "far";
                  }
                  if($_SESSION['Dist'] == 'none'){
                    $condition2 = "%%";
                  }
                }
                else $condition2 = "";
                if(!empty($_SESSION['Minp'])){
                  $condition3 = $_SESSION['Minp'];
                }
                else $condition3 = "0";
                if(!empty($_SESSION['Maxp'])){
                  $condition4 = $_SESSION['Maxp'];
                }
                else $condition4 = "10000000";
                if(!empty($_SESSION['Mname'])){
                  $condition5 = "%$_SESSION[Mname]%";
                } 
                else $condition5 = "%%";
                if(!empty($_SESSION['Category'])){
                  $condition6 = "%$_SESSION[Category]%";
                } 
                else $condition6 = "%%";

              $stmt = $conn->prepare("select * from product inner join shop on product.shopname = shop.shopname  where shop.shopname like :condition1 
              and distance like :condition2 and price >= :condition3 and price <= :condition4 and mealname like :condition5 and shop.category like :condition6
              GROUP BY SID ORDER by ST_Distance_Sphere(s_location, :location) ASC, shop.shopname ASC, category ASC");
              $stmt->execute(array('condition1' => $condition1, 'condition2' => $condition2, 'condition3' => $condition3, 'condition4' => $condition4, 
              'condition5' => $condition5, 'condition6' => $condition6, 'location' => $_SESSION['Location']));
              $row = $stmt->fetchall();
              $num = 1;
              foreach($row as $slist){?>
                <tr>
                  <td scope="row"><?=$num?></td>
      
                  <td><?=$slist['shopname']?></td>
                  <td><?=$slist['category']?></td>
                  <td><?=$slist['distance']?></td>
                  <td><button type="button" class="btn btn-info " data-toggle="modal" data-target="#macdonald<?=$slist['SID'] ?>">Open menu</button>
                    
                    <div class="modal fade" id="macdonald<?=$slist['SID'] ?>"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">     
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form action="calculate.php" method="post">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><?=$slist['shopname'] ?> menu</h4>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-xs-12">
                                <table class="table" style=" margin-top: 15px;">
                                  <thead>
                                    <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Picture</th>
                                      <th scope="col">meal name</th>
                                      <th scope="col">price</th>
                                      <th scope="col">Quantity</th>
                                      <th scope="col">Order</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    $stmt2 = $conn->prepare("select * from product where shopname=:shopname ORDER by PID ASC");
                                    $stmt2->execute(array('shopname' => $slist['shopname']));
                                    $row2 = $stmt2->fetchall();
                                    $num2=1;
                                    foreach($row2 as $plist){?>
                                      <tr>
                                        <td scope="row"><?=$num2?></td>
                                        <?php
                                        $img=$plist["img"];
                                        $logodata = $img;
                                        ?>
                                        <td><?='<img src="data:'.$plist['imgType'].';base64,' . $logodata . '"/ width="90" height="90" alt="Hamburger" >';?></td>
                                        <td><?=$plist['mealname']?></td>
                                        <td><?=$plist['price']?></td>
                                        <td><?=$plist['quantity']?></td>

                                        <input type="hidden" name="calpid[]" value=<?=$plist['PID']?>>
                                        <input type="hidden" name="calname[]" value=<?=$plist['mealname']?>>
                                        <input type="hidden" name="calprice[]" value=<?=$plist['price']?>>
                                        <input type="hidden" name="o_shopname" value=<?=$plist['shopname']?>>
                                        <td>
                                        <button onclick="var result = document.getElementById('qty<?=$plist['PID']?>'); var qty = result.value; if( !isNaN( qty ) &amp;&amp; qty &gt; 0 ) result.value--;return false;<?=$plist['PID']?>" class="col-xs-1"  type="button">-</button>
                                        <input type="text" class="col-xs-3" title="Qty" value="0" id="qty<?=$plist['PID']?>" name="calnum[]">
                                        <button onclick="var result = document.getElementById('qty<?=$plist['PID']?>'); var qty = result.value; if( !isNaN( qty )) result.value++;return false;<?=$plist['PID']?>" class="col-xs-1" type="button">+</button>
                                        </td>
                                      </tr>
                                    <?php 
                                    $num2+=1;}?>
                                  </tbody>
                                </table>
                                <label class="control-label col-sm-1" for="type" style=" margin-top: 25px;">Type</label>
                                <div class="col-sm-3">
                                        <select class="form-control" id="sel3" name="ordertype" style=" margin-top: 20px;">
                                          <option>Delivery</option>
                                          <option>Pick-up</option>
                                        </select>
                                </div>
                              </div>
                            </div> 
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-default" data-toggle="modal" data-target="#order">Calculate the price</button>
                          </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </td>
                  <?php $num += 1;?>
                </tr>
              <?php } 
              } ?>
              </tbody>

            </table>

          </div>
        </div>

      </div> <!--home-->
    
      <div id="menu1" class="tab-pane fade">
        <?php
          $stmt = $conn->prepare("select * from shop where owner=:owner");
          $stmt->execute(array('owner' => $_SESSION['Account']));
          $row = $stmt->fetch();
        ?>
        <form action="startbusiness.php" method="post">
          <h3> Start a business </h3>
          <div class="form-group ">
            <div class="row">
              <div class="col-xs-2">
                <label for="ex5">shop name</label>
                <input class="form-control" name="sname" oninput="check_shopname(this.value);" id="ex5" placeholder="Shop name" type="text"  <?php if($stmt->rowCount() == 1){?> value="<?=$row['shopname']?>" <?php if($row['shopname']!=''){?>disabled <?php }?> <?php }?>>
                <label id="msg"></label><br>
              </div>
              <div class="col-xs-2">
                <label for="ex5">shop category</label>
                <input class="form-control" name="category" id="ex5" placeholder="Category" type="text" <?php if($stmt->rowCount() == 1){?> value="<?=$row['category']?>" <?php if($row['category']!=''){?>disabled <?php }?> <?php }?>>
              </div>
              <div class="col-xs-2">
                <label for="ex8">longitude</label>
                <input class="form-control" name="longitude" id="ex8" placeholder="Longitude" type="text" <?php if($stmt->rowCount() == 1){?> value="<?=$row['longitude']?>"  <?php if($row['longitude']!=''){?>disabled <?php }?> <?php }?>>
              </div>
              <div class="col-xs-2">
                <label for="ex6">latitude</label>
                <input class="form-control" name="latitude" id="ex6" placeholder="Latitude" type="text" <?php if($stmt->rowCount() == 1){?> value="<?=$row['latitude']?>"  <?php if($row['latitude']!=''){?>disabled <?php }?> <?php }?>>
              </div>
            </div>
          </div>
          <div class=" row" style=" margin-top: 25px;">
            <div class=" col-xs-3">
              <input type="submit" class="btn btn-primary"  value="register" <?php if($stmt->rowCount() == 1){?> <?php if($row['shopname']!=''){?>disabled <?php }?> <?php }?>>
            </div>
          </div>
        </form>
        <hr>
        <form action="addproduct.php" method="post" Enctype="multipart/form-data">
          <h3>ADD</h3>
          <div class="form-group ">
            <div class="row">
            <div class="col-xs-6">
                <label for="ex3">meal name</label>
              <input class="form-control" name="meal" id="ex3" type="text">
              </div>
            </div>
            <div class="row" style=" margin-top: 15px;">
              <div class="col-xs-3">
              <label for="ex7">price</label>
              <input class="form-control" name="price" id="ex7" type="text">
              </div>
              <div class="col-xs-3">
                <label for="ex4">quantity</label>
                <input class="form-control" name="quantity" id="ex4" type="text">
              </div>
            </div>
            <div class="row" style=" margin-top: 25px;">
              <div class=" col-xs-3">
                <label for="ex12">上傳圖片</label>
                <input id="myFile" type="file" name="myfile" multiple class="file-loading">
              </div>
              <div class=" col-xs-3">
                <button style=" margin-top: 15px;" type="submit" class="btn btn-primary">Add</button>
              </div>
            </div>
          </div>
        </form>
        <div class="row">
          <div class="  col-xs-8">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Picture</th>
                  <th scope="col">meal name</th>
                  <th scope="col">price</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Edit</th>
                  <th scope="col">Delete</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $stmt = $conn->prepare("select * from product inner join shop on shop.shopname = product.shopname where owner=:owner ORDER by PID ASC");
                $stmt->execute(array('owner' => $_SESSION['Account']));
                $row = $stmt->fetchall();
                $num3 = 1;
                foreach($row as $plist){?>
                <tr>
                  <th scope="row"><?=$num3?></th>
                  <?php
                  $img=$plist["img"];
                  $logodata = $img;
                  ?>
                  <td><?= '<img src="data:'.$plist['imgType'].';base64,' . $logodata . '"/ width="100" heigh="100" alt="Hamburger" >';?></td>
                  <td><?=$plist['mealname'] ?></td>
                
                  <td><?=$plist['price'] ?> </td>
                  <td><?=$plist['quantity'] ?> </td>
                  <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#Hamburger-<?=$plist['PID'] ?>">
                  Edit
                  </button></td>
                  <!-- Modal -->
                  <form name="updateform<?=$plist['PID'] ?>" method="post">
                    <div class="modal fade" id="Hamburger-<?=$plist['PID'] ?>" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel"><?=$plist['mealname']?> Edit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="row" >
                              <div class="col-xs-6">
                                <label for="ex71">price</label>
                                <input class="form-control" id="ex71" type="text" name="price">
                              </div>
                              <div class="col-xs-6">
                                <label for="ex41">quantity</label>
                                <input class="form-control" id="ex41" type="text" name="quantity">
                              </div>
                              <input type="hidden" name="id" value="<?=$plist['PID'] ?>">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="edit<?=$plist['PID']?>()">Edit</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <td><button type="button" class="btn btn-danger" onclick="del<?=$plist['PID']?>()">Delete</button></td>
                  </form>
                  <script>
                      function edit<?=$plist['PID']?>() { 
                        updateform<?=$plist['PID']?>.action="updateproduct.php";
                        updateform<?=$plist['PID']?>.submit();
                      }
                      function del<?=$plist['PID']?>() { 
                        updateform<?=$plist['PID']?>.action="delproduct.php";
                        updateform<?=$plist['PID']?>.submit();
                      }
                  </script>
                </tr>
                <?php $num3 += 1;}?>
              </tbody>
            </table>
          </div>
        </div>
      </div> <!--menu1-->  

      <div id="menu2" class="tab-pane fade">
        <div class="row">
          <div class="  col-xs-9">
            <label class="control-label col-sm-1" for="status" style=" margin-top: 25px;">Status</label>
                <div class="col-sm-3">
                  <select class="form-control" id="sel2" name="status" style=" margin-top: 20px;" onChange="filt(this.value)">
                    <option value="all" >All</option>
                    <option value="Finished">Finished</option>
                    <option value="Not Finished">Not Finished</option>
                    <option value="Canceled">Canceled</option>
                  </select>
                </div>
                <script>
                  function filt(str) {
                    $.ajax({
                      url:"status.php",
                      data:'str='+str,
                      type: "post",
                      success: function(msg4){
                        $("#origin").html(msg4);
                      }
                    });
                  }
                </script>
              <span id="origin">
              <table class="table" style=" margin-top: 10px;">
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
                $stmt = $conn->prepare("select * from orders where buyer=:buyer ORDER by OID ASC");
                $stmt->execute(array('buyer' => $_SESSION['Account']));
                $row = $stmt->fetchall();
                foreach($row as $olist){?>
                <form name="orderform1<?=$olist['OID'] ?>" id="orderform1<?=$olist['OID'] ?>" method="post">
                <tr>
                  <td scope="row"><?=$olist['OID']?></td>
                  <td><?=$olist['status']?></td>
                  <td><?=$olist['start_t']?></td>
                  <td><?=$olist['end_t']?></td>
                  <td><?=$olist['shop']?></td>
                  <td><?=$olist['tot_amount']?></td>
                  <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#ordered<?=$olist['OID']?>">
                  Order Details</button>
                  <!-- Modal -->
                  <div class="modal fade" id="ordered<?=$olist['OID']?>" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                    <input type="hidden" name="oid" value="<?=$olist['OID']?>">
                    <td><button type="button" class="btn btn-danger" onclick="buyercancel<?=$olist['OID']?>()">Cancel</button></td>
                  <?php }?>
                </tr>
                <script>
                      function buyercancel<?=$olist['OID']?>() { 
                        orderform1<?=$olist['OID']?>.action="buyercancel.php";
                        orderform1<?=$olist['OID']?>.submit();
                      }
                  </script>
                </form>
                <?php }?>
              </tbody>
            </table>
            </span>
          </div>
        </div>
      </div> <!--menu2-->
      
      <div id="menu3" class="tab-pane fade">
        <div class="row">
          <div class="  col-xs-9">
            <label class="control-label col-sm-1" for="status" style=" margin-top: 25px;">Status</label>
                <div class="col-sm-3">
                  <select class="form-control" id="sel3" name="status" style=" margin-top: 20px;" onChange="filt2(this.value)">
                    <option value="all" >All</option>
                    <option value="Finished">Finished</option>
                    <option value="Not Finished">Not Finished</option>
                    <option value="Canceled">Canceled</option>
                  </select>
                </div>
                <script>
                  function filt2(str) {
                    $.ajax({
                      url:"status2.php",
                      data:'str='+str,
                      type: "post",
                      success: function(msg4){
                        $("#origin2").html(msg4);
                      }
                    });
                  }
                </script>
              <span id="origin2">
              <table class="table" style=" margin-top: 10px;">
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
                $stmt = $conn->prepare("select * from orders where shop=:shop ORDER by OID ASC");
                $stmt->execute(array('shop' => $_SESSION['Shopname']));
                $row = $stmt->fetchall();
                foreach($row as $olist){?>
                <tr>
                  <td scope="row"><?=$olist['OID']?></td>
                  <td><?=$olist['status']?></td>
                  <td><?=$olist['start_t']?></td>
                  <td><?=$olist['end_t']?></td>
                  <td><?=$olist['shop']?></td>
                  <td><?=$olist['tot_amount']?></td>
                  <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#shopordered<?=$olist['OID']?>">
                  Order Details</button>
                  <!-- Modal -->
                  <div class="modal fade" id="shopordered<?=$olist['OID']?>" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                      <td><button type="button" class="btn btn-danger" onclick="shopcancel<?=$olist['OID'] ?>()">Cancel</button></td>
                      </form>
                    <?php }?>
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
                </tr>
                <?php }?>
              </tbody>
            </table>
            </span>
          </div>
        </div>
      </div> <!--menu3-->  

      <div id="menu4" class="tab-pane fade">
        <div class="row">
          <div class="  col-xs-9">
            <label class="control-label col-sm-1" for="status" style=" margin-top: 25px;">Action</label>
                <div class="col-sm-3">
                  <select class="form-control" id="sel4" name="status" style=" margin-top: 20px;" onChange="filt3(this.value)">
                    <option value="all">All</option>
                    <option value="Payment">Payment</option>
                    <option value="Receive">Receive</option>
                    <option value="Recharge">Recharge</option>
                  </select>
                </div>
                <script>
                  function filt3(str) {
                    $.ajax({
                      url:"action.php",
                      data:'str='+str,
                      type: "post",
                      success: function(msg4){
                        $("#origin3").html(msg4);
                      }
                    });
                  }
                </script>
              <span id="origin3">
              <table class="table" style=" margin-top: 10px;">
              <thead>
                <tr>
                  <th scope="col">Record ID</th>
                  <th scope="col">Action</th>
                  <th scope="col">Time</th>
                  <th scope="col">Trader</th>
                  <th scope="col">Amount change</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $stmt = $conn->prepare("select * from transaction where UID=:UID ORDER by TID ASC");
                $stmt->execute(array('UID' => $_SESSION['Account']));
                $row = $stmt->fetchall();
                foreach($row as $tlist){?>
                <tr>
                  <td scope="row"><?=$tlist['TID']?></td>
                  <td><?=$tlist['action']?></td>
                  <td><?=$tlist['time']?></td>
                  <td><?=$tlist['trader']?></td>
                  <td><?=$tlist['amount']?></td>
                </tr>
                <?php }?>
              </tbody>
            </table>
                </span>
          </div>
        </div>
      </div> <!--menu4--> 
    </div>
  </div>

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
  <script>
    $(document).ready(function () {
      $(".nav-tabs a").click(function () {
        $(this).tab('show');
      });
    });
  </script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>