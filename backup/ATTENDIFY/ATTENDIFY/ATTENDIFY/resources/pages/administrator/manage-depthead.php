<?php

if (isset($_POST["adddepthead"])) {
    // Securely handle input
    $firstName = htmlspecialchars(trim($_POST["firstName"]));
    $lastName = htmlspecialchars(trim($_POST["lastName"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $phoneNumber = htmlspecialchars(trim($_POST["phoneNumber"]));
    $yearlevel = htmlspecialchars(trim($_POST["yearlevel"]));
    $dateRegistered = date("Y-m-d");
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Secure password hashing

    if ($email && $firstName && $lastName && $phoneNumber && $yearlevel) {
        try {
            // Check if depthead already exists
            $query = $pdo->prepare("SELECT * FROM tbldepthead WHERE emailAddress = :email");
            $query->bindParam(':email', $email);
            $query->execute();

            if ($query->rowCount() > 0) {
                $_SESSION['message'] = "depthead Already Exists";
            } else {
                // Insert new depthead
                $query = $pdo->prepare("INSERT INTO tbldepthead 
                    (firstName, lastName, emailAddress, password, phoneNo, yearlevelCode, dateCreated) 
                    VALUES (:firstName, :lastName, :email, :password, :phoneNumber, :yearlevel, :dateCreated)");
                $query->bindParam(':firstName', $firstName);
                $query->bindParam(':lastName', $lastName);
                $query->bindParam(':email', $email);
                $query->bindParam(':password', $hashedPassword);
                $query->bindParam(':phoneNumber', $phoneNumber);
                $query->bindParam(':yearlevel', $yearlevel);
                $query->bindParam(':dateCreated', $dateRegistered);

                $query->execute();

                $_SESSION['message'] = "depthead Added Successfully";
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['message'] = "Invalid input. Please check your data.";
    }
}

if (isset($_POST["editdepthead"])) {
    $id = htmlspecialchars(trim($_POST["editId"]));
    $firstName = htmlspecialchars(trim($_POST["editFirstName"]));
    $lastName = htmlspecialchars(trim($_POST["editLastName"]));
    $email = filter_var(trim($_POST["editEmail"]), FILTER_VALIDATE_EMAIL);
    $phoneNumber = htmlspecialchars(trim($_POST["editPhoneNumber"]));
    $yearlevel = htmlspecialchars(trim($_POST["editYearlevel"]));
    $password = $_POST['editPassword'];

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    if ($email && $firstName && $lastName && $phoneNumber && $yearlevel) {
        try {
            $query = $pdo->prepare("UPDATE tbldepthead SET 
                firstName = :firstName, 
                lastName = :lastName, 
                emailAddress = :email, 
                phoneNo = :phoneNumber, 
                yearlevelCode = :yearlevel, 
                password = :password 
                WHERE Id = :id");

            $query->bindParam(':id', $id);
            $query->bindParam(':firstName', $firstName);
            $query->bindParam(':lastName', $lastName);
            $query->bindParam(':email', $email);
            $query->bindParam(':phoneNumber', $phoneNumber);
            $query->bindParam(':yearlevel', $yearlevel);
            $query->bindParam(':password', $hashedPassword);

            $query->execute();

            $_SESSION['message'] = "Department Head Updated Successfully";
        } catch (PDOException $e) {
            $_SESSION['message'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['message'] = "Invalid input. Please check your data.";
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
    <?php include "Includes/topbar.php"; ?>

    <section class=main>

        <?php include "Includes/sidebar.php"; ?>

        <div class="main--content">
            <div id="overlay"></div>
            <?php showMessage() ?>
            <div class="table-container">
                <div class="title" id="showButton">
                    <h2 class="section--title">LIST OF DEPARTMENT HEAD</h2>
                    <button class="add"><i class="ri-add-line"></i>Add Department Head</button>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email Address</th>
                                <th>Phone No</th>
                                <th>Year Level Handle</th>
                                <th>Date Registered</th>
                                <th>Edit</th>
                                <th>Delete<th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                $sql = "SELECT * FROM tbldepthead";
                                $result = fetch($sql);
                                if ($result) {
                                    foreach ($result as $row) {
                                        echo "<tr id='rowdepthead{$row["Id"]}'>";
                                        echo "<td>" . $row["firstName"] . "</td>";
                                        echo "<td>" . $row["lastName"] . "</td>";
                                        echo "<td>" . $row["emailAddress"] . "</td>";
                                        echo "<td>" . $row["phoneNo"] . "</td>";
                                        echo "<td>" . $row["yearlevelCode"] . "</td>";
                                        echo "<td>" . $row["dateCreated"] . "</td>";
                                        echo "<td><span><i class='ri-edit-2-line edit' data-id='{$row["Id"]}' data-name='depthead'></i></span></td>";
                                        echo "<td><span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='depthead'></i></span></td>";
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
            <div class="formDiv--" id="form" style="display:none; ">
                <form method="POST" action="" name="adddepthead" enctype="multipart/form-data">
                    <div style="display:flex; justify-content:space-around;">
                        <div class="form-title">
                            <p>ADD DEPARTMENT HEAD</p>
                        </div>
                        <div>
                            <span class="close">&times;</span>
                        </div>
                    </div>
                    <input type="text" name="firstName" placeholder="First Name" required>
                    <input type="text" name="lastName" placeholder="Last Name" required>
                    <input type="email" name="email" placeholder="Email Address" required>
                    <input type="text" name="phoneNumber" placeholder="Phone Number" required>
                    <input type="password" name="password" placeholder="**********" required>

                    <select required name="yearlevel">
                        <option value="" selected>Select Year Level</option>
                        <?php
                        $yearlevelNames = getyearlevelNames();
                        foreach ($yearlevelNames as $yearlevel) {
                            echo '<option value="' . $yearlevel["yearlevelCode"] . '">' . $yearlevel["yearlevelName"] . '</option>';
                        }
                        ?>
                    </select>
                    <input type="submit" class="submit" value="Save Department Head" name="adddepthead">
                </form>
            </div>
            <div class="formDiv--" id="editForm" style="display:none;">
    <form method="POST" action="" name="editdepthead" enctype="multipart/form-data">
        <div style="display:flex; justify-content:space-around;">
            <div class="form-title">
                <p>EDIT DEPARTMENT HEAD</p>
            </div>
            <div>
                <span class="close">&times;</span>
            </div>
        </div>
        <input type="hidden" name="editId" id="editId">
        <input type="text" name="editFirstName" id="editFirstName" placeholder="First Name" required>
        <input type="text" name="editLastName" id="editLastName" placeholder="Last Name" required>
        <input type="email" name="editEmail" id="editEmail" placeholder="Email Address" required>
        <input type="text" name="editPhoneNumber" id="editPhoneNumber" placeholder="Phone Number" required>
        <input type="password" name="editPassword" id="editPassword" placeholder="**********" required>

        <select required name="editYearlevel" id="editYearlevel">
            <option value="" selected>Select Year Level</option>
            <?php
            foreach ($yearlevelNames as $yearlevel) {
                echo '<option value="' . $yearlevel["yearlevelCode"] . '">' . $yearlevel["yearlevelName"] . '</option>';
            }
            ?>
        </select>
        <input type="submit" class="submit" value="Update Department Head" name="editdepthead">
    </form>


    </section>

    <?php js_asset(["admin_functions", "active_link", "delete_request","editdept", "script"]) ?>


</body>

</html>