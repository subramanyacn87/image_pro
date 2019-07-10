<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .p-30{
            padding: 30px 0;
            border-bottom: 2px solid #ddd;
        }
    </style>
</head>

<body>


    <?php
    /**
     * Created by PhpStorm.
     * User: USER1
     * Date: 10/07/2019
     * Time: 3:21 PM
     */
    include_once "admin/config/dbConnect.php";
    $db = new DbConnection();
    $output = $db->getResult("SELECT * FROM image order by created_at DESC");
    //echo "<pre>";print_r($output);
    ?>
    <div class="container">
        <div id="main-content title-product">
            <div class="row p-30">
                <div class="col-md-7 col-xs-12">
                    <a href="index.php" class="btn btn-primary">Upload more images</a>
                </div>
            </div>
            <?php
            for($i=0;$i < count($output);$i=$i+2){
                ?>
                <div class="row p-30">
                    <div class="col-md-7 col-xs-12">
                        <?php echo "Image original Name: ".$output[$i]['original_image_name']."<br>1280px X 720Px Image:<br>";?>
                        <img src="admin/images/<?php echo $output[$i]['stored_image_name']?>" alt="Cinque Terre" class="img-responsive" />
                    </div>


                    <div class="col-md-5 col-xs-12">
                        <?php echo "<br>640px X 480Px Image:<br>"; ?>
                        <img src="admin/images/<?php echo $output[$i+1]['stored_image_name']?>" alt="Cinque Terre" class="img-responsive" />
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
</body>
</html>