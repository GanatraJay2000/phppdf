<?php
// error_reporting(0);
session_start();

include 'student.php';
include 'functions.php';

$data = get_data();
$students = $data[0];
$subjects = $data[1];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./my_vendors/bootstrap.min.css" />
    <link rel="stylesheet" href="./my_vendors/datatables.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <title>Analysis</title>
    <style>
        .green {
            color: green;
        }

        .red {
            color: red;
        }

        .yellow {
            color: gold;
        }
    </style>
</head>

<body>
    <a href="index.php" class="btn"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
    <div class="container d-flex justify-content-end">
        <a href="analysis.php">Analysis</a>
    </div>
    <div class="container my-5">
        <div id="filters" class="my-5 d-flex justify-content-center align-items-center">
            <select name="remark" id="remark" class="form-control col-3">
                <option value="">All </option>
                <option value="Pass">Pass</option>
                <option value="Fail">Fail</option>
                <option value="Absent">Absent</option>
            </select>
            <div class="mx-5" id="tableInfo"></div>
        </div>
        <?php require './table.php' ?>
    </div>

    <script src="./my_vendors/jquery.min.js"> </script>
    <script src="./my_vendors/bootstrap.min.js"> </script>
    <script src="./my_vendors/datatables.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#myTable').DataTable({
                "pageLength": 50,
            });

            $('#remark').on('change', function() {
                table.search(this.value).draw();
                document.getElementById("tableInfo").innerHTML = table.page.info().recordsDisplay + ' Entries';
            });
        });
    </script>
</body>

</html>