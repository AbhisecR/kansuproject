<?php
require('connect.php');

$teacher_id = $_POST['teacher_id'];
$name = $_POST['name'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$department = $_POST['department'];
$image = $_FILES['photo']['name'];
$tmp_name = $_FILES['photo']['tmp_name'];

if ($password == $cpassword) {
    // Upload teacher photo
    move_uploaded_file($tmp_name, "../uploads/$image");

    // Insert teacher into database
    $insert = mysqli_query($conn, "INSERT INTO teachers (teacher_id, name, password, department, photo) 
                                    VALUES ('$teacher_id', '$name', '$password', '$department', '$image')");

    if ($insert) {
        echo '
        <script>
        alert("Registration Successful!");
        window.location ="../";
        </script>';
    } else {
        echo '
        <script>
        alert("Some error occurred!");
        window.location ="../routes/register_teacher.html";
        </script>';
    }
} else {
    echo '
    <script>
    alert("Password and Confirm password do not match");
    window.location = "../routes/register_teacher.html";
    </script>';
}
?>
