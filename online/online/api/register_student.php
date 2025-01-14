<?php
require('connect.php');

$student_id = $_POST['student_id'];
$name = $_POST['name'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$class= $_POST['class'];
$department=$_POST['department'];
$image = $_FILES['photo']['name'];
$tmp_name = $_FILES['photo']['tmp_name'];
$role = $_POST['role'];

if($password==$cpassword){
    move_uploaded_file($tmp_name, "../uploads/$image");
    $insert = mysqli_query($conn, "INSERT INTO students (student_id,name,password,class,department,photo,role,status,votes) VALUES ('$student_id','$name','$password', '$class', '$department','$image', '$role', 0 , 0 )");
      if($insert){
       echo '
       <script>
       alert("Registartion Successfull!");
       window.location ="../";
       </script>';
      }
      else{
       echo '
       <script>
       alert("Some error occured!");
       window.location ="../routes/register_student.html";
       </script>';
      }
}

else{
    echo '
    <script>
    alert("Password and Confirm password does not match");
    window.location = "../routes/register.html";
    </script>
    ';
  }
?>

    
