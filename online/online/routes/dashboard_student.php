<?php
session_start();

// Check if the student is logged in
if (!isset($_SESSION['userdata'])) {
    header("location: ../");
}

// Database connection
require('../api/connect.php');

// Fetch the voting session status
$query = "SELECT status FROM voting_session WHERE id = 1";
$result = $conn->query($query);
$voting_status = ($result && $row = $result->fetch_assoc()) ? $row['status'] : 'unknown';

// Fetch the result if it has been announced
$result_query = "SELECT winner_name, total_votes FROM results ORDER BY announced_at DESC LIMIT 1";
$result = $conn->query($result_query);
$final_result = $result->fetch_assoc();

$userdata = $_SESSION['userdata'];
$candidatesdata = $_SESSION['candidatesdata'];

// Check if the user has already voted
$voted_status = $_SESSION['userdata']['status'];  // This is expected to be 0 for not voted, 1 for voted

$status = $voted_status == 0 ? '<b style="color:red">Not Voted</b>' : '<b style="color:green">Voted</b>';
$voting_message = ($voting_status === 'active') 
    ? '<b style="color:green">Voting is Open</b>' 
    : '<b style="color:red">Voting Not Started or Closed</b>';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Online Voting System - Dashboard</title>
    <link rel="stylesheet" href="../css/stylesheet.css">
    <style>
        #Profile, #Candidates { background-color: white; padding: 20px; text-align: left; }
        #Profile { width: 30%; float: left; }
        #Candidates { width: 60%; float: right; }
        #votebtn { padding: 5px; font-size: 15px; background-color: #3498db; color: white; border-radius: 5px; border:none; cursor:pointer;}
        #voted { padding: 5px; font-size: 15px; background-color: green; color: white; border-radius: 5px; border:none; cursor:not-allowed; }
        #votenotallowed { padding: 5px; font-size: 15px; background-color: gray; color: white; border-radius: 5px; border:none; cursor:not-allowed; }
    </style>
</head>
<body>
    <div id="mainSection">
        <center>
            <div id="headerSection">
                <a href="logout_student.php"><button id="logoutbtn">Logout</button></a>
                <h1>Online Voting System</h1>
            </div>
        </center>
        <hr>
        <div id="mainpanel">
            <div id="Profile">
                <img src="../uploads/<?php echo $userdata['photo'] ?>" height="100" width="100"><br><br>
                <b>Name:</b> <?php echo $userdata['name'] ?><br><br>
                <b>Student ID:</b> <?php echo $userdata['student_id'] ?><br><br>
                <b>Class:</b> <?php echo $userdata['class'] ?><br><br>
                <b>Department:</b> <?php echo $userdata['department'] ?><br><br>
                <b>Status:</b> <?php echo $status ?><br><br>
                <b>Voting Status:</b> <?php echo $voting_message; ?><br><br>
            </div>
            <div id="Candidates">
                <h3>Election Results</h3>
                <?php if ($final_result): ?>
                    <p><strong>Winner:</strong> <?php echo $final_result['winner_name']; ?></p>
                    <p><strong>Total Votes:</strong> <?php echo $final_result['total_votes']; ?></p>
                <?php else: ?>
                    <p>No results have been announced yet.</p>
                <?php endif; ?>

                <h3>Available Candidates</h3>
                <?php
                if ($candidatesdata) {
                    foreach ($candidatesdata as $candidate) {
                        ?>
                        <div>
                            <img style="float:right" src="../uploads/<?php echo $candidate['photo'] ?>" height="100" width="100">
                            <b>Candidate Name:</b> <?php echo $candidate['name'] ?><br><br>
                            <b>Student ID:</b> <?php echo $candidate['student_id'] ?><br><br>
                            <b>Class:</b> <?php echo $candidate['class'] ?><br><br>
                            <b>Department:</b> <?php echo $candidate['department'] ?><br><br>
                            <b>Votes:</b> <?php echo $candidate['votes'] ?><br><br>
                            <form action="../api/vote.php" method="POST">
                                <input type="hidden" name="cvotes" value="<?php echo $candidate['votes'] ?>">
                                <input type="hidden" name="cid" value="<?php echo $candidate['id'] ?>">
                                
                                <?php if ($voted_status == 0 && $voting_status === 'active') { ?>
                                    <input type="submit" name="votebtn" value="Vote" id="votebtn">
                                <?php } elseif ($voted_status == 1) { ?>
                                    <button disabled type="button" name="votebtn" value="Vote" id="voted">Voted</button>
                                <?php } else { ?>
                                    <button disabled type="button" id="votenotallowed">Voting Not Allowed</button>
                                <?php } ?>
                            </form>
                        </div>
                        <hr>
                        <?php
                    }
                } else {
                    echo "<b>No candidates found!</b>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
