<?php
session_start();
session_destroy(); // Destroy all session data
header("Location: ../routes/login_student.html"); // Redirect to student login page
exit();
?>
