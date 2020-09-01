<?php
function get_data()
{
    require('conndb.php');
    if (isset($_FILES['myfile']['name'])) {
        //Check if data present in database
        $filename = $_FILES['myfile']['name'];
        $_SESSION['filename'] = $filename;
        $select = "SELECT * FROM files WHERE filename='$filename'";
        $selecting = $conn->query($select);
        $text = '';
        if ($selecting->num_rows == 1) {
            //Extract data from database            
            $dept = "IT";
            $first = $_POST['start'];
            $no_of_students = $_POST['no_of_students'] - 1;
            while ($row = $selecting->fetch_assoc()) {
                $dbsubjects = $row['subjects'];
                $dbstudents = $row['text'];
                $filename = $row['filename'];
            }
            $_SESSION['filename'] = $filename;
            $dbstudents = json_decode($dbstudents);
            $dbsubjects = json_decode($dbsubjects);
            $data = array($dbstudents, $dbsubjects);
            $_SESSION['data'] = $data;
        } else {
            //Insert into database. 
            $text = extracted_data();
            $dept = "IT";
            $first = $_POST['start'];
            $no_of_students = $_POST['no_of_students'] - 1;
            $dbsubjects = get_subjects($dept, $text);
            $dbstudents = get_students($dept, $text, $first, $no_of_students);
            $subjects = json_encode($dbsubjects);
            $students = json_encode($dbstudents);
            $insert_data = "INSERT INTO files(filename, text, subjects) VALUES ('$filename','$students', '$subjects')";
            $conn->query($insert_data);
            $data = array($dbstudents, $dbsubjects);
            $_SESSION['data'] = $data;
        }
    } elseif (isset($_POST['filename'])) {
        $filename = $_POST['filename'];
        $_SESSION['filename'] = $filename;
        $select = "SELECT * FROM files WHERE filename='$filename';";
        $selecting = $conn->query($select);
        if ($selecting->num_rows > 0) {
            while ($row = $selecting->fetch_assoc()) {
                $dbsubjects = $row['subjects'];
                $dbstudents = $row['text'];
            }
        }
        $dbstudents = json_decode($dbstudents);
        $dbsubjects = json_decode($dbsubjects);
        $data = array($dbstudents, $dbsubjects);
        $_SESSION['data'] = $data;
    } else {
        $filename = $_SESSION['filename'];
        $select = "SELECT * FROM files WHERE filename='$filename';";
        $selecting = $conn->query($select);
        if ($selecting->num_rows > 0) {
            while ($row = $selecting->fetch_assoc()) {
                $dbsubjects = $row['subjects'];
                $dbstudents = $row['text'];
                $filename = $row['filename'];
            }
        }
        $_SESSION['filename'] = $filename;
        $dbstudents = json_decode($dbstudents);
        $dbsubjects = json_decode($dbsubjects);
        $data = array($dbstudents, $dbsubjects);
        $_SESSION['data'] = $data;
    }
    return $data;
}







function extracted_data()
{
    include 'vendor/autoload.php';
    $file = $_FILES['myfile']['tmp_name'];

    $parser = new \Smalot\PdfParser\Parser();
    $pdf    = $parser->parseFile($file);

    $text = $pdf->getText();
    return $text;
}

function get_students($dept, $text, $first, $no_of_students)
{
    // require 'student.php';
    $array = $_POST['notarray'];
    $array =  str_replace(" ,", ",", $array);
    $array =  str_replace(", ", ",", $array);
    $array = explode(",", $array);
    $students = [];
    for ($i = $first; $i <= $first + $no_of_students; $i++) {
        for ($j = 0; $j < count($array); $j++) {
            if ($i == $array[$j])  continue 2;
        }
        $start = strval($i);
        $information = strstr($text, $start);
        $value = strstr($information, $_POST['stop'], true);
        if ($value == '') {
            break;
        }
        // Formatting value
        $value = str_replace(')', ') ', $value); //so that program does not think it is one elem with
        $value = str_replace('(', ' (', $value); //the prev or next elem due to mistyping / error

        $value = str_replace(')', '', $value);
        $value = str_replace('(', '', $value);
        $value = str_replace('|', '', $value);
        $value = str_replace('*', '', $value);
        $value = str_replace('/', '', $value);
        $value = trim(preg_replace('/\t+/', '', $value));
        $value = explode(" ", $value);
        $value = array_map('trim', $value);
        $value = array_filter($value);
        $value = array_values($value);


        $identity = [$value[0], $value[2], $value[3], $value[1], $value[4]];
        $result = [$value[count($value) - 99 + 23], $value[count($value) - 1], $value[count($value) - 2], $value[count($value) - 3]];
        $value = array_slice($value, -94, -3);
        unset($value[18]);
        $value = array_values($value);
        $evaluation = [];
        $j = 0;
        $k = 0;
        foreach ($value as $key => $valueq) {
            $mod = $key % 9;
            $evaluation[$j][$k] = $valueq;
            if ($mod == 8) {
                $j++;
                $k = 0;
            }
            $k++;
        }
        $evaluation = array_values($evaluation);
        foreach ($evaluation as $key => $score) {
            $evaluation[$key] = array_values($evaluation[$key]);
        }

        $details = [$identity, $result, $evaluation];
        $student = new Student($details);
        array_push($students, $student);
    }
    return $students;
}
function get_subjects($dept, $text)
{
    // print_r($students[10]);

    $start = "1." . $dept;
    $sub_text = strstr($text, $start);
    $subjects = strstr($sub_text, $_POST['stop'], true);
    $subjects = trim(preg_replace('/\t+/', '', $subjects));
    $pattern = "/\d\D/";
    $subjects = preg_split($pattern, $subjects);
    $subjects = array_map('trim', $subjects);
    $subjects = array_filter($subjects);
    foreach ($subjects as $key => $object) {
        $res = preg_match("/\w\w\w\d\d/", $object);
        if ($res) {
            unset($subjects[$key]);
        }
    }
    $subjects = array_values($subjects);
    return $subjects;
}

function print_this($data)
{

    foreach ($data as $value) {
        print_r($value);
        echo "<br><br>";
    }
}
