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
        
    </style>
</head>
<body>
    
</body>
</html>

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

    $data = "SELECT * FROM studentdata WHERE Admission_No = ?";
    $stmt1 = $conn->prepare($data);
    $stmt1->bind_param("s", $rollno);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $row1 = $result1->fetch_assoc();
    if($result1->num_rows > 0){
        echo "<div class='container'>";
        echo "<h2>Student Details</h2>";
        echo "<table class='table table-striped '>";
        echo "<tr> <td>Admission No</td><td>".$row1["Admission_No"]."</td></tr>";
        echo "<tr> <td>Name</td><td>".$row1["Name"]."</td></tr>";
        echo "<tr><td>Branch</td><td>".$row1["Branch"]."</td></tr>";
        echo "<tr><td>Regulation</td><td>".$row1["Regulation"]."</td></tr>";
        echo "<tr><td>Date of Birth</td><td>".$row1["Date_of_birth"]."</td></tr>";
        echo "<tr><td>Year of joining</td><td>".$row1["year_of_joining"]."</td></tr>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "No results found for the specified criteria.";
    }

    // Prepare the SQL statement with placeholders for parameters
    $sql = "SELECT DISTINCT Year, Sem, ExamMonth, ExamYear, ExamType FROM studentmarks WHERE RegNo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $rollno);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='container table-responsive'>";
        echo "<h2>Student Performance</h2>";

        // Display a table to show student performance for all semesters
        echo "<table class='table table-bordered '>";
        echo "<tr><th>Year </th><th>Semester</th><th>Total Marks</th><th>GPA</th><th>Backlogs</th><th>Completion Period</th></tr>";

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            $yearSem = $row['Year'] . " - " . $row['Sem'];

            // Initialize variables for semester GPA and backlogs calculation
            $totalGradePoints = 0;
            $totalCredits = 0;
            $gpa = 0;
            $backlogs = 0;
            $timesToCover = 0;
            $totalMarks = 0;

            // Fetch and display student marks for each combination
            $sql_marks = "SELECT * FROM studentmarks WHERE RegNo = ? AND Year = ? AND Sem = ?";
            $stmt_marks = $conn->prepare($sql_marks);
            $stmt_marks->bind_param("sss", $rollno, $row["Year"], $row["Sem"]);
            $stmt_marks->execute();
            $result_marks = $stmt_marks->get_result();

            // Output data of each row
            while ($row_marks = $result_marks->fetch_assoc()) {
                // Check if credits are 0 and the result is 'F' to consider for GPA calculation
                if ($row_marks["Credits"] == 0 && $row_marks["Result"] === "F") {
                    $backlogs++;
                } else if ($row_marks["Credits"] > 0 && $row_marks["Result"] !== "F") {
                    // Calculate GPA for subjects passed
                    $totalMark = $row_marks["Total"];
                    $totalMarks += $row_marks["Total"];
                    $totalCredits += $row_marks["Credits"];
                    $gpa += calculateGPA($totalMark) * $row_marks["Credits"];
                }
            }

            // Calculate GPA and times to cover backlogs
            $cgpa = $totalCredits > 0 ? $gpa / $totalCredits : 0;
            if ($backlogs > 0) {
                $timesToCover = ceil($backlogs / 3); // Assuming it takes 3 attempts to clear a backlog
            }

            // Display student performance for each semester
            echo "<tr>";
            echo "<td>".$row['Year']."</td>";
            echo "<td>".$row['Sem']."</td>";
            echo "<td>".$totalMarks."</td>";
            echo "<td>".number_format($cgpa, 2)."</td>";
            echo "<td>".$backlogs."</td>";
            echo "<td>".$timesToCover."</td>";
            echo "</tr>";

        echo "</table>";
        echo "</div>";

        // Display student marks for all semesters
            $monthYearType = $row['ExamMonth'] . ", " . $row['ExamYear'] . ", " . $row['ExamType'];
            $month_of_exam = $row['ExamMonth'];
            $year_of_exam = $row['ExamYear'];
            $type_of_exam = $row['ExamType'];
            $year = $row['Year'];
            $sem = $row['Sem'];
        echo "<div class='container'>";
        echo "<h2>Student Marks for $monthYearType   $year - $sem sem </h2>";        
        // Output data of each row
            $yearSem = $row['Year'] . " - " . $row['Sem'];

            // Fetch and display student marks for each combination
            $sql_marks = "SELECT * FROM studentmarks WHERE RegNo = ? AND Year = ? AND Sem = ? AND ExamMonth = ? AND ExamYear = ? AND ExamType = ?";
            $stmt_marks = $conn->prepare($sql_marks);
            $stmt_marks->bind_param("ssssss", $rollno, $row["Year"], $row["Sem"], $row["ExamMonth"], $row["ExamYear"], $row["ExamType"]);
            $stmt_marks->execute();
            $result_marks = $stmt_marks->get_result();

            // Display a table to show student marks
            echo "<table class='table table-bordered table-responsive'>";
            echo "<tr><th>Subject Code</th><th>Subject Name</th><th>Internal Marks</th><th>External Marks</th><th>Total Marks</th><th>Result Status</th><th>Grade</th><th>Credits</th></tr>";

            // Output data of each row
            while ($row_marks = $result_marks->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row_marks["SubCode"]."</td>";
                echo "<td>".$row_marks["SubName"]."</td>";
                echo "<td>".$row_marks["IM"]."</td>";
                echo "<td>".$row_marks["EM"]."</td>";
                echo "<td>".$row_marks["Total"]."</td>";
                echo "<td>".$row_marks["Result"]."</td>";
                echo "<td>".$row_marks["Grade"]."</td>";
                echo "<td>".$row_marks["Credits"]."</td>";
                echo "</tr>";
            }

            echo "</table>";
        }

        echo "</div>";
    } else {
        echo "No results found for the specified criteria.";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    echo "No results found for the specified criteria.";
    exit;
}
?>
