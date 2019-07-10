<?php
include_once "../config/dbConnect.php";
$images = new Images();
if(isset($_POST['images'])){
    $images->getImages();
}

class Images {
    public function getImages() {
        echo "<pre>";
        $unsuported_file_count = 0;
        $message = '';
        foreach ( $_FILES['fileToUpload']['name'] as $i => $name ) {
            $ImageType = $_FILES['fileToUpload']['type'][$i];
            if($ImageType != 'image/png' & $ImageType != 'image/gif' & $ImageType != 'image/jpeg' & $ImageType != 'image/pjpeg'){
                $unsuported_file_count = 1;
                break;
            }
        }
        if($unsuported_file_count == 1){
            $message = "You are trying to upload a unsupported file. please upload only png, jpeg and gif files";
        }else{
            $this->processImages($_FILES['fileToUpload']);
            $message = "All images uploaded Successfully";
        }
        header("Location: http://localhost/image_pro/index.php?message=$message");
    }

    protected function processImages($images) {
        $db = new DbConnection();
        $output = $db->getResult("SELECT * FROM image");

        //print_r($output);exit;
        $DestinationDirectory	= '../images/'; //Upload Directory ends with / (slash)
        $Quality = 100;
        foreach ( $images['name'] as $i => $name ) {
            $ImageName 		= str_replace(' ','-',strtolower($images['name'][$i]));
            $TempSrc	 	= $images['tmp_name'][$i]; // Tmp name of image file stored in PHP tmp folder
            $ImageType	 	= $images['type'][$i]; //Obtain file type, returns "image/png", image/jpeg, text/plain etc.

            //Let's use $ImageType variable to check wheather uploaded file is supported.
            //We use PHP SWITCH statement to check valid image format, PHP SWITCH is similar to IF/ELSE statements
            //suitable if we want to compare the a variable with many different values
            switch(strtolower($ImageType))
            {
                case 'image/png':
                    $CreatedImage =  imagecreatefrompng($images['tmp_name'][$i]);
                    break;
                case 'image/gif':
                    $CreatedImage =  imagecreatefromgif($images['tmp_name'][$i]);
                    break;
                case 'image/jpeg':
                case 'image/pjpeg':
                    $CreatedImage = imagecreatefromjpeg($images['tmp_name'][$i]);
                    break;
                default:
                    die('Unsupported File!'); //output error and exit
            }

            //Getting Height and width of the current image
            list($CurWidth,$CurHeight)=getimagesize($TempSrc);

            //Construct a new image name (with random number added) for our new image.
            //for 640x480x
            $original_name = $images['name'][$i];
            $NewImageName640 = "640px_480px_"."_".time()."_".$images['name'][$i];
            $DestRandImageName640 = $DestinationDirectory.$NewImageName640; //Name for Big Image
            $this->resizeImage($CurWidth,$CurHeight,640,480,$DestRandImageName640,$CreatedImage,$Quality,$ImageType);
            $query_string = "SELECT MAX(id) as id from image";
            $last_id = $db->getResult($query_string);
            $group_id = $last_id[0]['id'] + 1;

            //for 1280x720x
            $NewImageName1280 = "1280px_720px_"."_".time()."_".$images['name'][$i];
            $DestRandImageName1280 = $DestinationDirectory.$NewImageName1280; //Name for Big Image
            $this->resizeImage($CurWidth,$CurHeight,1280,720,$DestRandImageName1280,$CreatedImage,$Quality,$ImageType);

            $query_string ="INSERT INTO `image` (`image_group_id`, `stored_image_name`, `original_image_name`, `ext`, `resolution`, `created_at`) 
                  VALUES ('$group_id', '$NewImageName640', '$original_name', '$ImageType', '640x480x', current_timestamp()),
                  ('$group_id', '$NewImageName1280', '$original_name', '$ImageType', '1280x720x', current_timestamp())";
            $db->insertQuerty($query_string,'insert');
        }
    }

    // This function will proportionally resize image
    protected function resizeImage($CurWidth,$CurHeight,$width,$height,$DestFolder,$SrcImage,$Quality,$ImageType) {
        //Check Image size is not 0
        if($CurWidth <= 0 || $CurHeight <= 0){
            return false;
        }

        //Construct a proportional size of new image
        $NewWidth  			= $width;
        $NewHeight 			= $height;

        if($CurWidth < $NewWidth || $CurHeight < $NewHeight) {
            $NewWidth = $CurWidth;
            $NewHeight = $CurHeight;
        }
        $NewCanves 	= imagecreatetruecolor($NewWidth, $NewHeight);
        // Resize Image
        if(imagecopyresampled($NewCanves, $SrcImage,0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight)) {
            switch(strtolower($ImageType)) {
                case 'image/png':
                    imagepng($NewCanves,$DestFolder);
                    break;
                case 'image/gif':
                    imagegif($NewCanves,$DestFolder);
                    break;
                case 'image/jpeg':
                case 'image/pjpeg':
                    imagejpeg($NewCanves,$DestFolder,$Quality);
                    break;
                default:
                    return false;
            }
            //Destroy image, frees up memory
            if(is_resource($NewCanves)) {
                imagedestroy($NewCanves);
            }
        }
    }
}
?>