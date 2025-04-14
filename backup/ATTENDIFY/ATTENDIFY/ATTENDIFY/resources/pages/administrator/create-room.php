<?php


if (isset($_POST["addroom"])) {
    // Sanitize and validate inputs
    $className = htmlspecialchars(trim($_POST['className']));
    $yearlevelCode = htmlspecialchars(trim($_POST['yearlevel']));
    $currentStatus = htmlspecialchars(trim($_POST['currentStatus']));
    $capacity = filter_var($_POST['capacity'], FILTER_VALIDATE_INT);
    $classification = htmlspecialchars(trim($_POST['classification']));

    // Check for required fields
    if (!$className || !$yearlevelCode || !$currentStatus || !$capacity || !$classification) {
        $_SESSION['message'] = "All fields are required and must be valid.";
    } else {
        $dateRegistered = date("Y-m-d");

        // Prepare database operations using PDO
        try {
            // Check if room already exists
            $stmt = $pdo->prepare("SELECT * FROM tblroom WHERE className = :className");
            $stmt->bindParam(':className', $className);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $_SESSION['message'] = "room Already Exists";
            } else {
                // Insert the new room
                $stmt = $pdo->prepare(
                    "INSERT INTO tblroom (className, yearlevelCode, currentStatus, capacity, classification, dateCreated)
                    VALUES (:className, :yearlevelCode, :currentStatus, :capacity, :classification, :dateCreated)"
                );
                $stmt->bindParam(':className', $className);
                $stmt->bindParam(':yearlevelCode', $yearlevelCode);
                $stmt->bindParam(':currentStatus', $currentStatus);
                $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
                $stmt->bindParam(':classification', $classification);
                $stmt->bindParam(':dateCreated', $dateRegistered);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "room Inserted Successfully";
                } else {
                    $_SESSION['message'] = "Failed to Insert room.";
                }
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = "Database Error: " . $e->getMessage();
        }
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

            <div class="rooms">
                <div class="title">
                    <h2 class="section--title">ROOMS</h2>
                    <div class="rooms--right--btns">
                        <select name="date" id="date" class="dropdown room--filter">
                            <option>Filter</option>
                            <option value="free">Free</option>
                            <option value="scheduled">Scheduled</option>
                        </select>
                        <button id="addClass1" class="add show-form"><i class="ri-add-line"></i>Schedule Class</button>
                    </div>
                </div>
                <div class="rooms--cards">
                    <a href="#" class="room--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="resources/images/office image.jpeg" alt="">
                            </div>
                        </div>
                        <p class="free">322</p>
                    </a>
                    <a href="#" class="room--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="resources/images/class.jpeg" alt="">
                            </div>
                        </div>
                        <p class="free">323</p>
                    </a>

                    <a href="#" class="room--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="resources/images/lecture hall.jpeg" alt="">
                            </div>
                        </div>
                        <p class="free">324</p>
                    </a>

                    <a href="#" class="room--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="resources/images/computer lab.jpeg" alt="">
                            </div>
                        </div>
                        <p class="free">325</p>
                    </a>
                    <a href="#" class="room--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="resources/images/laboratory.jpeg" alt="">
                            </div>
                        </div>
                        <p class="free">326</p>
                    </a>
                </div>
            </div>
            <?php showMessage() ?>
            <div class="table-container">
                <div class="title" id="addClass2">
                    <h2 class="section--title">LISTS OF ROOMS</h2>
                    <button class="add show-form"><i class="ri-add-line"></i>Schedule Class</button>
                </div>

                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Year Level</th>
                                <th>Current Status</th>
                                <th>Capacity</th>
                                <th>Classification</th>
                                <th>Settings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tblroom";
                            $stmt = $pdo->query($sql);
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if ($result) {
                                foreach ($result as $row)
                                    echo "<tr id='rowroom{$row["Id"]}'>";
                                echo "<td>" . $row["className"] . "</td>";
                                echo "<td>" . $row["yearlevelCode"] . "</td>";
                                echo "<td>" . $row["currentStatus"] . "</td>";
                                echo "<td>" . $row["capacity"] . "</td>";
                                echo "<td>" . $row["classification"] . "</td>";
                                echo "<td><span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='room'></i></span></td>";
                                echo "</tr>";
                            } else {
                                echo "<tr><td colspan='6'>No records found</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="formDiv-room" id="addClassForm" style="display:none ">
                <form method="POST" action="" name="addroom" enctype="multipart/form-data">
                    <div style="display:flex; justify-content:space-around;">
                        <div class="form-title">
                            <p>Add Room</p>
                        </div>
                        <div>
                            <span class="close">&times;</span>
                        </div>
                    </div>
                    <input type="text" name="className" placeholder="Class Name" required>
                    <select name="currentStatus" id="">
                        <option value="">Current Status</option>
                        <option value="availlable">Available</option>
                        <option value="scheduled">Scheduled</option>
                    </select>
                    <input type="text" name="capacity" placeholder="Capacity" required>
                    <select required name="classification">
                        <option value="" selected>Select Classroom</option>
                        <option value="322">322</option>
                        <option value="323">323</option>
                        <option value="324">324</option>
                        <option value="325">325</option>
                        <option value="326">326</option>
                    </select>
                    <select required name="yearlevel">
                        <option value="" selected>Select Year Level</option>
                        <?php
                        $yearlevelNames = getyearlevelNames();
                        foreach ($yearlevelNames as $yearlevel) {
                            echo '<option value="' . $yearlevel["yearlevelCode"] . '">' . $yearlevel["yearlevelName"] . '</option>';
                        }
                        ?>
                    </select>
                    <input type="submit" class="submit" value="Save room" name="addroom">
                </form>
            </div>
        </div>
    </section>
    <?php js_asset(["active_link", "delete_request"]) ?>


    <script>
        const show_form = document.querySelectorAll(".show-form")
        const addClassForm = document.getElementById('addClassForm');
        const overlay = document.getElementById('overlay');
        const closeButtons = document.querySelectorAll('#addClassForm .close');
        show_form.forEach((showForm) => {
            showForm.addEventListener('click', function() {
                addClassForm.style.display = 'block';
                overlay.style.display = 'block';
                document.body.style.overflow = 'hidden';

            });
        })

        closeButtons.forEach(function(closeButton) {
            closeButton.addEventListener('click', function() {
                addClassForm.style.display = 'none';
                overlay.style.display = 'none';
                document.body.style.overflow = 'auto';

            });
        });
    </script>
</body>

</html>