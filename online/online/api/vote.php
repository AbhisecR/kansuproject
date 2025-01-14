<?php 
session_start();
include("connect.php");

$votes = $_POST['cvotes'];
$total_votes = $votes + 1;
$cid = $_POST['cid'];
$uid = $_SESSION['userdata']['id'];

// Update votes for the candidate
$update_votes = mysqli_query($conn, "UPDATE students SET votes = '$total_votes' WHERE id='$cid' ");
// Update user status to "voted"
$update_user_status = mysqli_query($conn, "UPDATE students SET status=1 WHERE id='$uid' ");

if ($update_votes && $update_user_status) {
    // Fetch updated candidate data
    $candidates = mysqli_query($conn, "SELECT * FROM students WHERE role=2 ");
    $candidatesdata = mysqli_fetch_all($candidates, MYSQLI_ASSOC);
    $_SESSION['userdata']['status'] = 1; // Update session status to "voted"
    $_SESSION['candidatesdata'] = $candidatesdata; // Update session candidates data

    echo '
    <script>
    alert("Voting Successful");
    window.location ="../routes/dashboard_student.php";
    </script>';
} else {
    echo '
    <script>
    alert("Some error occurred!!");
    window.location ="../routes/dashboard_student.php";
    </script>';
}
?>
