<?php
session_start();
require "../includes/control_access.php";
?>

<html>
<head>
    <title>Sortable Table</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../libs/tabulator/tabulator.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Search and Filter Data</h2>
        <div class="row">
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search...">
            </div>
            <div class="col-md-4">
                <select id="filterColumn" class="form-control">
                    <option value="name">Name</option>
                    <option value="date">Date</option>
                    <option value="member_type">Member Type</option>
                    <option value="cap_id">CAPID</option>
                    <!-- Add more options as needed -->
                </select>
            </div>
            <div class="col-md-4">
                <button onclick="searchData()" class="btn btn-primary">Search</button>
            </div>
        </div>
        <hr>
        <div id="searchResults"></div>
    </div>

    <script src="../libs/tabulator/jquery-3.2.1.js"></script>
    <script src="../libs/tabulator/jquery-ui.js"></script>
    <script src="../libs/tabulator/tabulator.min.js"></script>
    <script>
        // JavaScript function to perform search and display data using Tabulator
        function searchData() {
            const searchInput = document.getElementById("searchInput").value;
            const filterColumn = document.getElementById("filterColumn").value;

            // Define the Tabulator table
            var table = new Tabulator("#searchResults", {
                layout: "fitColumns",
                ajaxURL: "search.php", // Replace with your PHP script
                ajaxParams: {
                    searchInput: searchInput,
                    filterColumn: filterColumn
                },
                columns: [
                    { title: "ID", field: "ID" },
                    { title: "FQSN", field: "FQSN" },
                    { title: "Date", field: "date" },
                    { title: "Name", field: "name" },
                    { title: "Time In", field: "time_in" },
                    { title: "Time Out", field: "time_out" },
                    { title: "Member Type", field: "member_type" },
                    { title: "Visited", field: "visited" },
                    { title: "Phone Number", field: "phone_number" },
                    { title: "Email", field: "email" }
                ],
            });

            // Load data into the Tabulator table
            table.setData();
        }
    </script>
</body>
</html>