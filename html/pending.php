<?php
    declare(strict_types = 1);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    include '../lib/db.php';
    include '../lib/functions.php';
    session_start();
    // Go home if home button is clicked
    if(isset($_POST["home"]))
    {
        header("location:home.php");
    }
    //connect to mariadb
    $pdo = connectdb();


    echo "Was the submit button pushed?:";
    if(isset($_POST["modify"]))
    {
        echo "yes, submit button pushed";
        echo "</br>";

        if($_POST['notes'] != NULL){
            $sql ='UPDATE Orders SET Notes = :notes WHERE OrderID = :orderID;';
            $result = false;    
            try {
                $statement = $pdo->prepare($sql);
                if($statement) {
                        $result = $statement->execute([
                            ':notes' => $_POST['notes'],
                            ':orderID' => $_POST['orderID']
                        ]);
                        echo "</br>"; 
                        echo "notes updated";
                        echo "</br>";
                } else {
                    echo "    <p>Could not query database for unknown reason.</p>\n";
                }
            } catch (PDOException $e){
                echo "    <p>Could not query from database. PDO Exception: {$e->getMessage()}</p>\n";
            }       
    
        }
        if($_POST['tracking'] != NULL){
            $sql ='UPDATE Orders SET TrackingNum = :tracking WHERE OrderID = :orderID;';
            $result = false;    
            try {
                $statement = $pdo->prepare($sql);
                if($statement) {
                        $result = $statement->execute([
                            ':tracking' => $_POST['tracking'],
                            ':orderID' => $_POST['orderID']
                        ]);
                        echo "</br>"; 
                        echo "tracking updated";
                        echo "</br>";
                } else {
                    echo "    <p>Could not query database for unknown reason.</p>\n";
                }
            } catch (PDOException $e){
                echo "    <p>Could not query from database. PDO Exception: {$e->getMessage()}</p>\n";
            }       
        }if($_POST['status'] != NULL){
            $sql ='UPDATE Orders SET Status = :status WHERE OrderID = :orderID;';
            $result = false;    
            try {
                $statement = $pdo->prepare($sql);
                if($statement) {
                        $result = $statement->execute([
                            ':status' => $_POST['status'],
                            ':orderID' => $_POST['orderID']
                        ]);
                        echo "</br>"; 
                        echo "status updated";
                        echo "</br>";
                } else {
                    echo "    <p>Could not query database for unknown reason.</p>\n";
                }
            } catch (PDOException $e){
                echo "    <p>Could not query from database. PDO Exception: {$e->getMessage()}</p>\n";
            }       
        }

    } else {echo "no";}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Page</title>
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
        </style>
    </head>
    <body>
        <h1>Employee Page - Pending Orders</h1>    
        <h2>Pending Orders below:</h2>
        <form method="POST">
            <button class="button home" name="home">Home 
                <i class="fa fa-home"></i>
            </button>
        </form>

        <h4> post: </h4>
        <pre> <?php print_r($_POST); ?> </pre> 

        <?php drawTablePending($pdo); ?>

        <h2>Update Tracking, Status, and Notes:</h2>
        <form method="POST">   
            <label for="orderID">Order to modify:</label>
            <input type="number" id="orderID" name="orderID"></br>
            <label for="notes">Notes:</label>
            <input type="text" id="notes" name="notes"></br>
            <label for="tracking">Tracking:</label>
            <input type="tracking" id="tracking" name="tracking"></br>
            <label for="status">Status:</label>
            <input type="text" id="status" name="status"></br>
            <button class="button submit" name="modify" id="checkout">
            Modify order <i class="fa fa-check"></i>
            </button></br>
       
        </form>

        <h2>All Orders below:</h2>
        <?php drawTableOrders($pdo); ?>


    </body>
</html>