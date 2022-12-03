<?php
//mysql://b826795c9dae48:bf67164f@us-cdbr-east-06.cleardb.net/heroku_f7bca4734b58379?reconnect=true
//b882aadfdb7fdf:b32701d8@us-cdbr-east-06.cleardb.net/heroku_0d2f6f079e14839?reconnect=true
$servername = "us-cdbr-east-06.cleardb.net";
$username = "b826795c9dae48";
$password = "bf67164f";
$database = "heroku_f7bca4734b58379";
global $connect;
$connect = new mysqli($servername, $username, $password, $database);
$connect->set_charset("utf8mb4");