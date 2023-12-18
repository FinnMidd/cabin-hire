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
    <h1>Update Cabin</h1>
  </header>

  <hr>

  <a href="adminMenu.php" class='primary-button'>Admin Menu</a>

  <section>


<?php
  # Define default $photo incase of image error
  $photo = 'testCabin.jpg';

  // Check if errors are stored in $_SESSION & Echo them
  if (isset($_SESSION['update_errors']) && !empty($_SESSION['update_errors'])) {
    $errors = $_SESSION['update_errors'];

    echo "<section class='error'>";

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

    echo "</section>";

    // Clear errors from session after displaying
    unset($_SESSION['update_errors']);
  }

  

  // Check if data is sent via POST method
  if(isset($_POST['cabinID']) && is_numeric($_POST['cabinID'])) {
    $cabinID = $_POST['cabinID'];

    #attempt connection
    $link = mysqli_connect("localhost", "root", "", "sunnyspot2");

    #test if successful
    if ($link == FALSE) {
      exit("Connection error: " . mysqli_connect_error());
      echo "Connection error: " . mysqli_connect_error();
    } else {

      #prepare query for database
      $query = "SELECT * FROM cabin WHERE cabinID = $cabinID";

      #run query and store in $result variable
      $result = mysqli_query($link, $query);

      #check rows of data were returned from database
      if (mysqli_num_rows($result)) {
        #fetch the result as an associative array
        $row = mysqli_fetch_assoc($result);
        #store results in necessary variables
        $cabinType = $row['cabinType'];
        $cabinDescription = $row['cabinDescription'];
        $pricePerNight = $row['pricePerNight'];
        $pricePerWeek = $row['pricePerWeek'];
        $photo = $row['photo']; //TODO FIX $photo variable(use database)
      }

      #echo form & form values
      echo "<form action='updateCabin_process.php' method='post' enctype='multipart/form-data'>";
      #echo cabin photo
      echo "<img src='images/$photo' alt='$cabinDescription'><br>";
      #echo hidden input representing cabinID
      echo "<input type='hidden' id='cabinID' name='cabinID' value='$cabinID'>";
      #echo rest of form data
      echo "<label for='cabinType'>Cabin Type</label>";
      echo "<input type='text' id='cabinType' name='cabinType' value='$cabinType'>";
      echo "<br>";
      #echo cabin description
      echo "<label for='cabinDescription'>Cabin Description</label>";
      echo "<input type='text' id='cabinDescription' name='cabinDescription' value='$cabinDescription'>";
      echo "<br>";
      echo "<label for='pricePerNight'>Price Per Night</label>";
      echo "<input type='number' min='0' step='any' id='pricePerNight' name='pricePerNight' value='$pricePerNight'>";
      echo "<br>";
      echo "<label for='pricePerWeek'>Price Per Week</label>";
      echo "<input type='number' min='0' step='any' id='pricePerWeek' name='pricePerWeek' value='$pricePerWeek'>";
      echo "<br>";
      //Image upload input
      echo "<label for='photo'>Cabin Photo</label>";
      echo "<input type='file' id='fileToUpload' name='fileToUpload' >";
      echo "<br>";
      echo "<input type='submit' value='Submit'>";
      echo "</form>";
    }

    #free memory related to result
    mysqli_free_result($result);
    #close database connection
    mysqli_close($link);

  // Else Check if data is sent via SESSION method
  } elseif (isset($_SESSION['cabin_info']) && !empty($_SESSION['cabin_info'])) {
    // Define variables
    $cabinID = $_SESSION['cabin_info']['cabinID'];
    $cabinType = $_SESSION['cabin_info']['cabinType'];
    $cabinDescription = $_SESSION['cabin_info']['cabinDescription'];
    $pricePerNight = $_SESSION['cabin_info']['pricePerNight'];
    $pricePerWeek = $_SESSION['cabin_info']['pricePerWeek'];
    #$photo = $row['photo']; //TODO FIX $photo variable(use session)

    #attempt connection (to gain $photo data)
    $link = mysqli_connect("localhost", "root", "", "sunnyspot2");

    #test if successful
    if ($link == FALSE) {
      exit("Connection error: " . mysqli_connect_error());
      echo "Connection error: " . mysqli_connect_error();
    } else {

      #prepare query for database
      $query = "SELECT photo FROM cabin WHERE cabinID = $cabinID";

      #run query and store in $result variable
      $result = mysqli_query($link, $query);

      #check if rows of data were returned from the database
      if (mysqli_num_rows($result) > 0) {
        #fetch the result as an associative array
        $row = mysqli_fetch_assoc($result);

        #access the 'photo' value
        $photo = $row['photo'];
      }
    }

    #echo form & form values
    echo "<form action='updateCabin_process.php' method='post' enctype='multipart/form-data'>";
    #echo cabin photo
    echo "<img src='images/$photo' alt='$cabinDescription'><br>";
    #echo hidden input representing cabinID
    echo "<input type='hidden' id='cabinID' name='cabinID' value='$cabinID'>";
    #echo rest of form data
    echo "<label for='cabinType'>Cabin Type</label>";
    echo "<input type='text' id='cabinType' name='cabinType' value='$cabinType'>";
    echo "<br>";
    #echo cabin description
    echo "<label for='cabinDescription'>Cabin Description</label>";
    echo "<input type='text' id='cabinDescription' name='cabinDescription' value='$cabinDescription'>";
    echo "<br>";
    echo "<label for='pricePerNight'>Price Per Night</label>";
    echo "<input type='number' min='0' step='any' id='pricePerNight' name='pricePerNight' value='$pricePerNight'>";
    echo "<br>";
    echo "<label for='pricePerWeek'>Price Per Week</label>";
    echo "<input type='number' min='0' step='any' id='pricePerWeek' name='pricePerWeek' value='$pricePerWeek'>";
    echo "<br>";
    //Image upload input
    echo "<label for='photo'>Cabin Photo</label>";
    echo "<input type='file' id='fileToUpload' name='fileToUpload'>";
    echo "<br>";
    echo "<input type='submit' value='Submit'>";
    echo "</form>";

    // Clear cabin info from session after displaying
    unset($_SESSION['cabin_info']);

    #free memory related to result
    mysqli_free_result($result);
    #close database connection
    mysqli_close($link);

  // Else invalid
  } else {
    echo "Invalid cabin ID.";
  }
?>

  </section>
  
  <footer> 
    <a href="admin/logout.php">Log Out</a>
  </footer>
</body>
</html>
