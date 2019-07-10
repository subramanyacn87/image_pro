<?php
include_once "../config/dbConnect.php";
$images = new Images();
if(isset($_REQUEST['images'])) {
    $images->getImages();
}elseif(isset($_REQUEST['target'])) {
    if($_REQUEST['target'] == 'delete')
        $images->deleteImages($_REQUEST['id']);
}

class Images {

    function __construct()
    {
        $this->db = new DbConnection();
    }

    public function getImages() {
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
        $output = $this->db->getResult("SELECT * FROM image");
        $tags = $_REQUEST['tags'];

        //print_r($output);exit;
        $DestinationDirectory	= '../images/'; //Upload Directory ends with / (slash)
        $Quality = 100;
        foreach ( $images['name'] as $i => $name ) {
            $ImageName 		= str_replace(' ','-',strtolower($images['name'][$i]));
            $TempSrc	 	= $images['tmp_name'][$i]; // Tmp name of image file stored in PHP tmp folder
            $ImageType	 	= $images['type'][$i]; //Obtain file type, returns "image/png", image/jpeg, text/plain etc.
            $original_image_name = $DestinationDirectory.time().'_'.$ImageName;
            move_uploaded_file($TempSrc, "$original_image_name");

            //Getting Height and width of the current image
            list($CurWidth,$CurHeight)=getimagesize($original_image_name);

            $query_string = "SELECT MAX(id) as id from image";
            $last_id = $this->db->getResult($query_string);
            $group_id = $last_id[0]['id'] + 1;

            //Construct a new image name (with random number added) for our new image.
            //for 640x480x
            $original_name = $images['name'][$i];
            $NewImageName640 = "640px_480px_"."_".time()."_".$images['name'][$i];
            $DestRandImageName640 = $DestinationDirectory.$NewImageName640; //Name for Big Image
            $this->resizeImage($CurWidth,$CurHeight,640,480,$original_image_name,$DestRandImageName640,$Quality,$ImageType);

            //for 1280x720x
            $NewImageName1280 = "1280px_720px_"."_".time()."_".$images['name'][$i];
            $DestRandImageName1280 = $DestinationDirectory.$NewImageName1280; //Name for Big Image
            $this->resizeImage($CurWidth,$CurHeight,1280,720,$original_image_name,$DestRandImageName1280,$Quality,$ImageType);

            $query_string ="INSERT INTO `image` (`image_group_id`, `stored_image_name`, `original_image_name`, `ext`, `tags`, `resolution`, `created_at`) 
                  VALUES ('$group_id', '$NewImageName640', '$original_name', '$ImageType', '$tags', '640x480x', current_timestamp()),
                  ('$group_id', '$NewImageName1280', '$original_name', '$ImageType', '$tags', '1280x720x', current_timestamp())";
            $db->insertQuerty($query_string,'insert');
            unlink($original_image_name);
        }
    }

    // This function will proportionally resize image
    protected function resizeImage($CurWidth,$CurHeight,$width,$height,$SrcImage,$DestFolder,$Quality,$ImageType) {
        //Check Image size is not 0
        if($CurWidth <= 0 || $CurHeight <= 0){
            return false;
        }
        $scale_ratio = $CurWidth / $CurHeight;
        //Construct a proportional size of new image
        if (($width / $height) > $scale_ratio) {
            $width = $height * $scale_ratio;
        } else {
            $height = $width / $scale_ratio;
        }

        $img = "";
        // Resize Image
        switch(strtolower($ImageType)) {
            case 'image/png':
                $img = imagecreatefrompng($SrcImage);
                break;
            case 'image/gif':
                $img = imagecreatefromgif($SrcImage);
                break;
            case 'image/jpeg':
            case 'image/pjpeg':
            $img = imagecreatefromjpeg($SrcImage);
                break;
            default:
                return false;
        }
        $NewCanves 	= imagecreatetruecolor($width, $height);
        imagecopyresampled($NewCanves, $img,0, 0, 0, 0, $width, $height, $CurWidth, $CurHeight);
        imagejpeg($NewCanves, $DestFolder, $Quality);
    }

    public function deleteImages($id){
        $query_string ="SELECT * FROM image WHERE image_group_id = $id";
        $details = $this->db->getResult($query_string);
        foreach($details as $det){
            unlink("../images/".$det['stored_image_name']);
        }
        $query_string ="DELETE FROM image WHERE image_group_id = $id";
        $this->db->insertQuerty($query_string,'insert');

        header("Location: http://localhost/image_pro/view_images.php?message=Image Deleted Successfully");
    }
}
?>