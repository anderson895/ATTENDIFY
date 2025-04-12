<?php

if (isset($_POST["addUnit"])) {
    $unitName = htmlspecialchars(trim($_POST["unitName"]));
    $unitCode = htmlspecialchars(trim($_POST["unitCode"]));
    $courseID = filter_var($_POST["course"], FILTER_VALIDATE_INT);
    $scheduleDay = htmlspecialchars(trim($_POST["scheduleDay"]));
    $startTime = htmlspecialchars(trim($_POST["startTime"]));
    $endTime = htmlspecialchars(trim($_POST["endTime"]));
    $dateRegistered = date("Y-m-d");

    if ($unitName && $unitCode && $courseID && $scheduleDay && $startTime && $endTime) {
        $query = $pdo->prepare("SELECT * FROM tblunit WHERE unitCode = :unitCode");
        $query->bindParam(':unitCode', $unitCode);
        $query->execute();

        if ($query->rowCount() > 0) {
            $_SESSION['message'] = "Subject Already Exists";
        } else {
            $query = $pdo->prepare("INSERT INTO tblunit (name, unitCode, courseID, scheduleDay, startTime, endTime, dateCreated) 
                                    VALUES (:name, :unitCode, :courseID, :scheduleDay, :startTime, :endTime, :dateCreated)");

            $query->bindParam(':name', $unitName);
            $query->bindParam(':unitCode', $unitCode);
            $query->bindParam(':courseID', $courseID);
            $query->bindParam(':scheduleDay', $scheduleDay);
            $query->bindParam(':startTime', $startTime);
            $query->bindParam(':endTime', $endTime);
            $query->bindParam(':dateCreated', $dateRegistered);

            if ($query->execute()) {
                $_SESSION['message'] = "Subject Inserted Successfully";
            } else {
                $_SESSION['message'] = "Failed to Insert Subject";
                print_r($query->errorInfo());
            }
        }
    } else {
        $_SESSION['message'] = "Invalid input for unit";
    }
}

if (isset($_POST["editUnit"])) {
    $unitID = filter_var($_POST["unitID"], FILTER_VALIDATE_INT);
    $unitName = htmlspecialchars(trim($_POST["unitName"]));
    $unitCode = htmlspecialchars(trim($_POST["unitCode"]));
    $courseID = filter_var($_POST["course"], FILTER_VALIDATE_INT);
    $scheduleDay = htmlspecialchars(trim($_POST["scheduleDay"]));
    $startTime = htmlspecialchars(trim($_POST["startTime"]));
    $endTime = htmlspecialchars(trim($_POST["endTime"]));

    if ($unitID && $unitName && $unitCode && $courseID && $scheduleDay && $startTime && $endTime) {
        $query = $pdo->prepare("UPDATE tblunit SET name = :name, unitCode = :unitCode, courseID = :courseID, 
        scheduleDay = :scheduleDay, startTime = :startTime, endTime = :endTime 
        WHERE Id = :unitID");
        $query->bindParam(':name', $unitName);
        $query->bindParam(':unitCode', $unitCode);
        $query->bindParam(':courseID', $courseID);
        $query->bindParam(':scheduleDay', $scheduleDay);
        $query->bindParam(':startTime', $startTime);
        $query->bindParam(':endTime', $endTime);
        $query->bindParam(':unitID', $unitID);

        if ($query->execute()) {
            $_SESSION['message'] = "Subject Updated Successfully";
        } else {
            $_SESSION['message'] = "Failed to Update Subject";
        }
    } else {
        $_SESSION['message'] = "Invalid input for unit";
    }
}

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="resources/images/tablogo.png" type="image/svg+xml">
    <title>ATTENDIFY</title>
    <link rel="stylesheet" href="resources/assets/css/admin_styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
    <style>
        :root {
            /* Light Theme Variables (default) */
            --primary-color: #e50914;
            --secondary-color: #b81d24;
            --accent-color: #f5222d;
            --dark-red: #8c0f13;
            --light-red: #ff4d58;
            
            --background-color: #ffffff;
            --card-bg: #ffffff;
            --light-bg: #f9f9f9;
            
            --text-primary: #333333;
            --text-secondary: #666666;
            --text-muted: #999999;
            
            --border-color: #eeeeee;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --shadow-md: 0 5px 15px rgba(0,0,0,0.07);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
            
            --success-color: #52c41a;
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        /* Dark Theme Variables */
        html[data-theme='dark'] {
            --primary-color: #e50914;
            --secondary-color: #b81d24;
            --accent-color: #f5222d;
            --dark-red: #8c0f13;
            --light-red: #ff4d58;
            
            --background-color: #121212;
            --card-bg: #1e1e1e;
            --light-bg: #252525;
            
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --text-muted: #888888;
            
            --border-color: #333333;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.2);
            --shadow-md: 0 5px 15px rgba(0,0,0,0.3);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.4);
        }

        body {
            background-color: var(--light-bg);
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main--content {
            background-color: var(--light-bg);
            padding: 1.5rem;
            color: var(--text-primary);
        }

        /* Overview Section */
        .overview {
            margin-bottom: 2rem;
        }

        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section--title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            position: relative;
            padding-left: 15px;
        }

        .section--title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .dropdown {
            background-color: var(--card-bg);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            padding: 8px 15px;
            border-radius: 6px;
            outline: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .dropdown:hover {
            border-color: var(--primary-color);
        }

        /* Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            border: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .card--data {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .card--content {
            flex: 1;
        }

        .card--content h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 10px 0 0;
        }

        .card--icon--lg {
            font-size: 2.5rem;
            color: var(--primary-color);
            opacity: 0.8;
        }

        button.add {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
        }

        button.add:hover {
            background-color: var(--dark-red);
            transform: translateY(-2px);
        }

        button.add i {
            font-size: 1.2rem;
        }

        /* Tables */
        .table-container {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            padding: 2.5rem;
            margin: 2rem 0;
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid var(--border-color);
            width: 100%;
            max-width: 100%;
            min-height: 700px;
            display: flex;
            flex-direction: column;
        }

        .table-container:hover {
            box-shadow: var(--shadow-lg);
        }

        .table {
            width: 100%;
            overflow-x: auto;
            max-height: 800px;
            overflow-y: auto;
            flex-grow: 1;
        }

        .table::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table::-webkit-scrollbar-track {
            background: var(--light-bg);
            border-radius: 4px;
        }

        .table::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        .table::-webkit-scrollbar-thumb:hover {
            background: var(--dark-red);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1rem;
            table-layout: fixed;
        }

        thead {
            background-color: var(--light-bg);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        th {
            text-align: left;
            padding: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            vertical-align: middle;
            font-size: 1.05rem;
        }

        tbody tr {
            transition: var(--transition);
        }

        tbody tr:hover {
            background-color: var(--light-bg);
            transform: scale(1.01);
            box-shadow: var(--shadow-sm);
        }

        .ri-edit-line, .ri-delete-bin-line {
            font-size: 1.2rem;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
            transition: var(--transition);
            background-color: var(--primary-color);
            color: white;
        }

        .ri-edit-line::before, .ri-delete-bin-line::before {
            margin-right: 4px;
        }

        .ri-edit-line {
            background-color: #4a6cf7;
        }

        .ri-edit-line:hover {
            background-color: #2d4de8;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(74, 108, 247, 0.3);
        }

        .ri-delete-bin-line {
            background-color: var(--primary-color);
        }

        .ri-delete-bin-line:hover {
            background-color: var(--dark-red);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3);
        }

        /* Add text to buttons */
        .ri-edit-line::after {
            content: 'Edit';
            font-size: 0.9rem;
        }

        .ri-delete-bin-line::after {
            content: 'Delete';
            font-size: 0.9rem;
        }

        /* Update table cell padding for action buttons */
        td:last-child, td:nth-last-child(2) {
            padding: 0.5rem;
        }

        /* Modal Forms */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
            backdrop-filter: blur(5px);
        }

        .formDiv {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: var(--card-bg);
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            width: 90%;
            max-width: 500px;
            animation: fadeIn 0.3s ease;
            border: 1px solid var(--border-color);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, -48%); }
            to { opacity: 1; transform: translate(-50%, -50%); }
        }

        .form-title {
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .form-title p {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            position: relative;
            padding-left: 15px;
        }

        .form-title p::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .close {
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-secondary);
            transition: var(--transition);
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.05);
        }

        .close:hover {
            color: var(--primary-color);
            background: rgba(229, 9, 20, 0.1);
            transform: rotate(90deg);
        }

        form {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        form input[type="text"],
        form input[type="time"],
        form select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--background-color);
            color: var(--text-primary);
            transition: var(--transition);
            font-size: 1rem;
        }

        form input[type="text"]:focus,
        form input[type="time"]:focus,
        form select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
            transform: translateY(-2px);
        }

        form input[type="text"]::placeholder,
        form input[type="time"]::placeholder,
        form select {
            color: var(--text-muted);
        }

        form select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23666666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
            padding-right: 40px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-cancel {
            padding: 10px 20px;
            background-color: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-cancel:hover {
            background-color: var(--light-bg);
            color: var(--text-primary);
        }

        form input[type="submit"] {
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
        }

        form input[type="submit"]:hover {
            background-color: var(--dark-red);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(229, 9, 20, 0.3);
        }

        form input[type="submit"]:active {
            transform: translateY(0);
        }

        form input[type="submit"]::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transform: translateX(-100%);
        }

        form input[type="submit"]:hover::after {
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        /* Theme Toggle */
        .theme-toggle {
            background: rgba(229, 9, 20, 0.1);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            color: var(--primary-color);
            font-size: 1.2rem;
            margin-left: auto;
            margin-right: 10px;
        }

        .theme-toggle:hover {
            background: rgba(229, 9, 20, 0.2);
            transform: rotate(30deg);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .cards {
                grid-template-columns: 1fr;
            }
            
            .table {
                overflow-x: auto;
            }
            
            .formDiv {
                width: 95%;
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <?php include 'includes/topbar.php' ?>
    <section class="main">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main--content">
            <div id="overlay"></div>
            <div class="title">
                <h2 class="section--title">MANAGE SUBJECTS</h2>
                <button id="theme-toggle" class="theme-toggle" title="Toggle dark/light mode">
                    <i class="ri-sun-line" id="theme-icon"></i>
                </button>
            </div>
            
            <div class="overview">
                <div class="cards">
                    <div class="card" id="addUnit">
                        <div class="card--data">
                            <div class="card--content">
                                <button class="add"><i class="ri-add-line"></i>Add Subject</button>
                                <h1><?php total_rows('tblunit') ?></h1>
                                <p>Total Subjects</p>
                            </div>
                            <i class="ri-file-text-line card--icon--lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            <?php showMessage() ?>
            
            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">SUBJECTS</h2>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 10%">Subject Code</th>
                                <th style="width: 15%">Name</th>
                                <th style="width: 15%">Course</th>
                                <th style="width: 10%">Day</th>
                                <th style="width: 10%">Start Time</th>
                                <th style="width: 10%">End Time</th>
                                <th style="width: 10%">Total Professor</th>
                                <th style="width: 10%">Date Created</th>
                                <th style="width: 5%">Edit</th>
                                <th style="width: 5%">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT 
                          c.name AS course_name,
                          u.unitCode AS unit_code,
                          u.name AS unit_name, u.Id as Id,
                          u.scheduleDay AS schedule_day,
                          u.startTime AS start_time,
                          u.endTime AS end_time,
                          u.dateCreated AS date_created,
                          COUNT(s.Id) AS total_professor
                          FROM tblunit u
                          LEFT JOIN tblcourse c ON u.courseID = c.Id
                          LEFT JOIN tblprofessor s ON c.courseCode = s.courseCode
                          GROUP BY u.Id";

                            $result = fetch($sql);
                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowunit{$row["Id"]}'>";
                                    echo "<td>" . $row["unit_code"] . "</td>";
                                    echo "<td>" . $row["unit_name"] . "</td>";
                                    echo "<td>" . $row["course_name"] . "</td>";
                                    echo "<td>" . $row["schedule_day"] . "</td>";
                                    echo "<td>" . $row["start_time"] . "</td>";
                                    echo "<td>" . $row["end_time"] . "</td>";
                                    echo "<td>" . $row["total_professor"] . "</td>";
                                    echo "<td>" . $row["date_created"] . "</td>";
                                    echo "<td><i class='ri-edit-line edit' data-id='{$row["Id"]}' data-name='unit'></i></td>";
                                    echo "<td><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='unit'></i></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='10'>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Subject Form -->
        <div class="formDiv" id="addUnitForm" style="display:none;">
            <form method="POST" action="" name="addUnit" enctype="multipart/form-data">
                <div class="form-title">
                    <p>Add New Subject</p>
                    <span class="close">&times;</span>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="unitName">Subject Name</label>
                        <input type="text" id="unitName" name="unitName" placeholder="Enter subject name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="unitCode">Subject Code</label>
                        <input type="text" id="unitCode" name="unitCode" placeholder="Enter subject code" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="scheduleDay">Schedule Day</label>
                        <select required name="scheduleDay" id="scheduleDay">
                            <option value="" selected>Select Day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="startTime">Start Time</label>
                        <input type="time" id="startTime" name="startTime" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="endTime">End Time</label>
                        <input type="time" id="endTime" name="endTime" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="depthead">Department Head</label>
                        <select required name="depthead" id="depthead">
                            <option value="" selected>Assign Department Head</option>
                            <?php
                            $deptheadNames = getdeptheadNames();
                            foreach ($deptheadNames as $depthead) {
                                echo '<option value="' . $depthead["Id"] . '">' . $depthead["firstName"] . ' ' . $depthead["lastName"]  .  '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="course">Course</label>
                        <select required name="course" id="course">
                            <option value="" selected>Select Course</option>
                            <?php
                            $courseNames = getCourseNames();
                            foreach ($courseNames as $course) {
                                echo '<option value="' . $course["Id"] . '">' . $course["name"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-cancel">Cancel</button>
                    <input type="submit" class="submit" value="Save Subject" name="addUnit">
                </div>
            </form>
        </div>

        <!-- Edit Subject Form -->
        <div class="formDiv" id="editUnitForm" style="display:none;">
            <form method="POST" action="">
                <div class="form-title">
                    <p>Edit Subject</p>
                    <span class="close">&times;</span>
                </div>
                
                <input type="hidden" name="unitID" id="editUnitID">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="editUnitName">Subject Name</label>
                        <input type="text" id="editUnitName" name="unitName" placeholder="Enter subject name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editUnitCode">Subject Code</label>
                        <input type="text" id="editUnitCode" name="unitCode" placeholder="Enter subject code" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editScheduleDay">Schedule Day</label>
                        <select required name="scheduleDay" id="editScheduleDay">
                            <option value="">Select Day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="editStartTime">Start Time</label>
                        <input type="time" id="editStartTime" name="startTime" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editEndTime">End Time</label>
                        <input type="time" id="editEndTime" name="endTime" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editDepthead">Department Head</label>
                        <select required name="depthead" id="editDepthead">
                            <option value="">Assign Department Head</option>
                            <?php
                            $deptheadNames = getdeptheadNames();
                            foreach ($deptheadNames as $depthead) {
                                echo '<option value="' . $depthead["Id"] . '">' . $depthead["firstName"] . ' ' . $depthead["lastName"]  .  '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="editCourse">Course</label>
                        <select name="course" id="editCourse" required>
                            <option value="">Select Course</option>
                            <?php
                            $courseNames = getCourseNames();
                            foreach ($courseNames as $course) {
                                echo '<option value="' . $course["Id"] . '">' . $course["name"] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-cancel">Cancel</button>
                    <input type="submit" class="submit" value="Update Subject" name="editUnit">
                </div>
            </form>
        </div>
    </section>

    <?php js_asset(["delete_unit", "editUnit", "addUnit", "active_link"]) ?>

    <script>
        // Dark mode toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            
            // Check for saved theme preference or use default
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            
            // Update icon based on current theme
            updateThemeIcon(savedTheme);
            
            // Toggle theme when button is clicked
            themeToggle.addEventListener('click', () => {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                
                updateThemeIcon(newTheme);
            });
            
            function updateThemeIcon(theme) {
                if (theme === 'dark') {
                    themeIcon.classList.remove('ri-sun-line');
                    themeIcon.classList.add('ri-moon-line');
                } else {
                    themeIcon.classList.remove('ri-moon-line');
                    themeIcon.classList.add('ri-sun-line');
                }
            }
            
            // Show overlay when forms are displayed
            const formDivs = document.querySelectorAll('.formDiv');
            const overlay = document.getElementById('overlay');
            
            function showOverlay() {
                overlay.style.display = 'block';
            }
            
            function hideOverlay() {
                overlay.style.display = 'none';
            }
            
            // Add click event to close buttons
            const closeBtns = document.querySelectorAll('.close');
            closeBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    formDivs.forEach(div => {
                        div.style.display = 'none';
                    });
                    hideOverlay();
                });
            });
            
            // Close forms when clicking on overlay
            overlay.addEventListener('click', () => {
                formDivs.forEach(div => {
                    div.style.display = 'none';
                });
                hideOverlay();
            });
            
            // Add click event to cancel buttons
            const cancelBtns = document.querySelectorAll('.btn-cancel');
            cancelBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    formDivs.forEach(div => {
                        div.style.display = 'none';
                    });
                    hideOverlay();
                });
            });

            // Function to populate edit form
            function populateEditForm(data) {
                document.getElementById('editUnitID').value = data.Id;
                document.getElementById('editUnitName').value = data.name;
                document.getElementById('editUnitCode').value = data.unitCode;
                document.getElementById('editScheduleDay').value = data.scheduleDay;
                document.getElementById('editStartTime').value = data.startTime;
                document.getElementById('editEndTime').value = data.endTime;
                document.getElementById('editDepthead').value = data.deptheadId;
                document.getElementById('editCourse').value = data.courseID;
            }

            // Add click handler for edit buttons
            const editButtons = document.querySelectorAll('.edit');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const unitId = this.getAttribute('data-id');
                    // Here you would typically fetch the unit data from the server
                    // and then call populateEditForm with the data
                    
                    // Show the form and overlay
                    document.getElementById('editUnitForm').style.display = 'block';
                    document.getElementById('overlay').style.display = 'block';
                });
            });
        });
    </script>
</body>

</html>