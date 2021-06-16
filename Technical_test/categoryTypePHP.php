<?php 
include "db.php";
?>

<?php

// get the type parameter from URL
$type = trim($_POST["type"]);
// echo $type;die;
$sql = "SELECT DISTINCT(value),id FROM category where type = '$type' ORDER BY id asc";
// echo $sql ;die;
$res = $db->query($sql);

$html ="<option value=''>-- Filter By--</option>";  

while($list = $res->fetch_assoc()){ 
    $html .= sprintf("<option value='%s'>%s</option>",$list['value'],$list['value']);
} 

$html = !empty($html) ? $html : "<option value=''>-- Filter By--</option>";

echo $html;

?>