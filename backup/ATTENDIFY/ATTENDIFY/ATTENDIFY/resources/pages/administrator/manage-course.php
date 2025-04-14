<?php


if (isset($_POST["addCourse"])) {
    $courseName = htmlspecialchars(trim($_POST["courseName"])); // Escape and trim whitespace
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

if (isset($_POST["addUnit"])) {
    $unitName = htmlspecialchars(trim($_POST["unitName"]));
    $unitCode = htmlspecialchars(trim($_POST["unitCode"]));
    $courseID = filter_var($_POST["course"], FILTER_VALIDATE_INT);
    $dateRegistered = date("Y-m-d");

    if ($unitName && $unitCode && $courseID) {
        $query = $pdo->prepare("SELECT * FROM tblunit WHERE unitCode = :unitCode");
        $query->bindParam(':unitCode', $unitCode);
        $query->execute();

        if ($query->rowCount() > 0) {
            $_SESSION['message'] = "Subject Already Exists";
        } else {
            $query = $pdo->prepare("INSERT INTO tblunit (name, unitCode, courseID, dateCreated) 
                                     VALUES (:name, :unitCode, :courseID, :dateCreated)");
            $query->bindParam(':name', $unitName);
            $query->bindParam(':unitCode', $unitCode);
            $query->bindParam(':courseID', $courseID);
            $query->bindParam(':dateCreated', $dateRegistered);
            $query->execute();

            $_SESSION['message'] = "Subject Inserted Successfully";
        }
    } else {
        $_SESSION['message'] = "Invalid input for unit";
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
            $_SESSION['message'] = "yearlevel Already Exists";
        } else {
            $query = $pdo->prepare("INSERT INTO tblyearlevel (yearlevelName, yearlevelCode, dateRegistered) 
                                     VALUES (:yearlevelName, :yearlevelCode, :dateRegistered)");
            $query->bindParam(':yearlevelName', $yearlevelName);
            $query->bindParam(':yearlevelCode', $yearlevelCode);
            $query->bindParam(':dateRegistered', $dateRegistered);
            $query->execute();

            $_SESSION['message'] = "yearlevel Inserted Successfully";
        }
    } else {
        $_SESSION['message'] = "Invalid input for yearlevel";
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

if (isset($_POST["editUnit"])) {
    $unitID = filter_var($_POST["unitID"], FILTER_VALIDATE_INT);
    $unitName = htmlspecialchars(trim($_POST["unitName"]));
    $unitCode = htmlspecialchars(trim($_POST["unitCode"]));
    $courseID = filter_var($_POST["course"], FILTER_VALIDATE_INT);

    if ($unitID && $unitName && $unitCode && $courseID) {
        $query = $pdo->prepare("UPDATE tblunit SET name = :name, unitCode = :unitCode, courseID = :courseID WHERE Id = :unitID");
        $query->bindParam(':name', $unitName);
        $query->bindParam(':unitCode', $unitCode);
        $query->bindParam(':courseID', $courseID);
        $query->bindParam(':unitID', $unitID);
        $query->execute();

        $_SESSION['message'] = "Subject Updated Successfully";
    } else {
        $_SESSION['message'] = "Invalid input for unit";
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
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="resources/images/tablogo.png" type="image/svg+xml">
    <title>ATTENDIFY</title>
    <link rel="stylesheet" href="resources/assets/css/admin_styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/topbar.php' ?>
    <section class="main">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main--content">
            <div id="overlay"></div>
            <div class="overview">
                <div class="title">
                    <h2 class="section--title">OVERVIEW</h2>
                    <select name="date" id="date" class="dropdown">
                        <option value="today">Today</option>
                        <option value="lastweek">Last Week</option>
                        <option value="lastmonth">Last Month</option>
                        <option value="lastyear">Last Year</option>
                        <option value="alltime">All Time</option>
                    </select>
                </div>
                <div class="cards">
                    <div id="addCourse" class="card card-1">

                        <div class="card--data">
                            <div class="card--content">
                                <button class="add"><i class="ri-add-line"></i>Add Course</button>
                                <h1><?php total_rows('tblcourse') ?> Courses</h1>
                            </div>
                            <i class="ri-user-2-line card--icon--lg"></i>
                        </div>

                    </div>
                    <div class="card card-1" id="addUnit">

                        <div class="card--data">
                            <div class="card--content">
                                <button class="add"><i class="ri-add-line"></i>Add Subject</button>
                                <h1><?php total_rows('tblunit') ?> Subject</h1>
                            </div>
                            <i class="ri-file-text-line card--icon--lg"></i>
                        </div>

                    </div>

                    <div class="card card-1" id="addyearlevel">

                        <div class="card--data">
                            <div class="card--content">
                                <button class="add"><i class="ri-add-line"></i>Add Year Level</button>
                                <h1><?php total_rows("tblyearlevel") ?> Year Level </h1>
                            </div>
                            <i class="ri-user-line card--icon--lg"></i>
                        </div>

                    </div>
                </div>
            </div>

            <?php showMessage() ?>
            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">COURSE</h2>
                </div>
                </a>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Year level</th>
                                <th>Total Subjects</th>
                                <th>Total Professor</th>
                                <th>Date Created</th>
                                <th>Update</th>

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
                                echo "<tr><td colspan='6'>No records found</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">SUBJECT</h2>
                </div>
                </a>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Total Professor</th>
                                <th>Date Created</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT 
                            c.name AS course_name,
                            u.unitCode AS unit_code,
                            u.name AS unit_name, u.Id as Id,
                            u.dateCreated AS date_created,
                            COUNT(s.Id) AS total_professor
                            FROM tblunit u
                            LEFT JOIN tblcourse c ON u.courseID = c.Id
                            LEFT JOIN tblprofessor s ON c.courseCode = s.courseCode
                            GROUP BY u.Id";
                            $result = fetch($sql);
                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowunit{$row["Id"]}' >";
                                    echo "<td>" . $row["unit_code"] . "</td>";
                                    echo "<td>" . $row["unit_name"] . "</td>";
                                    echo "<td>" . $row["course_name"] . "</td>";
                                    echo "<td>" . $row["total_professor"] . "</td>";
                                    echo "<td>" . $row["date_created"] . "</td>";
                                    echo "<td><i class='ri-edit-line edit' data-id='{$row["Id"]}' data-name='unit'></i></td>";
                                    echo "<td><span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='unit'></i></span></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No records found</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">YEAR LEVEL</h2>
                </div>
                </a>
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
                                    echo "<td><span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='yearlevel'></i></span></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No records found</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
<!-- ADD  BUTTON-->
        </div>
        <div class="formDiv" id="addCourseForm" style="display:none; ">

            <form method="POST" action="" name="addCourse" enctype="multipart/form-data">
                <div style="display:flex; justify-content:space-around;">
                    <div class="form-title">
                        <p>Add Course</p>
                    </div>
                    <div>
                        <span class="close">&times;</span>
                    </div>
                </div>

                <input type="text" name="courseName" placeholder="Course Name + Year Level" required>
                <input type="text" name="courseCode" placeholder="Course Code + Year Level" required>


                <select required name="yearlevel">
                    <option value="" selected>Select Year Level</option>
                    <?php
                    $yearlevelNames = getyearlevelNames();
                    foreach ($yearlevelNames as $yearlevel) {
                        echo '<option value="' . $yearlevel["Id"] . '">' . $yearlevel["yearlevelName"] . '</option>';
                    }
                    ?>
                </select>

                <input type="submit" class="submit" value="Save Course" name="addCourse">
            </form>H
        </div>

        <div class="formDiv" id="addUnitForm" style="display:none; ">
            <form method="POST" action="" name="addUnit" enctype="multipart/form-data">
                <div style="display:flex; justify-content:space-around;">
                    <div class="form-title">
                        <p>Add Subject</p>
                    </div>
                    <div>
                        <span class="close">&times;</span>
                    </div>
                </div>

                <input type="text" name="unitName" placeholder="Subject Name + Year Level" required>
                <input type="text" name="unitCode" placeholder="Subject Code + Year Level" required>

                <select required name="depthead">
                    <option value="" selected>Assign Department Head</option>
                    <?php
                    $deptheadNames = getdeptheadNames();
                    foreach ($deptheadNames as $depthead) {
                        echo '<option value="' . $depthead["Id"] . '">' . $depthead["firstName"] . ' ' . $depthead["lastName"]  .  '</option>';
                    }
                    ?>
                </select>
                <select required name="course">
                    <option value="" selected>Select Course</option>
                    <?php
                    $courseNames = getCourseNames();
                    foreach ($courseNames as $course) {
                        echo '<option value="' . $course["Id"] . '">' . $course["name"] . '</option>';
                    }
                    ?>
                </select>

                <input type="submit" class="submit" value="Save Subject" name="addUnit">
            </form>
        </div>

        <div class="formDiv" id="addyearlevelForm" style="display:none; ">
            <form method="POST" action="" name="addyearlevel" enctype="multipart/form-data">
                <div style="display:flex; justify-content:space-around;">
                    <div class="form-title">
                        <p>Add Year Level</p>
                    </div>
                    <div>
                        <span class="close">&times;</span>
                    </div>
                </div>
                <input type="text" name="yearlevelName" placeholder="Year Level Name" required>
                <input type="text" name="yearlevelCode" placeholder="Year Level Code" required>
                <input type="submit" class="submit" value="Save Year level" name="addyearlevel">
            </form>
        </div>

    <!-- EDIT BUTTON-->
        <div class="formDiv" id="editCourseForm" style="display:none;">
    <form method="POST" action="">
        <div class="form-title">
            <p>Edit Course</p>
            <span class="close">&times;</span>
        </div>
        <input type="hidden" name="courseID" id="editCourseID">
        <input type="text" name="courseName" id="editCourseName" placeholder="Course Name" required>
        <input type="text" name="courseCode" id="editCourseCode" placeholder="Course Code" required>
        <select name="yearlevel" id="editYearLevel" required>
            <option value="">Select Year Level</option>
            <?php
            $yearlevelNames = getyearlevelNames();
            foreach ($yearlevelNames as $yearlevel) {
                echo '<option value="' . $yearlevel["Id"] . '">' . $yearlevel["yearlevelName"] . '</option>';
            }
            ?>
        </select>
        <input type="submit" class="submit" value="Update Course" name="editCourse">
    </form>
</div>

<!-- Repeat similar forms for Units and Year Levels -->
<div class="formDiv" id="editUnitForm" style="display:none;">
    <form method="POST" action="">
        <div class="form-title">
            <p>Edit Subject</p>
            <span class="close">&times;</span>
        </div>
        <input type="hidden" name="unitID" id="editUnitID">
        <input type="text" name="unitName" id="editUnitName" placeholder="Subject Name" required>
        <input type="text" name="unitCode" id="editUnitCode" placeholder="Subject Code" required>
        <select name="course" id="editCourse" required>
            <option value="">Select Course</option>
            <?php
            $courseNames = getCourseNames();
            foreach ($courseNames as $course) {
                echo '<option value="' . $course["Id"] . '">' . $course["name"] . '</option>';
            }
            ?>
        </select>
        <input type="submit" class="submit" value="Update Subject" name="editUnit">
    </form>
</div>
<div class="formDiv" id="editYearLevelForm" style="display:none;">
    <form method="POST" action="">
        <div class="form-title">
            <p>Edit Year Level</p>
            <span class="close">&times;</span>
        </div>
        <input type="hidden" name="yearLevelID" id="editYearLevelID">
        <input type="text" name="yearLevelName" id="editYearLevelName" placeholder="Year Level Name" required>
        <input type="text" name="yearLevelCode" id="editYearLevelCode" placeholder="Year Level Code" required>
        <input type="submit" class="submit" value="Update Year Level" name="editYearLevel">
    </form>
</div>

    </section>

    <?php js_asset(["delete_request", "editForms" ,  "addCourse", "active_link"]) ?>
</body>

</html>