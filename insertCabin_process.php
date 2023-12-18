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
  <header> <img src="images/accommodation.png" alt="Accommodation">
    <h1>Administrative Menu</h1>
  </header>

  <hr>

  <a href="adminMenu.php" class='primary-button'>Admin Menu</a>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if all required fields are set
  if (isset($_POST['cabinType'], $_POST['cabinDescription'], $_POST['pricePerNight'], $_POST['pricePerWeek'])) {

    #get values from the form on insertCabin.php
    $cabinType = $_POST['cabinType'];
    $cabinDescription = $_POST['cabinDescription'];
    $pricePerNight = $_POST['pricePerNight'];
    $pricePerWeek = $_POST['pricePerWeek'];
    $pricePerWeek = is_numeric($pricePerWeek) ? (float)$pricePerWeek : 0.0;
    $pricePerNight = is_numeric($pricePerNight) ? (float)$pricePerNight : 0.0;

    //? Error checking (part1)
    $errors = array(0, 0, 0, 0, 0, 0, 0, 0);
    if ($pricePerNight < 1) {
      $errors[0] = 1;
    }
    if ($pricePerWeek < 1) {
      $errors[1] = 1;
    }
    if ($pricePerWeek > (5 * $pricePerNight)) {
      $errors[2] = 1;
    }
    if ($pricePerNight >= $pricePerWeek) {
      $errors[3] = 1;
    }

    if ($cabinType == '' || $cabinDescription == '') { 
      $errors[7] = 1; //? Error checking (part5)
    }


    // Define image variables
    $photo = 'testCabin.jpg'; // default img
    $target_dir = 'images/';
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); 

    // Check if image is valid
    if(isset($_FILES["fileToUpload"]) && 
    $_FILES["fileToUpload"]["error"] == 0) { 
      $allowed_ext = array("jpg" => "image/jpg", 
                          "jpeg" => "image/jpeg", 
                          "gif" => "image/gif", 
                          "png" => "image/png"); 
      $file_name = $_FILES["fileToUpload"]["name"]; 
      $file_type = $_FILES["fileToUpload"]["type"]; 
      $file_size = $_FILES["fileToUpload"]["size"]; 

      // Verify file extension 
      $ext = pathinfo($file_name, PATHINFO_EXTENSION); 

      if (!array_key_exists($ext, $allowed_ext)) { 
        # Error: Please select a valid file format
        $errors[4] = 1; //? Error checking (part2)
      }	 
          
      // Verify file size - 2MB max 
      $maxsize = 2 * 1024 * 1024; 
      
      if ($file_size > $maxsize) { 
        # Error: File size is larger than the allowed limit
        $errors[5] = 1; //? Error checking (part3)
      }	
      
      // Verify MYME type of the file 
      if (in_array($file_type, $allowed_ext)) 
      { 
        // Check whether file exists before uploading it 
        if (file_exists($target_dir . $_FILES["fileToUpload"]["name"])) { 
          # Error: File name already exists
          $errors[6] = 1; //? Error checking (part4)
        } else { 

          // Check error status & attempt upload to directory
          if (array_sum($errors) > 0) {
            // Validation failed, store errors in session
            $_SESSION['errors'] = $errors;
            header("Location: insertCabin.php");
            exit();
          }

          // Attempt upload to directory
          if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], 
          $target_file)) { 
            #echo "The file ". $_FILES["fileToUpload"]["name"]. " has been uploaded."; 
            $photo = $_FILES["fileToUpload"]["name"];
          } 
          else { 
            die("Sorry, there was an error uploading your file."); 
          } 
        } 
      }
    }

    // Check error status & attempt upload to database
    if (array_sum($errors) > 0) {
      // Validation failed, store errors in session
      $_SESSION['errors'] = $errors;
      header("Location: insertCabin.php");
      exit();
    }

    #attempt connection
    $link = mysqli_connect("localhost", "root", "", "sunnyspot2");

    #test if successful
    if ($link == FALSE) {
      exit("Connection error: " . mysqli_connect_error());
      echo "Connection error: " . mysqli_connect_error();
    } else {
      #echo "Connection successful"; //!testing
    }

    #set up the SQL query to insert data into the table
    $query = "INSERT INTO cabin (cabinType, cabinDescription, pricePerNight, pricePerWeek, photo, cabinStatus) 
    VALUES ('$cabinType', '$cabinDescription', '$pricePerNight', '$pricePerWeek', '$photo', 'Active')";

    #run query against database
    if(mysqli_query($link, $query)){
      #echo "Cabin inserted successfully."; //!testing
      header("Location: adminMenu.php");
      exit();
    } else{
      echo "ERROR: Could not able to execute $query. " . mysqli_error($link);
    }

    #close database connection
    mysqli_close($link);
  } else {
    echo "All fields are required.";
  }
} else {
  echo "Invalid request.";
}
?>

  
  <footer> 
    <a href="index.php">Home</a> 
  </footer>
</body>
</html>
