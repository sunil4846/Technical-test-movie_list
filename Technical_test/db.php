<?php

$db = new Mysqli;
$db->connect('localhost','root','','Technical_testMovies');

if(!$db){
    echo "Database Not Connected";
}
else{
    // echo "Database connected successfully";
}
?>