<?php
declare(strict_types = 1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include '../lib/db.php';
include '../lib/functions.php';
if(isset($_POST["cart"]))
{
    header("location:cart.php");
}
if(isset($_POST["home"]))
{
    header("location:home.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Armors</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>   
        body {
        height: 100%;
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        background-image: url("img/labs1.webp");
       
        }
        .row { background-color: rgba(0, 0, 0, 0.5); }
        /* Three image containers (use 25% for four, and 50% for two, etc) *****/
        .column {
            float: left;
            width: 33%;
            padding:0px;
            text-align: center;
        }
        /* Clear floats after image containers */
        .row::after {
            content: "";
            clear: both;
            display: table;
        }
        img{
            width:auto;
            height:auto;
            max-width:200px;
        }
        .cart {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 25px;
            margin: 20px 10px;
        }
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
    <h1>Armors</h1>
    <h2>Keeping yourself lead-free</h2>
    <form method="POST">
        <button class="button cart" name="cart">Shopping Cart 
            <i class="fa fa-shopping-cart"></i>
        </button>
    </form>
    <form method="POST">
        <button class="button home" name="home">Home 
            <i class="fa fa-home"></i>
        </button>
    </form>
<?php

$sql = <<<SQL
  SELECT item_id, Meds.type, Products.name, tagline, image
  FROM Meds
  INNER JOIN Products ON Products.id = Meds.item_id;
SQL;
try {
    $statement = $pdo->query($sql);
    if($statement) {
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        if($statement->rowCount() != 0) {
            // echo "    <p>Got rows!</p>\n<pre>\n";
        } else {
            echo "    <p>No rows!</p>\n";
        }
    } else {
        echo "    <p>Could not query armor items from database for unknown reason.</p>\n";
    }
} catch (PDOException $e){
    echo "    <p>Could not query armor items from database. PDO Exception: {$e->getMessage()}</p>\n";
}
if (!empty($rows)) {
    $tiers = [
        'Painkiller',
        'Wound treatment',
        'Surgical treatment'
        
    ];
    foreach($tiers as $tier){
        echo <<<HTML
            <h3>$tier</h3>
            <div class="row">\n
        HTML;
        // only armors of current tier (2,3,4...)
        $armors = array_filter($rows, function ($row) use ($tier) {
            return $row['type'] == $tier;
        });
        foreach($armors as $armor) {
           echo <<<HTML
                   <div class="column">
                       <a href="item.php?id={$armor['item_id']}" class="item">
                           <img src="{$armor['image']}" class="item" /><br />
                           {$armor['name']}
                       </a> <br>
                        <form action="" method="POST">
                        <label for=""></label>
                        &nbsp;<label for="quantity">QTY:</label>
                        <input type="number" min="0" id="quantity" name="QTY">
                        <input type="submit" value="Add to cart">
                        </form>
                      <!--- <p>{$armor['tagline']}</p> --->
                   </div>\n
            HTML;
        }
        echo "    </div>\n";
    }
}
?>
</body>
</html>