<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('config.php');

$rollno = $_POST['rollno'];
$year = $_POST['year'];
$semester = $_POST['semester'];
$regulation = $_POST['regulation'];
$month_of_result = $_POST['month_of_result'];
$year_of_result = $_POST['year_of_result'];
$type_of_exam = $_POST['type_of_exam'];
// $dateofbirth = $_POST['dateofbirth'];
function tonumber($number){
    $map = array('I'=> 1,'V'=> 5,'X'=> 10,'L'=> 50,'C'=> 100,'D'=> 500,'M'=> 1000);
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

$yearno = tonumber($year);
$semno = tonumber($semester);


// SQL query to retrieve data for the first table
// $FirstTable = "SELECT REGNO, NAME, BRANCH FROM studentdata WHERE REGNO = ? AND 	DOB = ? ";
// $FirstTable = $conn->prepare($FirstTable);
// $FirstTable->bind_param("ss",$rollno, $dateofbirth);
// $FirstTable->execute();
// $resultTable = $FirstTable->get_result();
// // Check if no results were found in the first table
// if ($resultTable->num_rows === 0) {
//     echo "<script>alert('No results found for Admission No: $_POST[rollno]'); window.location.href = document.referrer;</script>";
//     exit;
// }

// Close the prepared statement for the first table
// $FirstTable->close();

$sqlFirstTable = "SELECT REGNO,YEAR, SEM, BRANCH FROM studentmarks WHERE REGNO = ? AND YEAR = ? AND SEM = ? AND EXAMMONTH  = ? AND EXAMYEAR = ? AND EXAMTYPE = ? ";
$stmtFirstTable = $conn->prepare($sqlFirstTable);
$stmtFirstTable->bind_param("ssssss", $rollno, $year, $semester, $month_of_result, $year_of_result, $type_of_exam);
$stmtFirstTable->execute();
$resultFirstTable = $stmtFirstTable->get_result();

// SQL query to retrieve data for the second table
$sqlSecondTable = "SELECT SUBCODE, SUBNAME, IM, EM,TOTAL, RESULT, GRADE, CREDITS FROM studentmarks WHERE REGNO = ? AND YEAR = ? AND SEM = ? AND EXAMMONTH  = ? AND EXAMYEAR = ? AND EXAMTYPE = ? ";
$stmtSecondTable = $conn->prepare($sqlSecondTable);
$stmtSecondTable->bind_param("ssssss", $rollno, $year, $semester, $month_of_result, $year_of_result, $type_of_exam);
$stmtSecondTable->execute();
$resultSecondTable = $stmtSecondTable->get_result();

// Check if no results were found
if ($resultFirstTable->num_rows === 0) {
    // Redirect back to the previous page with an alert
    // echo "no found";
    echo "<script>alert('No results found for Admission No: $_POST[rollno]'); window.location.href = document.referrer;</script>";
    exit;
}

// Close the prepared statements
$stmtFirstTable->close();
$stmtSecondTable->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="../css/getresults.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" media="print" href="../css/print.css"> <!-- Add a print-specific stylesheet -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <title>Results</title>
    <style>
        /* CSS for the topGeneratedLink container */
        #topGeneratedLink .container{
            margin-top: 20px; /* Add margin at the top to create space */
            box-shadow: 0 0 50px 1px #8fe9f5a9 inset, 0 0  1px black ;            
            padding: 20px; /* Padding around the content */
            margin-bottom: 20px; /* Margin at the bottom to create space */
            border-radius: 8px; /* Rounded corners */
        }

        /* CSS for the heading inside topGeneratedLink */
        #topGeneratedLink h3 {
            color: #333; /* Text color */
            font-size: 24px; /* Font size */
            font-weight: bold; /* Bold font weight */
            margin: 0; /* Remove margin to ensure spacing consistency */
        }

        /* CSS for the container row and column */
        #topGeneratedLink .row {
            display: flex; /* Flexbox layout for alignment */
            justify-content: center; /* Center the content horizontally */
            align-items: center; /* Center the content vertically */
        }

        /* CSS for the column inside the container */
        #topGeneratedLink .col {
            text-align: center; /* Center the text */
        }
 
    </style>

