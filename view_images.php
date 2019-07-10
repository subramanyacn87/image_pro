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
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <?php
                    if(isset($_GET['message'])){
                        ?>
                        <div class="alert alert-success">
                            <strong><?php echo $_GET['message']?></strong>
                        </div>
                        <?php
                    } ?>
                </div>
            </div>
            <?php
            for($i=0;$i < count($output);$i=$i+2){
                ?>
                <div class="row" style="margin-top: 10px">
                    <div class="col-md-7 col-xs-12">
                        <a href="admin/models/image_processor.php?target=delete&id=<?php echo $output[$i]['image_group_id']?>" class="btn btn-primary">
                            Delete following Images</a>
                    </div>
                </div>
                <div class="row p-30">
                    <div class="col-md-7 col-xs-12">
                        <?php echo "Image original Name: ".$output[$i]['original_image_name']."<br>1280px X 720Px Image:";?>
                        <span class="tag label label-info"> #<?php echo $output[$i]['tags'];?><span data-role="remove"></span></span>
                        <img src="admin/images/<?php echo $output[$i]['stored_image_name']?>" alt="Cinque Terre" class="img-responsive" />
                    </div>


                    <div class="col-md-5 col-xs-12">
                        <?php echo "<br>640px X 480Px Image:"; ?>
                        <span class="tag label label-info"> #<?php echo $output[$i]['tags'];?><span data-role="remove"></span></span>
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