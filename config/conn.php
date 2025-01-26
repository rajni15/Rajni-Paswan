<?php
$servername="localhost";
$username="root";
$Password="";
$database="testing";
$conn=new mysqli($servername,$username,$Password,$database);

if($conn->connect_error){
   die("Connection Failed".$conn->connect_error);
}

// $sql="SELECT * FROM student";
// $results=$conn->query($sql);
// if($results->num_rows>0){
//     while($row=$results->fetch_assoc()){
//           var_dump($row);
//     }
// }else{
//     echo "No result found.";
// }
// $conn->close();
?>