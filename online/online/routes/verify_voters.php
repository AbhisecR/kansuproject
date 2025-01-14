<?php
require('../api/connect.php');
session_start();

// Ensure the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../routes/login_teacher.html");
    exit();
}

// Fetch unverified voters
$sql = "SELECT * FROM students WHERE verified = 0";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Voters</title>
    <link rel="stylesheet" href="../css/stylesheet.css">
</head>
<body>
    <div id="headerSection">
        <h1>Teacher Dashboard - Verify Voters</h1>
        <a href="logout_teacher.php"><button id="logoutbtn">Logout</button></a>
    </div>
    <hr>
    <div id="bodySection">
        <h2>Unverified Voters</h2>
        <?php if ($result->num_rows > 0): ?>
            <table border="1">
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Department</th>
                    <th>role</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['student_id']; ?></td> <!-- Display student ID -->
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['class']; ?></td>
                        <td><?php echo $row['department']; ?></td>
                        <td><?php echo $row['role']; ?></td>
                        <td>
                            <form action="../api/process_verification.php" method="POST" style="display: inline;">
                                <input type="hidden" name="student_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="action" value="approve" style="background-color: green; color: white;">Approve</button>
                            </form>
                            <form action="../api/process_verification.php" method="POST" style="display: inline;">
                                <input type="hidden" name="student_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="action" value="reject" style="background-color: red; color: white;">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No unverified voters found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
