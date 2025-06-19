<?php
include('connect.php');

$sql = "SELECT concat(id,':',first_name) as code,concat(first_name,' - ',email) as name_th FROM users WHERE department='{$_GET['province_id']}';";
//echo $sql;
$query = mysqli_query($connect, $sql);

$json = array();
while($result = mysqli_fetch_assoc($query)) {    
    array_push($json, $result);
}
echo json_encode($json);