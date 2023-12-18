<?php
session_start();

if ($_SESSION['authorization'] == FALSE) {
  // Redirect & Exit
  header("Location: admin/login.php");
  exit();
}
?>

<?php
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $cabinID = $_GET['id'];

    // Attempt connection
    $link = mysqli_connect("localhost", "root", "", "sunnyspot2");

    // Check connection
    if ($link == FALSE) {
        die("Connection error: " . mysqli_connect_error());
    }

    // Prepare SQL query to delete cabin by ID
    $query = "UPDATE cabin SET cabinStatus = 'Deleted' WHERE cabinID = $cabinID";

    //? Alternative SQL query to delete cabin by ID
    #$query = "DELETE FROM cabin WHERE cabinID = $cabinID";

    // Run the query
    if(mysqli_query($link, $query)) {
        #echo "Cabin deleted successfully."; //!testing
        header("Location: adminMenu2.php");
        exit();
    } else {
        echo "Error deleting cabin: " . mysqli_error($link);
    }

    // Close connection
    mysqli_close($link);
} else {
    echo "Invalid cabin ID.";
}
?>
