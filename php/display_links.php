<?php
include('config.php');

// Fetch all the generated links from the database
$selectLinksQuery = "SELECT * FROM generated_links ORDER BY id DESC";
$result = $conn->query($selectLinksQuery);

$generatedLinks = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Concatenate link text and publish date separately
        $linkText = $row['link_text'];
        $publishDate = $row['publish_date'];
        $linkUrl = $row['link_url'];

        // Create an associative array for each link
        $generatedLinks[] = array(
            'link_text' => $linkText,
            'publish_date' => $publishDate,
            'link_url' => $linkUrl
        );
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Links</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/displaylinks.css">

</head>
<body>
    <div class="container">
        <h2>Result Links</h2>
        <form action="" method="GET" class="mb-3">
                <input type="text" class="form-control" id="searchKeyword" placeholder="Search Link">
        </form>
        <table id="linksTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Results Link </th>
                    <th>Published Date</th>
                </tr>
            </thead>
            <tbody >
                <!-- Table rows will be added dynamically via JavaScript -->
            </tbody>
        </table>
        <!-- Pagination Section -->
        <div id="pagination" class="pagination">
            <!-- Pagination links will be added dynamically via JavaScript -->
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="viewResultsModal" tabindex="-1" role="dialog" aria-labelledby="viewResultsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="viewResultsModalLabel">View Results</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Include the form content here -->
                <form id="resultsForm" method="post" action="php/getresults.php" onsubmit="return validateForm()">                
                    <legend>Get Results</legend>
                    <label class="input-group-prepend" for="rollno">Enter HallTicket Number  </label>
                    <input type="text" name="rollno" class="form-control" placeholder="Hallticket Number" maxlength="10" required>                   
                    <label for="dateofbirth" class="input-group-prepend">Date Of Birth</label>
                    <input type="date" name="dateofbirth" class="form-control" id="dateofbirth" required>
                    
                    <div style="display:nne;">
                        <label for="year">Year:</label>
                        <input type="text" id="year" name="year" class="form-control" required>
                        <label for="semester">Semester:</label>
                        <input type="text" id="semester" name="semester" class="form-control" required>
                        <label for="regulation">Regulation:</label>
                        <input type="text" id="regulation" name="regulation" class="form-control" required>
                        <label for="type">Examination Type:</label>
                        <input type="text" id="type_of_exam" name="type_of_exam" class="form-control" required>
                        <label for="month_of_result">Month of Result:</label>
                        <input type="text" id="month_of_result" name="month_of_result" class="form-control" required>
                        <label for="year_of_result">Year of Result:</label>
                        <input type="text" id="year_of_result" name="year_of_result" class="form-control" required>
                    </div>

                    <div class="text-center">
                        <input class="btn  btn-success" type="submit" value="Get Result">
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>




