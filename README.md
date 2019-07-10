# Project Description:
  This project allows you to upload the images like JPEG, PNG and GIF to the portal and get the image size 640px X 480px and 1280px X 720px resolution images. The images will be stored in the server and you can view all the images you have uploaded. If the images are no longer needed you can delete those images.
  You can also tag each image in different category which can be used later for categorizing the images.

# Configuration Details:

* Server Configuration:
  > PHP Version >=7.2
  > SQL verison >= 4.9

* Database configuration:
  1. Download the sql file which is present in the repo named: image_pro.sql
  2. export the DB to your mysql server which will create the database structure
  3. update the DB credentails in the file path: admin/config/dbConnect.php
  
* Limitation:
  > Files which are supported for upload are  JPEG, PNG and GIF and any other files uploaded will result in the failure of the upload and message will be showned.

# Input and output Details:
* Input:
    Upload the images files with the format of JPEG, Png and Gif
* Output:
    In view images you will be able to see the 2 images with the resolution 640px X 480px and 1280px X 720px 
