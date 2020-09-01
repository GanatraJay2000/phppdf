<?php
session_start();
include 'functions.php';
$data = $_SESSION['data'];
$students = $data[0];
foreach ($students as $key => $student) {
    if ($student->identity[0] == $_POST['seat_number']) {
        $students[$key]->identity[1] = $_POST['first_name'];
        $students[$key]->identity[2] = $_POST['father_name'];
        $students[$key]->identity[3] = $_POST['last_name'];
        $students[$key]->identity[4] = $_POST['mother_name'];
        print_this($students[$key]);
    }
}
echo "<br><br>";
foreach ($students as $key => $student) {
    if ($student->identity[0] == $_POST['seat_number']) {
        print_this($students[$key]);
    }
}
