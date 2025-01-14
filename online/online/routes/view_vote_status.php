<?php
session_start();

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../routes/login_teacher.html");
    exit();
}

// Database connection
require('../api/connect.php');

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch candidates and their votes
$query = "SELECT student_id, name, votes FROM students WHERE role = 2 ORDER BY votes DESC";
$result = $conn->query($query);

// Check if the query was successful
if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Status</title>
    <link rel="stylesheet" href="../css/stylesheet.css">
</head>
<body>
    <h1>Vote Status</h1>

    <!-- Display vote status -->
    <?php if ($result && $result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Votes</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['votes']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No candidates or votes found.</p>
    <?php endif; ?>

    <a href="dashboard_teacher.php">Back to Dashboard</a>
</body>
</html>
