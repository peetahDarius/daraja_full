<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch</title>
</head>
<body>
<?php

//connect to db
include('./includes/conn.inc.php');


//write sql to fetch data 
$sql = "SELECT * FROM tinypesa ORDER BY  id DESC LIMIT 1";

$result = mysqli_query($conn, $sql);

while($row=mysqli_fetch_array($result))
{
?>
    <p style='color:black;'><?php echo $row['ResultCode']; ?></p>


<?php
}    

?>
</body>
</html>