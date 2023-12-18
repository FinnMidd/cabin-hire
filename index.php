<?php
session_start(); //? Do we need a session function here?
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
    <h1>Sunnyspot Accomodation</h1>
  </header>

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
  $query = "SELECT * FROM cabin WHERE cabinStatus = 'Active'";

  #run query against database and store result 
  $result = mysqli_query($link, $query);

  #check rows of data were returned from database
  if (mysqli_num_rows($result)) {
    #show number of returned rows on screen
    #echo "Returned rows are: " . mysqli_num_rows($result); //!testing
  }

  
  #store result in array
  #create while loop for all cabins
  while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    #store field values in variables
    #words in [] need to match column name in database
    $cabinType = $row['cabinType'];
    $cabinDetails = $row['cabinDescription'];
    $pricePerNight = $row['pricePerNight'];
    $pricePerWeek = $row['pricePerWeek'];
    $cabinPhoto = $row['photo'];
  
  
    #display data on page
    echo "<article>";
    echo "<h2>$cabinType</h2>";
    echo "<img src='images/$cabinPhoto' alt='$cabinType'>";
    echo "<p><span>Details: </span>$cabinDetails</p>";
    echo "<p><span>Price per night: </span>$pricePerNight</p>";
    echo "<p><span>Price per week: </span>$pricePerWeek</p>";
    echo "</article>";
  }

  #free memory related to result
  mysqli_free_result($result);
  #close database connection
  mysqli_close($link);
?>

  </section>
  
  <footer> 
    <a href="admin/login.php">Admin</a> 
  </footer>
</body>
</html>
