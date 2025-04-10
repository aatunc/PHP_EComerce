<?php
session_start();
error_reporting("E_ALL");
require_once("dbcontroller.php");
$conn = connectDB();
  if(!empty($_GET["action"])) {
     //echo $_GET["action"];
  switch($_GET["action"]) {
case "add":
    if(!empty($_POST["quantity"])) {
        $productByCode = mysqli_query($conn,"SELECT * FROM  tblproduct WHERE code='" . $_GET["code"] . "'");
                    $products = mysqli_fetch_array($productByCode);
        $itemArray = array($products["code"]=>array('name'=>$products["name"], 'code'=>$products["code"], 'quantity'=>$_POST["quantity"], 'price'=>$products["price"]));

        if(!empty($_SESSION["cart_item"])) {
            if(in_array($products["code"],array_keys($_SESSION["cart_item"]))) {
                foreach($_SESSION["cart_item"] as $k => $v) {
                        if($products[0]["code"] == $k) {
                            if(empty($_SESSION["cart_item"][$k]["quantity"])) {
                                $_SESSION["cart_item"][$k]["quantity"] = 0;
                            }
                            $_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
                        }
                }
            } else {
                $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
            }
        } else {
            $_SESSION["cart_item"] = $itemArray;
        }
    }
break;
case "remove":
    if(!empty($_SESSION["cart_item"])) {
        foreach($_SESSION["cart_item"] as $k => $v) {
                if($_GET["code"] == $k)
                    unset($_SESSION["cart_item"][$k]);              
                if(empty($_SESSION["cart_item"]))
                    unset($_SESSION["cart_item"]);
        }
    }
break;
case "empty":
    unset($_SESSION["cart_item"]);
break;  
}
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
$coupon = $_POST["coupon"];
$total = $_POST["total"];
if($coupon == "off30"){
     $disc_item_total = $total*0.7;
}
else{
     $disc_item_total = $total;
}