<?php
//php block 1
    declare(strict_types = 1);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    include '../lib/db.php';
    include '../lib/functions.php';
    // Go home if home button is clicked
    if(isset($_POST["home"]))
    {
        header("location:home.php");
    }
    //connect to mariadb
    $pdo = connectdb();

        
    echo "Was the submit button pushed?:";
    if(isset($_POST["track"]))
    {
        if($_POST['track'] == 'track'){
            
            echo "yes, track button pushed";
            echo "</br>";
        
            $sql ='SELECT 
            Orders.OrderID, 
            Orders.UserID, 
            User.Name,
            OrderDetails.ItemID, 
            Products.name,
            OrderDetails.Price, 
            OrderDetails.QTYOrdered,
            Orders.OrderDate
            FROM OrderDetails 
            INNER JOIN Orders 
            ON  OrderDetails.OrderID = Orders.OrderID
            INNER JOIN Products 
            ON Products.id = OrderDetails.ItemID
            INNER JOIN User
            ON User.UserID = OrderDetails.UserID
            WHERE OrderDetails.OrderID = :orderID;';
            $result = false;    
            try {
                $statement = $pdo->prepare($sql);
                if($statement) {
                        $result = $statement->execute([
                            ':orderID' => $_POST['orderID']
                        ]);
                        $orderID = $_POST['orderID'];
                        echo "</br>"; 
                        echo "show orders for OrderID: $orderID ";
                        echo "</br>";

                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        drawTable($rows);
                       
                } else {
                    echo "    <p>Could not query database for unknown reason.</p>\n";
                }
            } catch (PDOException $e){
                echo "    <p>Could not query from database. PDO Exception: {$e->getMessage()}</p>\n";
            }       
        }
        if($_POST['track'] == 'UserID'){
            echo "yes, UserID button pushed";
            echo "</br>";
        
            $sql ='SELECT
            Orders.OrderID, 
            Orders.UserID, 
            User.Name, 
            OrderDetails.ItemID, 
            Products.name,
            OrderDetails.Price, 
            OrderDetails.QTYOrdered,
            Orders.OrderDate
            FROM OrderDetails 
            INNER JOIN Orders 
            ON  OrderDetails.OrderID = Orders.OrderID
            INNER JOIN Products 
            ON Products.id = OrderDetails.ItemID
            INNER JOIN User
            ON User.UserID = OrderDetails.UserID
            WHERE OrderDetails.UserID = :UserID;';
            $result = false;    
            try {
                $statement = $pdo->prepare($sql);
                if($statement) {
                        $result = $statement->execute([
                            ':UserID' => $_POST['UserID']
                        ]);
                        $userID = $_POST['UserID'];
                        echo "</br>"; 
                        echo "Show orders for user: $userID ";
                        echo "</br>";

                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        drawTable($rows);
                       
                } else {
                    echo "    <p>Could not query database for unknown reason.</p>\n";
                }
            } catch (PDOException $e){
                echo "    <p>Could not query from database. PDO Exception: {$e->getMessage()}</p>\n";
            }     

        }
    }
     else {echo "no";}


?>

<h4> post: </h4>
        <pre> <?php print_r($_POST); ?> </pre> 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Order History</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            .home{
                position: absolute;
                top: 10px;
                left: 10px;
                font-size: 25px;
                margin: 20px 10px;
            }
            .tack{
                position: relative;
                font-size: 25px;
                margin: 20px 10px;
            }
        </style>
    </head>
    <body>
        <form method="POST">
            <button class="button home" name="home">
                Home <i class="fa fa-home"></i>
            </button>
        </form>
        <h1>Order History</h1>    
        <h2>Check your order info and its current status</h2>
        <form method="POST">
            Order Number: <input type="text" id="ORDER_ID" name="orderID"/>
            <br>
            <button class="button track" name="track" id="track" value="track">
                Track <i class="fa fa-truck"></i>
            </button>
        </form>

        <form method="POST">
            UserID Numer: <input type="text" id="UserID" name="UserID"/>
            <br>
            <button class="button track" name="track" id="UserID" value="UserID">
                Track <i class="fa fa-truck"></i>
            </button>
        </form>
    </body>
</html>