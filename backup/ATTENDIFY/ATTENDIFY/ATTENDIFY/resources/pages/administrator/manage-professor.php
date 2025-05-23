<?php

if (isset($_POST['addprofessor'])) {

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $registrationNumber = $_POST['registrationNumber'];
    $courseCode = $_POST['course'];
    $yearlevel = $_POST['yearlevel'];
    $dateRegistered = date("Y-m-d");

    $imageFileNames = []; // Array to hold image file names

    // Process and save images
    $folderPath = "resources/labels/{$registrationNumber}/";
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    for ($i = 1; $i <= 5; $i++) {
        if (isset($_POST["capturedImage$i"])) {
            $base64Data = explode(',', $_POST["capturedImage$i"])[1];
            $imageData = base64_decode($base64Data);
            $fileName = "{$registrationNumber}_image{$i}.png";
            $labelName = "{$i}.png";
            file_put_contents("{$folderPath}{$labelName}", $imageData);
            $imageFileNames[] = $fileName;
        }
    }

    // Convert image file names to JSON
    $imagesJson = json_encode($imageFileNames);

    // Check for duplicate registration number
    $checkQuery = $pdo->prepare("SELECT COUNT(*) FROM tblprofessor WHERE registrationNumber = :registrationNumber");
    $checkQuery->execute([':registrationNumber' => $registrationNumber]);
    $count = $checkQuery->fetchColumn();

    if ($count > 0) {
        $_SESSION['message'] = "professor with the given Registration No: $registrationNumber already exists!";
    } else {
        // Insert new professor with images stored as JSON
        $insertQuery = $pdo->prepare("
        INSERT INTO tblprofessor 
        (firstName, lastName, email, registrationNumber, yearlevel, courseCode, professorImage, dateRegistered) 
        VALUES 
        (:firstName, :lastName, :email, :registrationNumber, :yearlevel, :courseCode, :professorImage, :dateRegistered)
    ");

        $insertQuery->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':email' => $email,
            ':registrationNumber' => $registrationNumber,
            ':yearlevel' => $yearlevel,
            ':courseCode' => $courseCode,
            ':professorImage' => $imagesJson, // Store JSON array of image file names
            ':dateRegistered' => $dateRegistered
        ]);

        $_SESSION['message'] = "professor: $registrationNumber added successfully!";
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="resources/images/tablogo.png" type="image/svg+xml">
    <title>ATTENDIFY</title>
    <link rel="stylesheet" href="resources/assets/css/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/topbar.php'; ?>

    <section class=main>

        <?php include "Includes/sidebar.php"; ?>

        <div class="main--content">
            <div id="overlay"></div>
            <?php showMessage(); ?>
            <div class="table-container">

                <div class="title" id="showButton">
                    <h2 class="section--title">LIST OF PROFESSOR</h2>
                    <button class="add"><i class="ri-add-line"></i>ADD PROFESSOR</button>
                </div>

                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Registration No</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Year Level</th>
                                <th>Course</th>
                                <th>Email</th>
                                <th>Settings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tblprofessor";
                            $result = fetch($sql);
                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowprofessor{$row["Id"]}'>";
                                    echo "<td>" . $row["registrationNumber"] . "</td>";
                                    echo "<td>" . $row["firstName"] . "</td>";
                                    echo "<td>" . $row["lastName"] . "</td>";
                                    echo "<td>" . $row["yearlevel"] . "</td>";
                                    echo "<td>" . $row["courseCode"] . "</td>";
                                    echo "<td>" . $row["email"] . "</td>";
                                    echo "<td><span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='professor'></i></span></td>";
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
            <div class="formDiv--" id="form" style="display:none;">

                <form method="post">
                    <div style="display:flex; justify-content:space-around;">
                        <div class="form-title">
                            <p>ADD PROFESSOR</p>
                        </div>
                        <div>
                            <span class="close">&times;</span>
                        </div>
                    </div>
                    <div>
                        <div>
                            <input type="text" name="firstName" placeholder="First Name">
                            <input type="text" name="lastName" " placeholder=" Last Name">
                            <input type="email" name="email" placeholder="Email Address">
                            <input type="text" required id="registrationNumber" name="registrationNumber" placeholder="Registration Number"> <br>
                            <p id="error" style="color: red; display: none;">Invalid characters in registration number.</p> 
                            <select required name="yearlevel">
                                <option value="" selected>Select Year Level</option>
                                <?php
                                $yearlevelNames = getyearlevelNames();
                                foreach ($yearlevelNames as $yearlevel) {
                                    echo '<option value="' . $yearlevel["yearlevelCode"] . '">' . $yearlevel["yearlevelName"] . '</option>';
                                }
                                ?>
                            </select> <br />

                            <select required name="course">
                                <option value="" selected>Select Course</option>
                                <?php
                                $courseNames = getCourseNames();
                                foreach ($courseNames as $course) {
                                    echo '<option value="' . $course["courseCode"] . '">' . $course["name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <div class="form-title-image">
                                <p>TAKE PROFESSOR DATASET
                                <p>
                            </div>
                            <div id="open_camera" class="image-box" onclick="takeMultipleImages()">
                                <img src="resources/images/default.png" alt="Default Image">
                            </div>
                            <div id="multiple-images">



                            </div>


                        </div>
                    </div>

                    <input type="submit" class="btn-submit" value="Save Dataset" name="addprofessor" />


                </form>
            </div>

    </section>



    <?php js_asset(["admin_functions", "delete_request", "script", "active_link"]) ?>

    <script>
        const registrationNumberInput = document.getElementById('registrationNumber');
        const errorMessage = document.getElementById('error');

        const invalidCharacters = /[\\/:*?"<>|]/g;

        registrationNumberInput.addEventListener('input', () => {
            const originalValue = registrationNumberInput.value;

            const sanitizedValue = originalValue.replace(invalidCharacters, '');

            if (originalValue !== sanitizedValue) {
                errorMessage.style.display = 'inline';
                errorMessage.textContent = 'Invalid characters removed.';
            } else {
                errorMessage.style.display = 'none';
            }

            registrationNumberInput.value = sanitizedValue; 
        });
    </script>
</body>

</html>