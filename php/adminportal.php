<?php
include('config.php'); // Include your database connection file


// Function to convert numbers to Roman numerals
function toRoman($number){
    $map = array('M'=>1000,'CM'=>900,'D'=>500,'CD'=>400,'C'=>100,'XC'=>90,'L'=>50,'XL'=>40,'X'=>10,'IX'=>9,'V'=>5,'IV'=>4,'I'=>1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman=>$int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}

$link = ""; // Initialize link variable
$linkText = ""; // Initialize link text variable

if (isset($_POST["year"])) {
    $year = $_POST['year'];
    $semester = $_POST['sem'];
    $regulation = $_POST['regulation'];
    $month_of_result = $_POST['month_of_result'];
    $year_of_result = $_POST['year_of_result'];
    $type_of_exam = $_POST['type_of_exam'];

    // Convert year and semester to Roman numerals
    $romanYear = toRoman($year);
    $romanSemester = toRoman($semester);

    // Generate link text
    $linkText = "$romanYear B.Tech $romanSemester Sem ($regulation) {$type_of_exam} Examinations $month_of_result $year_of_result";

    // Check if the link already exists in the database
    $checkLinkQuery = "SELECT * FROM generated_links WHERE link_text = '$linkText'";
    $checkResult = $conn->query($checkLinkQuery);

    if ($checkResult->num_rows > 0) {
        // If the link already exists, fetch and display the existing link
        $existingLink = $checkResult->fetch_assoc();
        $existingLinkText = $existingLink['link_text'];
        $existingLinkUrl = $existingLink['link_url'];
        
        echo "<div id='generatedLink' style='display:block; text-align:center; margin-bottom:10px;'><a href='$existingLinkUrl' onclick='showUploadForm(\"uploadForm\")' style='color: #060708; text-decoration: none; font-weight: bold;'>$existingLinkText</a></div>";
    } else {
        // If the link does not exist, insert it into the database
        $link = "display_links.php"; // Replace this with your actual link
        $publishDate = date('Y-m-d');

        // Insert the generated link into the database with publish date
        $insertLinkQuery = "INSERT INTO generated_links (link_text, link_url, publish_date) VALUES ('$linkText', '$link', '$publishDate')";
        if ($conn->query($insertLinkQuery) === TRUE) {
            // Echo out the link if insertion is successful (you can store it in the database instead)
            echo "<div id='generatedLink' style='display:block; text-align:center; margin-bottom:10px;'><a href='#' onclick='showUploadForm(\"uploadForm\")' style='color: #060708; text-decoration: none; font-weight: bold;'>$linkText</a></div>";
        } else {
            echo "Error: " . $insertLinkQuery . "<br>" . $conn->error;
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examination Portal - Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admincss.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    

</head>
<body>
    <?php include('topmenu.php'); ?>

    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4 col-xs-12 text-center">
            <h4 id="chagecolor"> <span id="success"> </span></h4>
        </div>
    </div>
    <div class="container">
        <div class="row mt-3 mb-3 p-3 border border-dark border-rounded bg-light">
            <div class="col text-center">
                <h2 class="text-dark">Generate Links and Upload Marks</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-xs-12">  
                <div class="cardhead" id="uploadbtn" >
                    <div class="card-body " >
                        <div class="row text-center">
                            <h2 class="card-title ">Generate Link</h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <label for="year">Year:</label>
                                    <select name="year" id="year" class="form-control" required>
                                        <option value="1">I</option>
                                        <option value="2">II</option>
                                        <option value="3">III</option>
                                        <option value="4">IV</option>
                                    </select><br>
                                    <label for="semester">Semester:</label>
                                    <select name="sem" id="sem" class="form-control" required>
                                        <option value="1">I</option>
                                        <option value="2">II</option>
                                    </select>
                                    <br>
                                    <label for="regulation">Regulation:</label>
                                    <input type="text" name="regulation" id="regulation" class="form-control" required>
                                    <br>
                                    <label for="month_of_result">Month of Result:</label>
                                    <select name="month_of_result" id="month_of_result" class="form-control" required>
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                    </select>
                                    <br>
                                    <label for="year_of_result">Year of Result:</label>
                                    <input type="text" name="year_of_result" id="year_of_result" class="form-control" required>
                                    <br>
                                    <label for="type_of_exam">Examination Type:</label>
                                    <select name="type_of_exam" id="type_of_exam" class="form-control" required>
                                        <option value="Regular">Regular</option>
                                        <option value="Supplementary">Supplementary</option>
                                        <option value="Regular/Supplementary">Regular/Supplementary</option>
                                    </select>
                                    <br>
                                    <div class="text-center">
                                        <input type="submit" name="submit" value="Generate Link">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> 
                </div>    
            </div>
            <div class="col-lg-6 col-xs-12 linkgen">  
                <div class="cardhead"  > 
                    <div class="row linkgen1">
                      <?php echo "<div id='generatedLink' style='display:block; text-align:center; margin-bottom:10px;'><a id='genlink' href='#' onclick='showUploadForm(\"uploadForm\")' style='color: #060708; text-decoration: none; font-weight: bold;'>$linkText</a></div>";?>
                        <div class="alert alert-danger alert-dismissible" style="  margin: 0 1.5rem 1rem 1.5rem;">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Click Link</strong> Click This link to upload Marks of the students using the csv file and links cannot be generated duplicate.
                        </div>
                    </div>
                    <div class="card-body csvform" id="uploadForm" style="display: none;"><!-- Initially Hidden -->
                        <div class="row csvimgupload" style="margin:0;">
                            <img src="../images/upload.png" alt="">
                        </div>
                        <div class="row text-center" style="margin:0;">
                            <h2 class="card-title">Upload CSV file</h2>
                        </div>
                        <div class="row" style="margin:0;">
                            <form action="upload_csv.php" id="upload-form" method="post" enctype="multipart/form-data">
                                <input class="form-control" type="file" name="csv_file" id="csv_file" required>
                                <br>
                                <button type="submit" class="form-control" id="upload-button">Upload</button>

                            </form>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
        <div class="row csvrow ">
            <div class="col-lg-6 col-xs-12 ">  
                <div class="cardhead" id="uploadbtn" >
                    <div class="card-body csvform" >
                        <div class="row text-center">
                            <h2 class="card-title">Upload Student Data</h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="upload_csv.php" id="upload-form" method="post" enctype="multipart/form-data">
                                    <input class="form-control" type="file" name="studentdata" id="studentdata" required>
                                    <br>
                                    <button type="submit" class="form-control" id="upload-button">Upload</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xs-12">
                <div class="cardhead">
                    <div class="card-body csvform">
                        <div class="row">
                            <div class="text-center ">
                                <h2 class="card-title">Student Performance</h2>
                            </div>
                        </div>
                        <div class="row">
                            <label for="rollno">Admission Number</label><br>
                            <div class="col-lg-12">

                                <form action="student.php" method="post" enctype="multipart/form-data">
                                    <input type="text" name="rollno" id="rollno" class="form-control m-2 p-2" required><br>
                                    <button type="submit" class="form-control" id="getdata">Get Data</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <div class="cardlinks mx-auto">
                    <div class="card-body">
                        <div class="row">
                            <div class="text-center">

                                <h2 class="card-title">Uploaded links</h2>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                                include('config.php');
                                // Fetch all the generated links from the database
                                $selectLinksQuery = "SELECT * FROM generated_links ORDER BY id DESC LIMIT 15";                                $result = $conn->query($selectLinksQuery);
    
                                $generatedLinks = array();
    
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $generatedLinks[] = "<a href='#'>" . $row['link_text'] . "</a>";
                                    }
                                }
                            ?>
                            <ul>
                                <?php
                                // Display all the generated links
                                foreach ($generatedLinks as $link) {
                                    echo "<li>$link</li>";
                                }
                                ?>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>

    // Define the showMessage function outside the window.onload event
    function showMessage(param, bgColor) {
        // Retrieving the error message from URL parameters
        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }

        var errorMessage = getParameterByName(param);
        if (errorMessage) {
            var errorElement = document.getElementById('success');
            errorElement.innerText = errorMessage;
            errorElement.style.color = 'white';
            errorElement.style.fontSize = '20px';
            errorElement.style.backgroundColor = bgColor;
            errorElement.style.margin = '10px';
            errorElement.style.padding = '0.1em 1em 0.1em 1em';
            errorElement.style.height = '32px';
            errorElement.style.width = '100px';
            errorElement.style.boxShadow = '0.1em 0.1em 0.2em 0.2em rgba(0, 0, 0, 1)';
            errorElement.style.transition = 'height 0.3s linear, opacity 0.';
            setTimeout(function() {
                errorElement.style.height = '0';
                errorElement.style.opacity = '0';
                setTimeout(function() {
                    errorElement.style.display = 'none';
                }, 500)
            }, 6000);
        }
    }

    // Call the showMessage function outside the window.onload event
    showMessage('msg', 'green'); // Call function to display success message
    showMessage('error_msg','red');
        
    function showUploadForm(formId) {
        var form = document.getElementById(formId);
        if (form) {
            form.style.display = "block"; // Show the form
        }
    }
</script>

</body>
</html>