</head>
<body>
    <!-- Your existing body content here -->
    <header class="container-fluid" >
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center" id="collegeheading">
                    <div class="section">
                        <img src="../images/collegelogo.png" alt="JNTUACEK">
                    </div>
                    <div class="section">
                        <h1>JNTUA College of Engineering Kalikiri.</h1>
                        <h1>జవహర్‌లాల్ నెహ్రూ సాంకేతిక విశ్వవిద్యాలయం కలికిరి</h1>
                        <h4>Kalikiri, Annamayya Dist, Andhra Pradesh, India Pin : 517234</h4>
                        <h2 class="text-center">Examination Results</h2>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div id="topGeneratedLink">
        <div class="container">

            <?php
            echo '<div class="row"> <div class="col text-center"><h3>' . $yearno . ' B.Tech ' . $semno . ' Sem ' . $regulation . ' ' . $type_of_exam . ' Examinations ' . $month_of_result . ' ' . $year_of_result . '</h3></div></div>';
            ?>
        </div>
    </div>

    <div class="container-fluid custom-container" id="viewresults">
        <!-- <div class="col-lg-2 col-xs-12"></div> -->
        <div class="col-lg-12 col-xs-12 col-sm-12">
            <div class="card ">

                <div class="card-body">
                    <!-- Table for the first set of columns -->
                    <table class="table table-borderless ">

                        <tbody id="tablefirst">
                            <!-- // Display retrieved data in table rows for the first table -->
                            <!-- if ($row = $resultTable->fetch_assoc()) {
                                echo "<tr>";
                                    echo "<th>Admission No</th>";
                                    echo "<td>" . $row['REGNO'] . "</td></tr>";
                                    echo "<tr><th>Student Name</th>";
                                    echo "<td>" . $row['NAME'] . "</td></tr>";
                                    echo "<tr><th>Branch</th>";
                                    echo "<td>" . $row['BRANCH'] . "</td></tr>";
                                } -->
                                <?php
                            if($row = $resultFirstTable->fetch_assoc() ) {
                                echo "<tr><th style='float:right;'>Admission No</th><td>". $row['REGNO']."</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Table for the second set of columns -->
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Internal Marks</th>
                                <th>External Marks</th>
                                <th>Total Marks</th>
                                <th>Result Status</th>
                                <th>Grade</th>
                                <th>Credits</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Display retrieved data in table rows for the second table
                            while ($row = $resultSecondTable->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['SUBCODE'] . "</td>";
                                echo "<td>" . $row['SUBNAME'] . "</td>";
                                echo "<td>" . $row['IM'] . "</td>";
                                echo "<td>" . $row['EM'] . "</td>";
                                echo "<td>" . ($row['TOTAL']) . "</td>";
                                // Check for 'F' and apply style
                                $resultStatusStyle = ($row['RESULT'] == 'F') ? 'background-color: rgba(232, 9, 9,0.5); font-weight: bold;' : '';
                                echo "<td style='$resultStatusStyle'>" . $row['RESULT'] . "</td>";
                                // echo "<td>" . $row['Result_status'] . "</td>";
                                echo "<td>" . $row['GRADE'] . "</td>";
                                echo "<td>" . $row['CREDITS'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-2"></div>

    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-8 resultdisclimer">
                <b>Result Disclimer:</b> <p>The results published on net are for immediate information to the examinees. These cannot be treated as original mark sheets. Original mark sheets have been issued by the University separately.</p>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="row downloadbtn text-center">
            <div class="col-lg-5"></div>
            <div class="col-lg-2">
                <input type="button" class="btn  btn-info" onclick="window.print()" value="Download">
            </div>
        </div>


    </div>

   
    <script>
    $(document).ready(function() {
        function romanToNumber(roman) {
        var romanNumeralMap = {
            'I': 1,
            'V': 5,
            'X': 10,
            'L': 50,
            'C': 100,
            'D': 500,
            'M': 1000
        };
        var result = 0;
        var prevValue = 0;

        for (var i = roman.length - 1; i >= 0; i--) {
            var value = romanNumeralMap[roman[i]];

            if (value >= prevValue) {
                result += value;
            } else {
                result -= value;
            }

            prevValue = value;
        }

        return result;
    }
});

//         $(document).ready(function() {
//     // Event listener for link clicks
//     $('.result-link').click(function(event) {
//         event.preventDefault();
//         var linkUrl = $(this).attr('href'); // Get the href attribute of the clicked link

//         // Extract the necessary parameters from the linkUrl
//         var params = linkUrl.split('?')[1]; // Assuming the parameters are passed in the query string
//         window.location.href = 'getresults.php?' + params; // Redirect to the new page with parameters
//     });
// });

    </script>
</body>
</html>
