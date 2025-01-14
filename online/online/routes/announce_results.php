<?php
session_start();

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../routes/login_teacher.html");
    exit();
}

// Database connection
require('../api/connect.php');

// Initialize the announcement status variable
$announcement_status = null;

// Handle result announcement
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch the winner (highest votes)
    $query = "SELECT name, votes FROM students WHERE role = 2 ORDER BY votes DESC LIMIT 1";
    $result = $conn->query($query);
    $winner = $result->fetch_assoc();

    if ($winner) {
        // Store the result in the results table
        $name = $winner['name'];
        $votes = $winner['votes'];
        $query = "INSERT INTO results (winner_name, total_votes) VALUES ('$name', '$votes')";
        $conn->query($query);
        // Set flag for results announced
        $announcement_status = 1;
    } else {
        $announcement_status = 0;
    }
}

// Fetch results status from the database
$query_status = "SELECT * FROM results ORDER BY announced_at DESC LIMIT 1";
$result_status = $conn->query($query_status);
$final_result = $result_status->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announce Results</title>
    <link rel="stylesheet" href="../css/stylesheet.css">
</head>
<body>
    <h1>Announce Election Results</h1>

    <?php if ($announcement_status == 1): ?>
        <p>Results have already been announced.</p>
    <?php else: ?>
        <form method="POST">
            <button type="submit">Announce Results</button>
        </form>
    <?php endif; ?>

    <!-- Display the announced results if available -->
    <?php if ($final_result): ?>
        <h3>Winner: <?php echo htmlspecialchars($final_result['winner_name']); ?></h3>
        <p>Total Votes: <?php echo htmlspecialchars($final_result['total_votes']); ?></p>
    <?php elseif ($announcement_status == 0 && $_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <p>No candidates found or results could not be announced.</p>
    <?php endif; ?>

    <a href="dashboard_teacher.php">Back to Dashboard</a>
</body>
</html>
