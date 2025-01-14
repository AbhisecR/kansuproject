<?php
session_start();

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../routes/login_teacher.html");
    exit();
}

// Fetch teacher details from the session
$teacher_id = $_SESSION['teacher_id'];
$teacher_name = $_SESSION['teacher_name'];
$teacher_department = $_SESSION['department'];  // Fetch the department from the session
$teacher_photo = $_SESSION['photo'];  // Fetch the photo filename from the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../css/stylesheet.css">
    <style>
        /* Add your custom styles here */
        #Profile { background-color: white; padding: 20px; text-align: left; }
        #Profile { width: 30%; float: left; }
        #headerSection, #menuSection, #bodySection {
            margin: 20px;
        }
        #menuSection ul {
            list-style-type: none;
            padding: 0;
        }
        #menuSection ul li {
            margin: 10px 0;
        }
        img {
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div id="mainSection">
        <center>
            <div id="headerSection">
                <a href="logout_teacher.php"><button id="logoutbtn">Logout</button></a>
                <h1>WELCOME <?php echo htmlspecialchars($teacher_name); ?> </h1>
            </div>
        </center>
        <hr>
        <div id="mainpanel">
            <div id="Profile">
                <?php if (!empty($teacher_photo)): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($teacher_photo); ?>" height="100" width="100"><br><br>
                <?php endif; ?>
                <b>Name:</b> <?php echo htmlspecialchars($teacher_name); ?><br><br>
                <b>Teacher ID:</b> <?php echo htmlspecialchars($teacher_id); ?><br><br>
                
                <!-- Only display department if it's available -->
                <?php if (!empty($teacher_department)): ?>
                    <b>Department:</b> <?php echo htmlspecialchars($teacher_department); ?><br><br>
                <?php endif; ?>
            </div>

            <!-- Menu Section -->
            <div id="menuSection">
                <h3>Teacher Options</h3>
                <ul>
                    <li><a href="verify_voters.php">Verify or Reject Students</a></li>
                    <li><a href="../api/manage_voting.php">Start or Stop Voting</a></li>
                    <li><a href="view_vote_status.php">View Vote Status</a></li>
                    <li><a href="announce_results.php">Announce Results</a></li>
                </ul>
            </div>
            <hr>

            <!-- Remove the Votes Summary Section as it's moved to view_vote_status.php -->
        </div>
    </div>
</body>
</html>
