<?php
require('connect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = $_POST['teacher_id'];
    $password = $_POST['password'];

    // Check if the teacher is registered
    $check_teacher = "SELECT * FROM teachers WHERE teacher_id = '$teacher_id' AND password = '$password'";
    $result = $conn->query($check_teacher);

    if ($result->num_rows > 0) {
        // Successful login, create session
        $teacher = $result->fetch_assoc();
        $_SESSION['teacher_id'] = $teacher['teacher_id'];
        $_SESSION['teacher_name'] = $teacher['name'];
        $_SESSION['department'] = $teacher['department'];  // Store department in session
        $_SESSION['photo'] = $teacher['photo'];  // Store photo filename in session

        // Redirect to teacher dashboard
        header("Location: ../routes/dashboard_teacher.php");
        exit();
    } else {
        echo '<script>alert("Invalid teacher credentials!"); window.location = "../routes/login_teacher.html";</script>';
    }
}
?>
