<?php
session_start();
session_unset();  // Unsets all session variables
session_destroy();  // Destroys the session

// Redirect to the login page
header("Location:../");
exit();
?>
