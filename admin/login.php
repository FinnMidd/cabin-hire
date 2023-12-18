<?php
session_start();

if (isset($_SESSION['authorizationErr'])) {

  if ($_SESSION['authorizationErr'] == 0) {
    echo '<script>alert("ERROR: Could not process input. Please try again");</script>';

  } elseif ($_SESSION['authorizationErr'] == 1) {
    echo '<script>alert("ERROR: This password is incorrect");</script>';

  } elseif ($_SESSION['authorizationErr'] == 2) {
    echo '<script>alert("ERROR: This account does not exist");</script>';

  } elseif ($_SESSION['authorizationErr'] == 3) {
    echo '<script>alert("ERROR: Could not query database. Please try again");</script>';
  }

  // Unset session variable
  unset($_SESSION['authorizationErr']);
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sunnyspot Accommodation</title>
    <link href="https://fonts.googleapis.com/css?family=Quando&display=swap" rel="stylesheet">
    <link href="../style.css" rel="stylesheet" type="text/css">
</head>

<body>
  <header> <a href="../index.php"><img src="../images/accommodation.png" alt="Accommodation"></a>
    <h1>Admin Login</h1>
  </header>

  <hr>

  <section>
    <form action="processLogin.php" method="post">
        <label for="username">Username</label>
        <input type="text" id="username" name="username">
        <br>
        <label for="password">Password</label>
        <input type="text" id="password" name="password">
        <br>

        <input type="reset" value="Reset">
        <input type="submit" value="Submit">
    </form>
  </section>
  
  <footer> 
    <a href="../index.php">Home</a> 
  </footer>
</body>
</html>

<!--  Credentials
Username:  admin   
Password: secure    -->

<!-- if you enter the correct credentials i.e. admin name and password,
then you will be logged-in to the “adminMenu.php” page.
Else, you get an error pop-up alert.   -->