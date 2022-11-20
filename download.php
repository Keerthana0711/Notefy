<?php
$fileID = htmlentities($_GET['fileID']);
$conn = mysqli_connect('localhost','root','','notes');
$fileID = mysqli_real_escape_string($conn, $fileID); // ALWAYS ESCAPE USER INPUT
$query = "SELECT * FROM uploads where id=$fileID";
$result = mysqli_query($conn, $query);
$result_check = mysqli_num_rows($result);
if($result_check > 1 || $result_check < 1){ //If more then 1 result die
    die('inavlid ID');
}
$row = mysqli_fetch_assoc($result);
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=".$row['file']);
header("Content-Type: application/zip");
header("Content-Transfer-Encoding: binary");
echo $row['notes'];
?>