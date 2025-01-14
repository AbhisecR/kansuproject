<?php
require('connect.php');
session_start();

// Ensure the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../routes/login_teacher.html");
    exit();
}

// Check if the action is either approve or reject
if (isset($_POST['action']) && isset($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        // Approve: Update verified status to 1
        $sql = "UPDATE students SET verified = 1 WHERE id = ?";
    } elseif ($action == 'reject') {
        // Reject: Delete the student row from the database
        $sql = "DELETE FROM students WHERE id = ?";
    } else {
        echo "<script>alert('Invalid action.'); window.location.href = '../routes/verify_voters.php';</script>";
        exit();
    }

    // Prepare and execute the query
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $student_id);
        if ($stmt->execute()) {
            // Successful action
            $message = $action == 'approve' ? 'Student approved successfully!' : 'Student rejected and removed successfully!';
            echo "<script>alert('$message'); window.location.href = '../routes/verify_voters.php';</script>";
        } else {
            echo "<script>alert('Error processing action.'); window.location.href = '../routes/verify_voters.php';</script>";
        }
    } else {
        echo "<script>alert('SQL Error.'); window.location.href = '../routes/verify_voters.php';</script>";
    }
}
?>
