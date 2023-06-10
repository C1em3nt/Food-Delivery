<?php
session_start();

include('db.php');

try {

    if (empty($_POST['meal'])) 
    {
        throw new Exception('Meal name field required!');
    }
    if (empty($_POST['price'])) 
    {
        throw new Exception('Price field required!');
    }
    if (empty($_POST['quantity'])) 
    {
        throw new Exception('Quantity field required!');
    }



    $shopname = $_SESSION['Shopname'];
    $meal = $_POST['meal'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $file = fopen($_FILES["myfile"]["tmp_name"], 'rb');
    $fileContents = fread($file, filesize($_FILES["myfile"]["tmp_name"]));
    fclose($file);
    $fileContents = base64_encode($fileContents);
    $imgType=$_FILES["myfile"]["type"];

    //if(!ctype_alnum($meal)) throw new Exception('Meal name may only consist of characters and numbers.');
    if(!ctype_digit($price)) throw new Exception('Price should be a positive number.');
    if(!ctype_digit($quantity)) throw new Exception('Quantity should be a positive number.');

    $stmt = $conn->prepare("select mealname from product where shopname=:shopname and mealname=:mealname");
    $stmt->execute(array('shopname'=>$shopname, 'mealname'=>$meal));

    //check if shop name repeat
    if($stmt->rowCount()==0)
    {
        
        $stmt = $conn->prepare("insert into product (shopname, mealname, price, quantity, img ,imgType) 
                                values (:shopname, :mealname, :price, :quantity, :img, :imgType)");
        $stmt->execute(array('shopname'=>$shopname, 'mealname'=>$meal, 'price'=>$price, 'quantity'=>$quantity, 'img'=>$fileContents, 'imgType'=>$imgType));

        echo <<<EOT
            <!DOCTYPE html>
            <html>
                <body>
                    <script>
                    alert("Add success !!");
                    window.location.replace("nav.php");
                    </script>
                </body>
            </html>
        EOT;
        exit();
    }
    else
        throw new Exception('Meal exist !!');
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