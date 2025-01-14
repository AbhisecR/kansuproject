<?php
session_start();
require('connect.php');

$student_id = $_POST['student_id'];
$password = $_POST['password'];
$role = $_POST['role'];

// Validate inputs
if (empty($student_id) || empty($password) || empty($role)) {
    echo "<script>alert('Student ID, Password, and Role are required');</script>";
    echo "<script>window.location.href='../routes/login_student.html';</script>";
    exit();
}

// Check for valid student credentials and verification status
$check = mysqli_query($conn, "SELECT * FROM students WHERE student_id='$student_id' AND password='$password' AND role='$role'");

if (mysqli_num_rows($check) > 0) {
    $userdata = mysqli_fetch_array($check);

    // Check if the student is verified
    if ($userdata['verified'] == 0) {
        echo '
            <script>
            alert("Your account is not yet verified by the teacher. Please wait for verification.");
            window.location = "../routes/login_student.html";
            </script>';
        exit();
    }

    // Fetch candidate data
    $candidates = mysqli_query($conn, "SELECT * FROM students WHERE role=2");
    $candidatesdata = mysqli_fetch_all($candidates, MYSQLI_ASSOC);

    // Set session data
    $_SESSION['userdata'] = $userdata;
    $_SESSION['candidatesdata'] = $candidatesdata;

    echo '
        <script>
        window.location = "../routes/dashboard_student.php";
        </script>';
} else {
    echo '
        <script>
        alert("Invalid Credentials or User not found!");
        window.location = "../routes/login_student.html";
        </script>';
}
?>