<script>
    // Sample dynamically generated links array from PHP
    var generatedLinks = [
        <?php
        foreach ($generatedLinks as $link) {
            echo "['" . addslashes($link['link_text']) . "', '" . addslashes($link['publish_date']) . "'],";
        }
        ?>
    ];

   // Function to display links based on search keyword
   function displayFilteredLinks(keyword) {
        var filteredLinks = generatedLinks.filter(function(link) {
            return link[0].toLowerCase().includes(keyword.toLowerCase());
        });

        var linksHtml = '';
        filteredLinks.forEach(function(link) {
            linksHtml += '<tr>';
            linksHtml += '<td><a href="#">' + link[0] + '</a></td>';
            linksHtml += '<td>' + link[1] + '</td>';
            linksHtml += '</tr>';
        });

        document.querySelector('#linksTable tbody').innerHTML = linksHtml;
    }

    // Add event listener to the search input field
    document.querySelector('#searchKeyword').addEventListener('input', function(event) {
        var searchKeyword = event.target.value;
        displayFilteredLinks(searchKeyword);
    });




    // Assuming you have jQuery included
    $(document).ready(function() {
    // Attach click event handler to the parent element (table body) of dynamically generated links
    $('#linksTable').on('click', '.dynamic-link', function(event) {
        event.preventDefault(); // Prevent the default behavior of the link

        // Extract the text content of the clicked link
        var linkText = $(this).text();

        // Extract components using regular expression
        var pattern = /(\w+)\sB\.Tech\s(\w+)\sSem\s\((\w+)\)\s(\w+)\sExaminations\s(\w+)\s(\d{4})/;
        var matches = linkText.match(pattern);

        if (matches && matches.length === 7) {
            var year = romanToNumber(matches[1]); // Extract the year and convert to number
            var semester = romanToNumber(matches[2]); // Extract the semester and convert to number
            var regulation = matches[3]; // Extract the regulation
            var type = matches[4]; // Extract the type
            var month = matches[5]; // Extract the month
            var yearOfExam = matches[6]; // Extract the year of examination

            // Set the values of the form fields
            $('#year').val(year);
            $('#semester').val(semester);
            $('#regulation').val(regulation);
            $('#type_of_exam').val(type);
            $('#month_of_result').val(month);
            $('#year_of_result').val(yearOfExam);

            // Set other form fields similarly

        } else {
            console.log("Text format doesn't match the expected pattern.");
        }
    });

    // Function to convert Roman numerals to numbers
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



    // Number of records per page
    var recordsPerPage = 10;

    // Function to display links for a specific page
    function displayLinks(pageNumber) {
        var startIndex = (pageNumber - 1) * recordsPerPage;
        var endIndex = startIndex + recordsPerPage;
        var linksHtml = '';

        for (var i = startIndex; i < Math.min(endIndex, generatedLinks.length); i++) {
    // Extract the publish date from the generatedLinks array
            var publishDate = new Date(generatedLinks[i][1]);

            // Calculate the difference in milliseconds between the publish date and today's date
            var timeDifference = Date.now() - publishDate.getTime();

            // Convert the difference to days
            var differenceInDays = Math.floor(timeDifference / (1000 * 3600 * 24));

            // Check if the link was published within the last 7 days
            if (differenceInDays <= 7) {
                // Create a new row with a class of dynamic-link
                linksHtml += '<tr class="dynamic-link">';

                // Add the link text in the first cell
                linksHtml += '<td><a href="#"><button type="button" data-toggle="modal" data-target="#viewResultsModal" style="background:none; border:none;">' + generatedLinks[i][0] + '</button></a> <span id="newgif"><img src="images/new.gif" alt="NEW"></span></td>';

                // Add the publish date in the second cell
                linksHtml += '<td>' + generatedLinks[i][1] + '</td>';

                
                // Close the table row
                linksHtml += '</tr>';
            } else {
                // If the link was not published within the last 7 days, add an empty cell
                linksHtml += '<tr class="dynamic-link">';
                linksHtml += '<td><a href="#"><button type="button" data-toggle="modal" data-target="#viewResultsModal" style="background:none; border:none;">' + generatedLinks[i][0] + '</button></a></td>';
                linksHtml += '<td>' + generatedLinks[i][1] + '</td>';
                linksHtml += '<td></td>'; // Empty cell
                linksHtml += '</tr>';
            }
        }
        document.querySelector('#linksTable tbody').innerHTML = linksHtml;
        // Update active class for pagination links
        updatePaginationActiveClass(pageNumber);
    }


    // Function to update active class for pagination links
    function updatePaginationActiveClass(currentPage) {
        var paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(function(link) {
            link.classList.remove('active');
            if (link.textContent == currentPage) {
                link.classList.add('active');
            }
        });
    }

// Function to display pagination links
    function displayPagination(currentPage) {
        var totalPages = Math.ceil(generatedLinks.length / recordsPerPage);
        var paginationHtml = '';
        
        // Calculate the total number of records
        var totalRecords = generatedLinks.length;
    
        // Define how many page numbers to display
        var maxPageNumbers = 4;
        var startPage = 1;
        var endPage = Math.min(totalPages, maxPageNumbers);
    
        if (totalPages > maxPageNumbers) {
            // If total pages exceed the maximum number of pages to display, adjust start and end page numbers
            if (currentPage > Math.floor(maxPageNumbers / 2) + 1) {
                startPage = currentPage - Math.floor(maxPageNumbers / 2);
                endPage = currentPage + Math.floor(maxPageNumbers / 2) - 1;
            }
        
            if (endPage > totalPages) {
                endPage = totalPages;
                startPage = totalPages - maxPageNumbers + 1;
            }
        }
    
        // Previous Page Link
        if (currentPage > 1) {
            paginationHtml += '<a href="#" onclick="displayLinks(' + (currentPage - 1) + ')">Previous</a>';
        }
    
        // Page numbers
        for (var i = startPage; i <= endPage; i++) {
            paginationHtml += '<a href="#" onclick="displayLinks(' + i + ')"' + (i === currentPage ? ' class="active"' : '') + '>' + i + '</a>';
            
        }
            if (currentPage < totalPages) {
            paginationHtml += '<a href="#" onclick="displayLinks(' + (currentPage + 1) + ')">Next</a>';
        }
        // Next Page Link
        
    
        // Display total number of records
        paginationHtml += '<span style="foat:right; font-size:1rem; font-weight:bold;">Total Links are : ' + totalRecords + '</span>';
    
        document.querySelector('#pagination').innerHTML = paginationHtml;
    }

// Initially display pagination links
displayPagination(1);


    // Initially display the first page of links
    displayLinks(1);

    // Initially display pagination links
</script>

</body>
</html>
