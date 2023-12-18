<?php
session_start();

// Unset session variable
session_unset();

// End the session
session_destroy();

// Redirect & Exit
header("Location: login.php");
exit();
?>

<!-- When you logout from the page, it should redirect to the login page -->