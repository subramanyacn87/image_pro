<?php
define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_DATABASE", "image_pro");

class DbConnection{

    function __construct()
    {
        $this->con = $this->getDbConnection();
    }

    function getDbConnection(){
        $con=mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        return $con;
    }

    function getResult($query_string){
        $response = array();

        $result=mysqli_query($this->con,$query_string);

        $rowcount=mysqli_num_rows($result);
        for($i=0;$i < $rowcount;$i++){
            array_push($response, mysqli_fetch_array($result, MYSQLI_ASSOC));
        }

        return $response;
    }

    function insertQuerty($query_string,$type = 'insert'){
        $result = mysqli_query($this->con,$query_string);
        if ($result) {
            if($type == 'inset'){
                $response=mysqli_insert_id();
            } else {
                $response=1;
            }
        }else{
            $response=0;
        }
        return $response;
    }
}
?>