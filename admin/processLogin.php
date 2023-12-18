<?php
session_start();

// Set authorization status to false
$_SESSION['authorization'] = FALSE;

// Set authorization-error status to 0
$_SESSION['authorizationErr'] = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['username'], $_POST['password'])) {

        // Get values from $_POST
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Check form input is valid (temp credentials)
        if ($username == 'admin' && $password == 'secure') {
            // Set authorization status to true
            $_SESSION['authorization'] = TRUE;
            // Redirect & Exit
            header("Location: ../adminMenu.php");
            exit();

        // Check form input is valid (DB credentials)
        } else {

            // Attempt connection
            $link = mysqli_connect("localhost", "root", "", "sunnyspot2");

            // Test if connection is successful
            if ($link == FALSE) {
                exit("Connection error: " . mysqli_connect_error());
            }

            // Prepare SQL query to retrieve credentials from 'admin' table
            $query = "SELECT userName, password FROM admin WHERE userName = '$username'";

            // Run the query
            $result = mysqli_query($link, $query);

            // Check if query was successful
            if ($result) {

                // Check if a row is returned
                if (mysqli_num_rows($result) > 0) {

                    // Fetch the row
                    $row = mysqli_fetch_assoc($result);

                    // Verify the password
                    if ($password == $row['password']) {

                        // Set authorization status to true
                        $_SESSION['authorization'] = TRUE;
                        header("Location: ../adminMenu.php");
                        exit();

                    // Else return to login
                    } else {

                        // Set authorization error
                        $_SESSION['authorizationErr'] = 1;
                        
                        // Redirect & Exit
                        header("Location: login.php");
                        exit();
                    }

                } else {

                    // Set authorization error
                    // Check if username is admin (temp credentials) //!testing
                    if ($username == 'admin') {
                        $_SESSION['authorizationErr'] = 1;
                    
                    } else {
                        $_SESSION['authorizationErr'] = 2;
                    }
                    
                    // Redirect & Exit
                    header("Location: login.php");
                    exit();
                }

            } else {
                // Set authorization error
                $_SESSION['authorizationErr'] = 3;

                // Redirect & Exit
                header("Location: login.php");
                exit();
            }

            // Close the database connection
            mysqli_close($link);

        }
    }
}

// Redirect & Exit
header("Location: login.php");
exit();
?>