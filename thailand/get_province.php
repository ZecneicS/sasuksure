<?php
include('connect.php');
$sql = "SELECT * FROM provinces WHERE moph={$_GET['moph_id']} order by cast(sort as signed) asc;";
$query = mysqli_query($connect, $sql);

$json = array();
while($result = mysqli_fetch_assoc($query)) {    
    array_push($json, $result);
}
echo json_encode($json);