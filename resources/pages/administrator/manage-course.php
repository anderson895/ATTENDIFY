<?php
if (isset($_POST["addCourse"])) {
    $courseName = htmlspecialchars(trim($_POST["courseName"]));
    $courseCode = htmlspecialchars(trim($_POST["courseCode"]));
    $yearlevelID = filter_var($_POST["yearlevel"], FILTER_VALIDATE_INT);
    $dateRegistered = date("Y-m-d");

    if ($courseName && $courseCode && $yearlevelID) {
        $query = $pdo->prepare("SELECT * FROM tblcourse WHERE courseCode = :courseCode");
        $query->bindParam(':courseCode', $courseCode);
        $query->execute();

        if ($query->rowCount() > 0) {
            $_SESSION['message'] = "Course Already Exists";
        } else {
            $query = $pdo->prepare("INSERT INTO tblcourse (name, courseCode, yearlevelID, dateCreated) 
                                     VALUES (:name, :courseCode, :yearlevelID, :dateCreated)");
            $query->bindParam(':name', $courseName);
            $query->bindParam(':courseCode', $courseCode);
            $query->bindParam(':yearlevelID', $yearlevelID);
            $query->bindParam(':dateCreated', $dateRegistered);
            $query->execute();

            $_SESSION['message'] = "Course Inserted Successfully";
        }
    } else {
        $_SESSION['message'] = "Invalid input for course";
    }
}

if (isset($_POST["addyearlevel"])) {
    $yearlevelName = htmlspecialchars(trim($_POST["yearlevelName"]));
    $yearlevelCode = htmlspecialchars(trim($_POST["yearlevelCode"]));
    $dateRegistered = date("Y-m-d");

    if ($yearlevelName && $yearlevelCode) {
        $query = $pdo->prepare("SELECT * FROM tblyearlevel WHERE yearlevelCode = :yearlevelCode");
        $query->bindParam(':yearlevelCode', $yearlevelCode);
        $query->execute();

        if ($query->rowCount() > 0) {
            $_SESSION['message'] = "Year Level Already Exists";
        } else {
            $query = $pdo->prepare("INSERT INTO tblyearlevel (yearlevelName, yearlevelCode, dateRegistered) 
                                     VALUES (:yearlevelName, :yearlevelCode, :dateRegistered)");
            $query->bindParam(':yearlevelName', $yearlevelName);
            $query->bindParam(':yearlevelCode', $yearlevelCode);
            $query->bindParam(':dateRegistered', $dateRegistered);
            $query->execute();

            $_SESSION['message'] = "Year Level Inserted Successfully";
        }
    } else {
        $_SESSION['message'] = "Invalid input for year level";
    }
}

if (isset($_POST["editCourse"])) {
    $courseID = filter_var($_POST["courseID"], FILTER_VALIDATE_INT);
    $courseName = htmlspecialchars(trim($_POST["courseName"]));
    $courseCode = htmlspecialchars(trim($_POST["courseCode"]));
    $yearlevelID = filter_var($_POST["yearlevel"], FILTER_VALIDATE_INT);

    if ($courseID && $courseName && $courseCode && $yearlevelID) {
        $query = $pdo->prepare("UPDATE tblcourse SET name = :name, courseCode = :courseCode, yearlevelID = :yearlevelID WHERE Id = :courseID");
        $query->bindParam(':name', $courseName);
        $query->bindParam(':courseCode', $courseCode);
        $query->bindParam(':yearlevelID', $yearlevelID);
        $query->bindParam(':courseID', $courseID);
        $query->execute();

        $_SESSION['message'] = "Course Updated Successfully";
    } else {
        $_SESSION['message'] = "Invalid input for course";
    }
}

if (isset($_POST["editYearLevel"])) {
    $yearlevelID = filter_var($_POST["yearlevelID"], FILTER_VALIDATE_INT);
    $yearlevelName = htmlspecialchars(trim($_POST["yearlevelName"]));
    $yearlevelCode = htmlspecialchars(trim($_POST["yearlevelCode"]));

    if ($yearlevelID && $yearlevelName && $yearlevelCode) {
        $query = $pdo->prepare("UPDATE tblyearlevel SET yearlevelName = :yearlevelName, yearlevelCode = :yearlevelCode WHERE Id = :yearlevelID");
        $query->bindParam(':yearlevelName', $yearlevelName);
        $query->bindParam(':yearlevelCode', $yearlevelCode);
        $query->bindParam(':yearlevelID', $yearlevelID);
        $query->execute();

        $_SESSION['message'] = "Year Level Updated Successfully";
    } else {
        $_SESSION['message'] = "Invalid input for year level";
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

        .main {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .main--content {
            background-color: var(--light-bg);
            padding: 1.5rem;
            color: var(--text-primary);
            flex: 1;
            overflow-y: auto;
            height: calc(100vh - 60px); /* Adjust based on your topbar height */
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
            padding: 1.5rem;
            margin-bottom: 2rem;
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid var(--border-color);
            height: calc(100vh - 350px); /* Adjust this value based on your needs */
            display: flex;
            flex-direction: column;
        }

        .table-container .title {
            margin-bottom: 1rem;
            flex-shrink: 0;
        }

        .table {
            width: 100%;
            overflow: auto;
            flex: 1;
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
            border-collapse: collapse;
            margin-top: 0;
        }

        thead {
            background-color: var(--light-bg);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        th {
            text-align: left;
            padding: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 2px solid var(--border-color);
            background-color: var(--light-bg);
            white-space: nowrap;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
        }

        tbody tr {
            transition: var(--transition);
            position: relative;
            cursor: pointer;
        }

        tbody tr:hover {
            background-color: var(--light-bg);
            transform: translateY(-4px) scale(1.01);
            box-shadow: var(--shadow-md);
            z-index: 1;
        }

        tbody tr:hover td {
            color: var(--text-primary);
            transform: translateX(5px);
            transition-delay: calc(0.1s * var(--td-index));
        }

        tbody tr:hover td:first-child {
            position: relative;
        }

        tbody tr:hover td:first-child::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary-color);
            border-radius: 0 2px 2px 0;
            animation: slideIn 0.3s ease forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
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
            transform: translate(-50%, -50%) scale(0.9);
            background-color: var(--card-bg);
            padding: 0;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            width: 90%;
            max-width: 700px;
            border: 1px solid var(--border-color);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .formDiv.active {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .form-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-header h3 i {
            opacity: 0;
            transform: translateX(-10px);
            animation: slideInRight 0.5s 0.2s forwards;
        }

        .form-content {
            padding: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeUp 0.5s forwards;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }
        .form-group:nth-child(6) { animation-delay: 0.6s; }

        .form-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--background-color);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
            transform: translateY(-2px);
        }

        .form-footer {
            padding: 1.5rem 2rem;
            background-color: var(--light-bg);
            border-top: 1px solid var(--border-color);
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .btn-cancel {
            background-color: transparent;
            border: 1px solid var(--border-color);
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-secondary);
        }

        .btn-cancel:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background-color: var(--dark-red);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(229, 9, 20, 0.2);
        }

        .btn-submit i {
            margin-right: 5px;
            transition: transform 0.3s;
        }

        .btn-submit:hover i {
            transform: translateX(3px);
        }

        .close {
            font-size: 1.5rem;
            cursor: pointer;
            color: white;
            transition: var(--transition);
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        /* Animation keyframes */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Message Notification */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
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
            
            .form-grid {
                grid-template-columns: 1fr;
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
                <h2 class="section--title">MANAGE COURSES & YEAR LEVELS</h2>
                <button id="theme-toggle" class="theme-toggle" title="Toggle dark/light mode">
                    <i class="ri-sun-line" id="theme-icon"></i>
                </button>
            </div>
            
            <div class="overview">
                <div class="cards">
                    <div id="addCourse" class="card">
                        <div class="card--data">
                            <div class="card--content">
                                <button class="add"><i class="ri-add-line"></i>Add Course</button>
                                <h1><?php total_rows('tblcourse') ?></h1>
                                <p>Total Courses</p>
                            </div>
                            <i class="ri-book-open-line card--icon--lg"></i>
                        </div>
                    </div>

                    <div class="card" id="addyearlevel">
                        <div class="card--data">
                            <div class="card--content">
                                <button class="add"><i class="ri-add-line"></i>Add Year Level</button>
                                <h1><?php total_rows("tblyearlevel") ?></h1>
                                <p>Total Year Levels</p>
                            </div>
                            <i class="ri-stack-line card--icon--lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            <?php showMessage() ?>
            
            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">COURSES</h2>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Year level</th>
                                <th>Total Subjects</th>
                                <th>Total Professor</th>
                                <th>Date Created</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT 
                        c.name AS course_name,
                        c.yearlevelID AS yearlevel,
                        f.yearlevelName AS yearlevel_name,
                        c.Id AS Id,
                        COUNT(u.Id) AS total_units,
                        COUNT(DISTINCT s.Id) AS total_professor,
                        c.dateCreated AS date_created
                        FROM tblcourse c
                        LEFT JOIN tblunit u ON c.Id = u.courseID
                        LEFT JOIN tblprofessor s ON c.courseCode = s.courseCode
                        LEFT JOIN tblyearlevel f on c.yearlevelID=f.Id
                        GROUP BY c.Id";

                            $result = fetch($sql);

                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowcourse{$row["Id"]}'>";
                                    echo "<td>" . $row["course_name"] . "</td>";
                                    echo "<td>" . $row["yearlevel_name"] . "</td>";
                                    echo "<td>" . $row["total_units"] . "</td>";
                                    echo "<td>" . $row["total_professor"] . "</td>";
                                    echo "<td>" . $row["date_created"] . "</td>";
                                    echo "<td><i class='ri-edit-line edit' data-id='{$row["Id"]}' data-name='course'></i></td>";
                                    echo "<td><i class='ri-delete-bin-line delete'data-id='{$row["Id"]}' data-name='course'></i></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No courses found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">YEAR LEVELS</h2>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Total Courses</th>
                                <th>Total Professor</th>
                                <th>Total Department Head</th>
                                <th>Date Created</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT 
                           f.yearlevelName AS yearlevel_name,
                           f.yearlevelCode AS yearlevel_code,
                           f.Id as Id,
                           f.dateRegistered AS date_created,
                           COUNT(DISTINCT c.Id) AS total_courses,
                           COUNT(DISTINCT s.Id) AS total_professor,
                           COUNT(DISTINCT l.Id) AS total_deptheads
                       FROM tblyearlevel f
                       LEFT JOIN tblcourse c ON f.Id = c.yearlevelID
                       LEFT JOIN tblprofessor s ON f.yearlevelCode = s.yearlevel
                       LEFT JOIN tbldepthead l ON f.yearlevelCode = l.yearlevelCode
                       GROUP BY f.Id";

                            $result = fetch($sql);
                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowyearlevel{$row["Id"]}'>";
                                    echo "<td>" . $row["yearlevel_code"] . "</td>";
                                    echo "<td>" . $row["yearlevel_name"] . "</td>";
                                    echo "<td>" . $row["total_courses"] . "</td>";
                                    echo "<td>" . $row["total_professor"] . "</td>";
                                    echo "<td>" . $row["total_deptheads"] . "</td>";
                                    echo "<td>" . $row["date_created"] . "</td>";
                                    echo "<td><i class='ri-edit-line edit' data-id='{$row["Id"]}' data-name='yearlevel'></i></td>";
                                    echo "<td><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='yearlevel'></i></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No year levels found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Course Form -->
        <div class="formDiv" id="addCourseForm" style="display:none;">
            <form method="POST" action="" name="addCourse" enctype="multipart/form-data">
                <div class="form-header">
                    <h3><i class="ri-book-open-line"></i> Add New Course</h3>
                    <span class="close">&times;</span>
                </div>
                
                <div class="form-content">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="courseName">Course Name</label>
                            <input type="text" id="courseName" name="courseName" placeholder="Enter course name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="courseCode">Course Code</label>
                            <input type="text" id="courseCode" name="courseCode" placeholder="Enter course code" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="yearlevel">Year Level</label>
                            <select required name="yearlevel" id="yearlevel">
                                <option value="" selected disabled>Select Year Level</option>
                                <?php
                                $yearlevelNames = getyearlevelNames();
                                foreach ($yearlevelNames as $yearlevel) {
                                    echo '<option value="' . $yearlevel["Id"] . '">' . $yearlevel["yearlevelName"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-footer">
                    <button type="button" class="btn-cancel close-btn">Cancel</button>
                    <button type="submit" class="btn-submit" name="addCourse">
                        <i class="ri-save-line"></i>Save Course
                    </button>
                </div>
            </form>
        </div>

        <!-- Add Year Level Form -->
        <div class="formDiv" id="addyearlevelForm" style="display:none;">
            <form method="POST" action="" name="addyearlevel" enctype="multipart/form-data">
                <div class="form-header">
                    <h3><i class="ri-stack-line"></i> Add New Year Level</h3>
                    <span class="close">&times;</span>
                </div>
                
                <div class="form-content">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="yearlevelName">Year Level Name</label>
                            <input type="text" id="yearlevelName" name="yearlevelName" placeholder="Enter year level name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="yearlevelCode">Year Level Code</label>
                            <input type="text" id="yearlevelCode" name="yearlevelCode" placeholder="Enter year level code" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-footer">
                    <button type="button" class="btn-cancel close-btn">Cancel</button>
                    <button type="submit" class="btn-submit" name="addyearlevel">
                        <i class="ri-save-line"></i>Save Year Level
                    </button>
                </div>
            </form>
        </div>

        <!-- Edit Course Form -->
        <div class="formDiv" id="editCourseForm" style="display:none;">
            <form method="POST" action="">
                <div class="form-header">
                    <h3><i class="ri-edit-line"></i> Edit Course</h3>
                    <span class="close">&times;</span>
                </div>
                
                <div class="form-content">
                    <input type="hidden" name="courseID" id="editCourseID">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="editCourseName">Course Name</label>
                            <input type="text" id="editCourseName" name="courseName" placeholder="Enter course name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editCourseCode">Course Code</label>
                            <input type="text" id="editCourseCode" name="courseCode" placeholder="Enter course code" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editYearLevel">Year Level</label>
                            <select name="yearlevel" id="editYearLevel" required>
                                <option value="" disabled>Select Year Level</option>
                                <?php
                                $yearlevelNames = getyearlevelNames();
                                foreach ($yearlevelNames as $yearlevel) {
                                    echo '<option value="' . $yearlevel["Id"] . '">' . $yearlevel["yearlevelName"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-footer">
                    <button type="button" class="btn-cancel close-btn">Cancel</button>
                    <button type="submit" class="btn-submit" name="editCourse">
                        <i class="ri-save-line"></i>Update Course
                    </button>
                </div>
            </form>
        </div>

        <!-- Edit Year Level Form -->
        <div class="formDiv" id="editYearLevelForm" style="display:none;">
            <form method="POST" action="">
                <div class="form-header">
                    <h3><i class="ri-edit-line"></i> Edit Year Level</h3>
                    <span class="close">&times;</span>
                </div>
                
                <div class="form-content">
                    <input type="hidden" name="yearlevelID" id="editYearLevelID">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="editYearLevelName">Year Level Name</label>
                            <input type="text" id="editYearLevelName" name="yearlevelName" placeholder="Enter year level name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editYearLevelCode">Year Level Code</label>
                            <input type="text" id="editYearLevelCode" name="yearlevelCode" placeholder="Enter year level code" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-footer">
                    <button type="button" class="btn-cancel close-btn">Cancel</button>
                    <button type="submit" class="btn-submit" name="editYearLevel">
                        <i class="ri-save-line"></i>Update Year Level
                    </button>
                </div>
            </form>
        </div>
    </section>

    <?php js_asset(["delete_request", "editForms", "addCourse", "active_link"]) ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Theme toggle functionality
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
            
            // Form handling functionality for animations
            const formDivs = document.querySelectorAll('.formDiv');
            const overlay = document.getElementById('overlay');
            
            function showForm(form) {
                overlay.style.display = 'block';
                document.body.style.overflow = 'hidden';
                form.style.display = 'block';
                
                setTimeout(() => {
                    form.classList.add('active');
                }, 10);
                
                // Animate form groups
                const formGroups = form.querySelectorAll('.form-group');
                formGroups.forEach((group, index) => {
                    group.style.opacity = '0';
                    group.style.transform = 'translateY(10px)';
                    group.style.animation = `fadeUp 0.5s forwards ${0.1 * (index + 1)}s`;
                });
            }
            
            function hideForm() {
                formDivs.forEach(form => {
                    form.classList.remove('active');
                    setTimeout(() => {
                        form.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }, 300);
                });
                overlay.style.display = 'none';
            }
            
            // Add Course button functionality
            const addCourseBtn = document.getElementById('addCourse');
            if (addCourseBtn) {
                addCourseBtn.addEventListener('click', function() {
                    const form = document.getElementById('addCourseForm');
                    showForm(form);
                });
            }
            
            // Add Year Level button functionality
            const addYearLevelBtn = document.getElementById('addyearlevel');
            if (addYearLevelBtn) {
                addYearLevelBtn.addEventListener('click', function() {
                    const form = document.getElementById('addyearlevelForm');
                    showForm(form);
                });
            }
            
            // Add click event to close buttons
            const closeBtns = document.querySelectorAll('.close, .close-btn, .btn-cancel');
            closeBtns.forEach(btn => {
                btn.addEventListener('click', hideForm);
            });
            
            // Close forms when clicking on overlay
            overlay.addEventListener('click', hideForm);
            
            // Add animation to table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    cell.style.setProperty('--td-index', index);
                });
            });
            
            // Edit button functionality
            document.querySelectorAll('.edit').forEach(editBtn => {
                editBtn.addEventListener('click', function() {
                    const type = this.getAttribute('data-name');
                    if (type === 'course') {
                        const form = document.getElementById('editCourseForm');
                        showForm(form);
                    } else if (type === 'yearlevel') {
                        const form = document.getElementById('editYearLevelForm');
                        showForm(form);
                    }
                });
            });
        });
    </script>
</body>
</html>