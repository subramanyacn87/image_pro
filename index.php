<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="assets/bootstrap.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div class="container">
            <div id="main-content title-product">
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
                <div class="row" style="margin-top: 100px;">
                    <div class="alert alert-info">
                        <strong >Welcome to Image upload portal</strong>
                    </div>
                    <form action="admin/models/image_processor.php" class="form-horizontal" method="post" enctype="multipart/form-data">
                        <div class="form-group ">
                            <label for="email">Select Images to Upload:</label>
                            <input type="file" class="form-control" name="fileToUpload[]" required multiple>
                        </div>
                        <input type="hidden" name="images" value="yes">
                        <div class="form-group ">
                            <div class="radio">
                                <label><input type="radio" name="tags" value="nature" required>Nature</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="tags" value="festival" required>Festival</label>
                            </div>
                            <div class="radio disabled">
                                <label><input type="radio" name="tags" value="sports" required>Sports</label>
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-xs-12">
                            <button type="submit" class="btn btn-success">Upload Images</button>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xs-12" style="margin-top: 150px;">
                        <a href="view_images.php" class="btn btn-primary col-md-12 col-xs-12">View uploaded Image</a>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="assets/bootstrap.min.js"></script>
    </body>
</html>