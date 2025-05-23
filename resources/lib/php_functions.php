<?php
function user()
{
    if (isset($_SESSION['user'])) {
        return (object) $_SESSION['user'];
    }
    return null;
}

function getyearlevelNames()
{
    global $pdo;
    $sql = "SELECT * FROM tblyearlevel";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $yearlevelNames = array();
    if ($result) {
        foreach ($result as $row) {
            $yearlevelNames[] = $row;
        }
    }

    return $yearlevelNames;
}
function getdeptheadNames()
{
    global $pdo;
    $sql = "SELECT Id, firstName, lastName FROM tbldepthead";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $deptheadNames = array();
    if ($result) {
        foreach ($result as $row) {
            $deptheadNames[] = $row;
        }
    }

    return $deptheadNames;
}
function getCourseNames()
{
    global $pdo;
    $sql = "SELECT * FROM tblcourse";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $courseNames = array();
    if ($result) {
        foreach ($result as $row) {
            $courseNames[] = $row;
        }
    }

    return $courseNames;
}
function getroomNames()
{
    $sql = "SELECT className FROM tblroom";
    $result =  fetch($sql);

    $roomNames = array();
    if ($result) {
        foreach ($result as $row) {
            $roomNames[] = $row;
        }
    }

    return $roomNames;
}
function getUnitNames()
{
    $sql = "SELECT unitCode,name FROM tblunit";
    $result = fetch($sql);

    $unitNames = array();
    if ($result) {
        foreach ($result as $row) {
            $unitNames[] = $row;
        }
    }

    return $unitNames;
}

function showMessage(): void
{
    if (isset($_SESSION['message'])) {
        echo " <div id='messageDiv' class='messageDiv' >{$_SESSION['message']}</div>";
        echo `<script>
        
         var messageDiv = document.getElementById('messageDiv');
    messageDiv.style.opacity = 1;
    setTimeout(function() {
      messageDiv.style.opacity = 0;
    }, 5000);
        </script>`;

        unset($_SESSION['message']);
    }
}


function total_rows($tablename)
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM {$tablename}");
    $total_rows = $stmt->rowCount();
    echo $total_rows;
}

function fetch($sql)
{
    global $pdo;
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}


function fetchprofessorRecordsFromDatabase($courseCode, $unitCode)
{
    $professorRows = array();

    $query = "SELECT * FROM tblattendance WHERE course = '$courseCode' AND unit = '$unitCode'";
    $result = fetch($query);

    if ($result) {
        foreach ($result as $row) {
            $professorRows[] = $row;
        }
    }

    return $professorRows;
}


function fetchAllProfessorRecordsFromDatabase()
{
    $professorRows = [];

    $query = "SELECT DISTINCT tblattendance.attendanceID, 
                             tblattendance.professorRegistrationNumber, 
                             tblattendance.course, 
                             tblattendance.attendance_timein, 
                             tblattendance.attendance_timeout, 
                             tblattendance.dateMarked, 
                             tblattendance.unit, 
                             tblprofessor.firstName, 
                             tblprofessor.lastName, 
                             tblprofessor.registrationNumber, 
                             tblprofessor.email, 
                             tblprofessor.yearlevel, 
                             tblprofessor.courseCode, 
                             tblprofessor.professorImage, 
                             tblprofessor.dateRegistered, 
                             tblcourse.name AS coursename, 
                             tblunit.name AS unitname
              FROM tblattendance
              LEFT JOIN tblprofessor ON tblprofessor.registrationNumber = tblattendance.professorRegistrationNumber
              LEFT JOIN tblcourse ON tblcourse.courseCode = tblprofessor.courseCode
              LEFT JOIN tblunit ON tblunit.unitCode = tblattendance.unit
              Group by tblattendance.attendanceID
               ORDER BY `dateMarked` DESC
              ";

    // Fetch the results from the database
    $result = fetch($query);

    if (is_array($result)) {
        foreach ($result as $row) {
            $professorRows[] = $row;
        }
    }

    return $professorRows;
}







function js_asset($links = [])
{
    if ($links) {
        foreach ($links as $link) {
            echo "<script src='resources/assets/javascript/{$link}.js'>
        </script>";
        }
    }
}
