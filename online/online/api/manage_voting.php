<?php
session_start();

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../routes/login_teacher.html");
    exit();
}

// Database connection
require('../api/connect.php');

// Initialize message variable
$message = "";

// Handle voting session management
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $status = ($action === 'start') ? 'active' : 'inactive';

    // Update the voting session status
    $query = "UPDATE voting_session SET status = ? WHERE id = 1";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $status);
        if ($stmt->execute()) {
            $message = ($status === 'active') ? "Voting session started." : "Voting session stopped.";
        } else {
            $message = "Failed to execute query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Failed to prepare query: " . $conn->error;
    }
}

// Get current voting status
$query = "SELECT status FROM voting_session WHERE id = 1";
$result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    $voting_status = $row['status'];
} else {
    $voting_status = "unknown";
    $message = "Failed to retrieve voting status.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Voting</title>
    <link rel="stylesheet" href="../css/stylesheet.css">
</head>
<body>
    <h1>Manage Voting Process</h1>
    <p>Current Status: <strong><?php echo htmlspecialchars($voting_status); ?></strong></p>

    <form method="POST">
        <?php if ($voting_status === 'inactive' || $voting_status === 'unknown'): ?>
            <button type="submit" name="action" value="start">Start Voting</button>
        <?php else: ?>
            <button type="submit" name="action" value="stop">Stop Voting</button>
        <?php endif; ?>
    </form>

    <p><?php echo htmlspecialchars($message); ?></p>
    <a href="../routes/dashboard_teacher.php">Back to Dashboard</a>
</body>
</html>
