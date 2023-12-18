<?php
session_start();

if ($_SESSION['authorization'] == FALSE) {
  // Redirect & Exit
  header("Location: admin/login.php");
  exit();
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sunnyspot Accommodation</title>
    <link href="https://fonts.googleapis.com/css?family=Quando&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
  <header> <a href="index.php"><img src="images/accommodation.png" alt="Accommodation"></a>
    <h1>Create New Cabin</h1>
  </header>

  <hr>

  <a href="adminMenu.php" class='primary-button'>Admin Menu</a><br>

  
  <section class="error">
<?php
  // Check if errors are stored in $_SESSION
  if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];

    // Define error messages
    $errorMessages = [
      "Price Per Night must be greater than 0",
      "Price Per Week must be greater than 0",
      "Price Per Week cannot be greater than 5 times Price Per Night",
      "Price Per Night cannot be greater than Price Per Week",
      "Image is an invalid file type",
      "Image file size is too large (MAX: 2MB)",
      "Image file name already exists",
      "Ensure inputs are not empty"
    ];

    $errorMessage = "The following errors were found. Please correct them before resubmitting:";
    echo "<p class='error-message'>$errorMessage</p>";

    // Loop through the errors and echo corresponding messages
    foreach ($errors as $index => $error) {
      // Check if the error value is 1
      if ($error == 1) {
        $errorMessage = "<p class='error-message'>" . $errorMessages[$index] . "</p>";
        echo $errorMessage;
      }
    }

    // Clear errors from session after displaying
    unset($_SESSION['errors']);
}
?>
  </section>
  <section>
    <form action="insertCabin_process.php" method="post" enctype="multipart/form-data">
        <label for="cabinType">Cabin Type</label>
        <input type="text" id="cabinType" name="cabinType">
        <br>
        <label for="cabinDescription">Cabin Description</label>
        <input type="text" id="cabinDescription" name="cabinDescription">
        <br>
        <label for="pricePerNight">Price Per Night</label>
        <input type="number" min="0" step="any" id="pricePerNight" name="pricePerNight">
        <br>
        <label for="pricePerWeek">Price Per Week</label>
        <input type="number" min="0" step="any" id="pricePerWeek" name="pricePerWeek">
        <br>
        <!-- Image upload input -->
        <label for="photo">Cabin Photo</label>
        <input type="file" id="fileToUpload" name="fileToUpload">
        <br>
        <input type="submit" value="Submit">
    </form>
  </section>
  
  <footer> 
    <a href="admin/logout.php">Log Out</a> 
  </footer>
</body>
</html>