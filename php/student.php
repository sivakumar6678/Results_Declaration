<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
        }
        th, td {
            padding: 15px;
        }
        th {
            background-color: #f2f2f2;
        }
        .table-borderless td, .table-borderless th{
            border: none;
            background: none;
            text-align: left;
        }
        .links {
            /* display: inline-block; */
            position: absolute;
            /* top:10rem; */
            /* left:10rem; */
            text-align: center;
            width: 20rem;
            border: 1px solid black;
            border-radius: 5px;
            /* border-top-left-radius: 2.5rem; */
            border-top-right-radius: 2.5rem;
            box-shadow: inset 0 0 5px 1px #000000;
            margin-bottom: 10px;
            background-color: lightgray;
            font-size: 1.5rem;
            font-weight: bold;
            padding:0.5rem;
            margin: 1rem;
        }
        .links a{
            text-decoration: none;
            color: black;
        }
        .container{
            margin: 1rem;
            padding: 1rem;
            border: 1px solid black;
            background-color: white;
            border-radius: 5px;
        }
        .data{
            
            background-color: lightgray;
        }
        .link{
            background-color: lightblue;
        }
        
    </style>
</head>
<body>



<?php
include 'config.php';
include 'topmenu.php';

    // Define calculateGPA function
    function calculateGPA($totalMarks) {
        // Define mapping between total marks and GPA
        $gpaMapping = [
            90 => 4.0,
            80 => 3.5,
            70 => 3.0,
            60 => 2.5,
            50 => 2.0,
            40 => 1.5,
            30 => 1.0,
            0 => 0.0,  // You can adjust this based on your grading system
        ];

        // Find the appropriate GPA based on total marks
        foreach ($gpaMapping as $marks => $gpaValue) {
            if ($totalMarks >= $marks) {
                return $gpaValue;
            }
        }

        return 0.0;  // Default value if no mapping found
    }

