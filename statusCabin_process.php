<?php
session_start();

if ($_SESSION['authorization'] == FALSE) {
  // Redirect & Exit
  header("Location: admin/login.php");
  exit();
}
?>

<?php
  if(isset($_POST['cabinID']) && is_numeric($_POST['cabinID'])) {
    $cabinID = $_POST['cabinID'];

    // Attempt connection
    $link = mysqli_connect("localhost", "root", "", "sunnyspot2");

    // Check connection
    if ($link == FALSE) {
        die("Connection error: " . mysqli_connect_error());
    }

    // Get current cabinStatus
    $statusQuery = "SELECT cabinStatus FROM cabin WHERE cabinID = $cabinID";
    $result = mysqli_query($link, $statusQuery);
    $row = mysqli_fetch_assoc($result);
    $currentStatus = $row['cabinStatus'];

    // Switch the current cabinStatus
    $newStatus = ($currentStatus == 'Active') ? 'Inactive' : 'Active';

    // Prepare SQL query to toggle the cabinStatus by ID
    $query = "UPDATE cabin SET cabinStatus = '$newStatus' WHERE cabinID = $cabinID";

    // Run the query
    if(mysqli_query($link, $query)) {
        #echo "Cabin deleted successfully."; //!testing
        header("Location: adminMenu.php");
        exit();
    } else {
        echo "Error updating cabin status: " . mysqli_error($link);
    }

    // Close connection
    mysqli_close($link);
} else {
    echo "Invalid cabin ID.";
}
?>