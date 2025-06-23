<?php 

define("HOSTNAME", "localhost");
define("USERNAME", "root");
define("PASSWORD", "");
define("DATABASE", "architech");

$connection = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);

if(!$connection){
    die("Connection Failed");
} 
// else {
//     echo "Connected";
// }


?>