if(isset($_POST['rollno'])){
    
    $rollno = $_POST['rollno'];

    // $data = "SELECT * FROM studentdata WHERE REGNO = ?";
    // $stmt1 = $conn->prepare($data);
    // $stmt1->bind_param("s", $rollno);
    // $stmt1->execute();
    // $result1 = $stmt1->get_result();
    // $row1 = $result1->fetch_assoc();

    //to display the links in the left side panel
    $sql = "SELECT DISTINCT YEAR, SEM, EXAMMONTH, EXAMYEAR, EXAMTYPE FROM studentmarks WHERE REGNO = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $rollno);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row2 = $result->fetch_assoc()) {
        $yearSem = $row2['YEAR'] . " - " . $row2['SEM'];
        echo '  <div class="links">';
        echo "      <a href='#$yearSem'>$yearSem sem</a>";
        echo '  </div>';
    }
    echo "<div class='container data'>";
    // if($result1->num_rows > 0){
    //     echo "<div class='container'>";
    //     echo "      <h2>Student Details</h2>";
    //     echo "      <table class='table table-borderless text-left'>";
    //     echo "          <tr><th>Admission No</th><td>".$row1["REGNO"]."</td>";
    //     echo "          <tdhName</td><th>".$row1["NAME"]."</td></tr>";
    //     echo "          <tr><th>Branch</th><td>".$row1["BRANCH"]."</td>";
    //     echo "          <th>Regulation</th><td>".$row1["REGULATION"]."</td></tr>";
    //     echo "          <tr><th>Date of Birth</th><td>".$row1["DOB"]."</td>";
    //     echo "          <th>Year of joining</th><td>".$row1["JOININGYEAR"]."</td></tr>";
    //     echo "      </table>";
    //     echo "</div>";
    // } else {
    //     echo "No results found for the specified criteria.";
    // }

    // Prepare the SQL statement with placeholders for parameters
    $sql = "SELECT DISTINCT YEAR, SEM, EXAMMONTH, EXAMYEAR, EXAMTYPE FROM studentmarks WHERE REGNO = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $rollno);
    $stmt->execute();
    $result = $stmt->get_result();
   
    if ($result->num_rows > 0) {
        echo "<div class='container table-responsive'>";
        echo "      <h2>Student Performance</h2>";

        // Display a table to show student performance for all semesters
        echo "      <table class='table table-bordered '>";
        echo "          <tr><th>Year </th><th>Semester</th><th>Total Marks</th><th>GPA</th><th>Backlogs</th><th>Completion Period</th></tr>";

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            $yearSem = $row['YEAR'] . " - " . $row['SEM'];
            $totalGradePoints = 0;
            $totalCredits = 0;
            $gpa = 0;
            $backlogs = 0;
            $timesToCover = 0;
            $totalMarks = 0;

            // Fetch and display student marks for each combination
            $sql_marks = "SELECT * FROM studentmarks WHERE REGNO = ? AND YEAR = ? AND SEM = ?";
            $stmt_marks = $conn->prepare($sql_marks);
            $stmt_marks->bind_param("sss", $rollno, $row["YEAR"], $row["SEM"]);
            $stmt_marks->execute();
            $result_marks = $stmt_marks->get_result();

            // Output data of each row
            while ($row_marks = $result_marks->fetch_assoc()) {
                // Check if credits are 0 and the result is 'F' to consider for GPA calculation
                if ($row_marks["CREDITS"] == 0 && $row_marks["RESULT"] === "F") {
                    $backlogs++;
                } else if ($row_marks["CREDITS"] > 0 && $row_marks["RESULT"] !== "F") {
                    // Calculate GPA for subjects passed
                    $totalMark = $row_marks["TOTAL"];
                    $totalMarks += $row_marks["TOTAL"];
                    $totalCredits += $row_marks["CREDITS"];
                    $gpa += calculateGPA($totalMark) * $row_marks["CREDITS"];
                }
            }

            // Calculate GPA and times to cover backlogs
            $cgpa = $totalCredits > 0 ? $gpa / $totalCredits : 0;
            if ($backlogs > 0) {
                $timesToCover = ceil($backlogs / 30); // Assuming it takes 3 attempts to clear a backlog
            }

            // Display student performance for each SEMester
            echo "<tr>";
            echo "<td>".$row['YEAR']."</td>";
            echo "<td>".$row['SEM']."</td>";
            echo "<td>".$totalMarks."</td>";
            echo "<td>".number_format($cgpa, 2)."</td>";
            echo "<td>".$backlogs."</td>";
            echo "<td>".$timesToCover."</td>";
            echo "</tr>";

        echo "</table>";
        echo "</div>";

        // Display student marks for all semesters
            $monthYearType = $row['EXAMMONTH'] . ", " . $row['EXAMYEAR'] . ", " . $row['EXAMTYPE'];
            $month_of_exam = $row['EXAMMONTH'];
            $year_of_exam = $row['EXAMYEAR'];
            $type_of_exam = $row['EXAMTYPE'];
            $year = $row['YEAR'];
            $sem = $row['SEM'];
        echo "<div class='container marks' >";
        echo "      <h2 >Student Marks for $monthYearType   $year - $sem sem </h2>";        
        // Output data of each row
            $yearSem = $row['YEAR'] . " - " . $row['SEM'];

            // Fetch and display student marks for each combination
            $sql_marks = "SELECT * FROM studentmarks WHERE REGNO = ? AND YEAR = ? AND SEM = ? AND EXAMMONTH = ? AND EXAMYEAR = ? AND EXAMTYPE = ?";
            $stmt_marks = $conn->prepare($sql_marks);
            $stmt_marks->bind_param("ssssss", $rollno, $row["YEAR"], $row["SEM"], $row["EXAMMONTH"], $row["EXAMYEAR"], $row["EXAMTYPE"]);
            $stmt_marks->execute();
            $result_marks = $stmt_marks->get_result();
            // Display a table to show student marks
            echo "<div id='$yearSem' >";
            echo "  <table  class='table table-bordered table-responsive' >";
            echo "      <tr><th>Subject Code</th><th>Subject Name</th><th>Internal Marks</th><th>External Marks</th><th>Total Marks</th><th>Result Status</th><th>Grade</th><th>Credits</th></tr>";

            // Output data of each row
            while ($row_marks = $result_marks->fetch_assoc()) {
                $yearSem = $row['YEAR'] . " - " . $row['SEM'];
                echo "<tr>";
                echo "<td>".$row_marks["SUBCODE"]."</td>";
                echo "<td>".$row_marks["SUBNAME"]."</td>";
                echo "<td>".$row_marks["IM"]."</td>";
                echo "<td>".$row_marks["EM"]."</td>";
                echo "<td>".$row_marks["TOTAL"]."</td>";
                echo "<td>".$row_marks["RESULT"]."</td>";
                echo "<td>".$row_marks["GRADE"]."</td>";
                echo "<td>".$row_marks["CREDITS"]."</td>";
                echo "</tr>";
            }
            echo "  </table>";
            echo "</div>";
        }
        echo "</div>";

    } else {
        echo "No results found for the specified criteria.";
    }
    echo "</div>";
    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    echo "No results found for the specified criteria.";
    exit;
}
?>

</body>
</html>
