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
    <h1>Administrative Menu</h1>
  </header>

  <hr>

  <a href="adminMenu2.php" class='primary-button'>Deactivated</a>
  <!-- <a href="#">Active</a> -->
  <a href="insertCabin.php" class='primary-button'>Add Cabin</a>
  <!-- <a href="#">Pending</a> //! Work in process -->

  <section>

<?php
  #attempt connection
  $link = mysqli_connect("localhost", "root", "", "sunnyspot2");

  #test if successful
  if ($link == FALSE) {
    exit("Connection error: " . mysqli_connect_error());
    echo "Connection error: " . mysqli_connect_error();
  } else {
    #echo "Connection successful"; //!testing
  }

  #set up mySQL query statement
  $query = "SELECT * FROM cabin";
  $query= "SELECT * FROM cabin WHERE cabinStatus = 'Active'";


  #run query against database and store result 
  $result = mysqli_query($link, $query);

  #check rows of data were returned from database
  if (mysqli_num_rows($result)) {
    #show number of returned rows on screen
    #echo "Returned rows are: " . mysqli_num_rows($result); //!testing
    echo "<table class='cabin-table'>";
    echo "<tr><th>Cabin Type</th><th>Details</th><th>Price/Night</th><th>Price/Week</th><th>Photo</th><th>Update</th></tr>";
    
    #create while loop for all cabins
    #fetch data and display as table rows
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      #store field values in variables
      #words in [] need to match column name in database
      echo "<tr>";
      echo "<td>" . $row['cabinType'] . "</td>";
      echo "<td>" . $row['cabinDescription'] . "</td>";
      echo "<td>" . $row['pricePerNight'] . "</td>";
      echo "<td>" . $row['pricePerWeek'] . "</td>";
      echo "<td><img src='images/" . $row['photo'] . "' alt='" . $row['cabinType'] . "'></td>";

      #echo update button
      echo "<td><form action='updateCabin.php' method='post'>";
      echo "<input type='hidden' name='cabinID' value='" . $row['cabinID'] . "'>";
      echo "<input type='submit' class='primary-button' value='Update'>";
      echo "</form><br>";
      #echo deactivate button
      echo "<form action='statusCabin_process.php' method='post'>";
      echo "<input type='hidden' name='cabinID' value='" . $row['cabinID'] . "'>";
      echo "<input type='submit' class='primary-button' value='Deactivate'>";
      echo "</form><br>";
    }
    echo "</table>";
  } else {
    echo "No cabins found in the database.";
  }

  #free memory related to result
  mysqli_free_result($result);
  #close database connection
  mysqli_close($link);
?>

  </section>
  
  <footer> 
    <a href="admin/logout.php">Log Out</a>
  </footer>
</body>
</html>
