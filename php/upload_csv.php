<?php
include('config.php');

// Check if the form is submitted for file upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // File upload handling
    if (isset($_FILES["csv_file"])) {
        $file = $_FILES["csv_file"]["tmp_name"];
        $handle = fopen($file, "r");

        if ($handle !== false) {
            // Read the header row
            $header = fgetcsv($handle, 1000, ",");

            // Prepare the SQL statement using prepared statements
            $sql = "INSERT INTO studentmarks (" . implode(",", $header) . ") VALUES (" . rtrim(str_repeat("?,", count($header)), ",") . ")";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                // Loop through the data rows
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    // Bind parameters and execute the statement
                    $stmt->bind_param(str_repeat("s", count($header)), ...$data);
                    $stmt->execute();
                }

                // Close the prepared statement
                $stmt->close();

                // Close the file handle
                fclose($handle);

                // Redirect with success message
                $msg = "Marks data updated successfully";
                header("Location: adminportal.php?msg=" . urlencode($msg));
                exit;
            } else {
                // Error in preparing the SQL statement
                $error_msg = "Error preparing SQL statement";
            }
        } else {
            // Error in opening the file
            $error_msg = "Error opening the file";
        }
    } elseif (isset($_FILES["studentdata"])) {
        // Similar handling for student data file upload
        // You can implement it similarly as above
        $file = $_FILES["studentdata"]["tmp_name"];
        $handle = fopen($file, "r");
        $header = fgetcsv($handle, 1000, ",");
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $values = "'" . implode("','", $data) . "'";
            $sql = "INSERT INTO studentdata (" . implode(",", $header) . ") VALUES ($values)";
            $conn->query($sql);
        }
        fclose($handle);
        // echo "File upload successful.";
        $msg = "Student data updated successfully";
        header("Location: adminportal.php#?msg=" . urlencode($msg));

    } else {
        // No file uploaded
        $error_msg = "No file uploaded";
    }

    // Redirect with error message
    header("Location: adminportal.php?error_msg=" . urlencode($error_msg));
    exit;
}

// Close the database connection
$conn->close();
?>

