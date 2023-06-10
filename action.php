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
                  <th scope="col">Record ID</th>
                  <th scope="col">Action</th>
                  <th scope="col">Time</th>
                  <th scope="col">Trader</th>
                  <th scope="col">Amount change</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $q = $_POST['str'];
                if($q == 'all'){
                    $stmt = $conn->prepare("select * from transaction where UID=:UID ORDER by TID ASC");
                    $stmt->execute(array('UID' => $_SESSION['Account']));
                    $row = $stmt->fetchall();
                }
                else{
                    $stmt = $conn->prepare("select * from transaction where UID=:UID and action=:action ORDER by TID ASC");
                    $stmt->execute(array('UID' => $_SESSION['Account'], 'action' => $q));
                    $row = $stmt->fetchall();
                }

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
                    
    </div>
</body>