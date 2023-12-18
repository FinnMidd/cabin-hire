<?php
session_start();

if ($_SESSION['authorization'] == FALSE) {
  // Redirect & Exit
  header("Location: admin/login.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['cabinID'], $_POST['cabinType'], $_POST['cabinDescription'], $_POST['pricePerNight'], $_POST['pricePerWeek'])) {
        // Get form data
        $cabinID = $_POST['cabinID'];
        $cabinType = $_POST['cabinType'];
        $cabinDescription = $_POST['cabinDescription'];
        $pricePerNight = $_POST['pricePerNight'];
        $pricePerWeek = $_POST['pricePerWeek'];
        $pricePerWeek = is_numeric($pricePerWeek) ? (float)$pricePerWeek : 0.0;
        $pricePerNight = is_numeric($pricePerNight) ? (float)$pricePerNight : 0.0;
        $photoSubmitted = FALSE;

        $_SESSION['cabin_info'] = [
            'cabinID' => $cabinID,
            'cabinType' => $cabinType,
            'cabinDescription' => $cabinDescription,
            'pricePerNight' => $pricePerNight,
            'pricePerWeek' => $pricePerWeek,
        ];

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

        // Check if image is set & valid
        if(isset($_FILES["fileToUpload"]) && 
		$_FILES["fileToUpload"]["error"] == 0) { 
            // Define variables
            $allowed_ext = array("jpg" => "image/jpg", 
                                "jpeg" => "image/jpeg", 
                                "gif" => "image/gif", 
                                "png" => "image/png"); 
            $file_name = $_FILES["fileToUpload"]["name"]; 
            $file_type = $_FILES["fileToUpload"]["type"]; 
            $file_size = $_FILES["fileToUpload"]["size"]; 

            // Define extra variables
            $photoSubmitted = TRUE; // photo submission status
            $photo = 'testCabin.jpg'; // default image
            $target_dir = 'images/'; // image folder
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); 

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
                        // Store errors in SESSION
                        $_SESSION['update_errors'] = $errors;
                        header("Location: updateCabin.php");
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

        // Check error status & redirect if invalid
        if (array_sum($errors) > 0) {
            // Store errors in SESSION
            $_SESSION['update_errors'] = $errors;
            header("Location: updateCabin.php");
            exit();
        }


        // Attempt connection & upload if valid
        $link = mysqli_connect("localhost", "root", "", "sunnyspot2");

        // Check connection
        if ($link == FALSE) {
            die("Connection error: " . mysqli_connect_error());
        }

        // Prepare SQL query to update cabin in database (Check photo-submission status)
        if ($photoSubmitted == TRUE) {
            $query = "UPDATE cabin SET cabinType='$cabinType', cabinDescription='$cabinDescription', pricePerNight='$pricePerNight', pricePerWeek='$pricePerWeek', photo='$photo' WHERE cabinID=$cabinID";
        } else {
            $query = "UPDATE cabin SET cabinType='$cabinType', cabinDescription='$cabinDescription', pricePerNight='$pricePerNight', pricePerWeek='$pricePerWeek', WHERE cabinID=$cabinID";
        }

        // Run the query
        if (mysqli_query($link, $query)) {
            #echo "Cabin updated successfully."; //!testing
            header("Location: adminMenu.php");
            exit();
        } else {
            echo "Error updating cabin: " . mysqli_error($link);
        }

        // Close connection
        mysqli_close($link);

    } else {
        echo "All fields are required.";
    }

} else {
    echo "Invalid request.";
}
?>
