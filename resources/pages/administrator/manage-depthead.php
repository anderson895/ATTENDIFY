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
<html lang="en" data-theme="light">

<head>
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
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }

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

        /* Button Styling */
        button.add {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 10px rgba(229, 9, 20, 0.2);
        }

        button.add:hover {
            background-color: var(--dark-red);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(229, 9, 20, 0.3);
        }

        button.add i {
            font-size: 1.1rem;
            transition: transform 0.3s;
        }

        button.add:hover i {
            transform: rotate(90deg);
        }

        /* Action Buttons (Edit/Delete) */
        .ri-edit-2-line, .ri-delete-bin-line {
            font-size: 1.1rem;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .ri-edit-2-line {
            background-color: #4a6cf7;
            color: white;
        }

        .ri-edit-2-line:hover {
            background-color: #3151d3;
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 4px 12px rgba(74, 108, 247, 0.3);
        }

        .ri-delete-bin-line {
            background-color: var(--primary-color);
            color: white;
        }

        .ri-delete-bin-line:hover {
            background-color: var(--dark-red);
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 4px 12px rgba(229, 9, 20, 0.3);
        }

        /* Form Buttons */
        .btn-cancel {
            background-color: transparent;
            border: 1px solid var(--border-color);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-secondary);
        }

        .btn-cancel:hover {
            background-color: rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(229, 9, 20, 0.2);
        }

        .btn-submit:hover {
            background-color: var(--dark-red);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(229, 9, 20, 0.3);
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 30px;
            height: 100%;
            background: rgba(255, 255, 255, 0.3);
            transform: skewX(-30deg) translateX(-100%);
            transition: transform 0.5s;
        }

        .btn-submit:hover::after {
            transform: skewX(-30deg) translateX(300%);
        }

        /* Theme Toggle Button - Updated to match manage-course */
        .theme-toggle {
            background: rgba(229, 9, 20, 0.1); /* Semi-transparent red background */
            border: none; /* No border */
            border-radius: 50%; /* Circular shape */
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            color: var(--primary-color); /* Icon color matches primary red */
            font-size: 1.2rem;
            margin-left: auto; /* Positioned to the right */
            margin-right: 10px; /* Spacing */
            box-shadow: var(--shadow-sm); /* Subtle shadow */
        }

        .theme-toggle:hover {
            background: rgba(229, 9, 20, 0.2); /* Slightly darker red on hover */
            transform: rotate(30deg); /* Rotation effect on hover */
            box-shadow: var(--shadow-md); /* Slightly larger shadow on hover */
        }

        /* Remove previous dark mode specific overrides for this button */
        html[data-theme='dark'] .theme-toggle {
            /* Use the same styles as light mode for consistency */
            background: rgba(229, 9, 20, 0.1);
            color: var(--primary-color);
        }

        html[data-theme='dark'] .theme-toggle:hover {
            background: rgba(229, 9, 20, 0.2);
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
            animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            animation-delay: 0.3s;
            animation-fill-mode: both;
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
            transition: transform 0.3s, color 0.3s;
            transition-delay: calc(0.05s * var(--td-index, 0));
        }

        tbody tr {
            transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
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

        /* Add text to buttons */
        .ri-edit-2-line::after {
            content: 'Edit';
            font-size: 0.9rem;
        }

        .ri-delete-bin-line::after {
            content: 'Delete';
            font-size: 0.9rem;
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

        .formDiv-- {
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

        .formDiv--.active {
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
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
        }

        .form-group input,
        .form-group select {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--background-color);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
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

        /* Animation keyframes for form elements */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

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
    <?php include "Includes/topbar.php"; ?>

    <section class="main">
        <?php include "Includes/sidebar.php"; ?>

        <div class="main--content">
            <div id="overlay"></div>
            
            <div class="title">
                <h2 class="section--title">MANAGE DEPARTMENT HEADS</h2>
                <button id="theme-toggle" class="theme-toggle" title="Toggle dark/light mode">
                    <i class="ri-sun-line" id="theme-icon"></i>
                </button>
            </div>
            
            <div class="overview">
                <div class="cards">
                    <div class="card" id="showButton">
                        <div class="card--data">
                            <div class="card--content">
                                <button class="add"><i class="ri-add-line"></i>Add Department Head</button>
                                <h1><?php echo count(fetch("SELECT * FROM tbldepthead")); ?></h1>
                                <p>Total Department Heads</p>
                            </div>
                            <i class="ri-user-settings-line card--icon--lg"></i>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <?php showMessage() ?>
            
            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">LIST OF DEPARTMENT HEADS</h2>
                    <button class="add" id="showButtonMobile"><i class="ri-add-line"></i>Add Department Head</button>
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
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                    echo "<td><i class='ri-edit-2-line edit' data-id='{$row["Id"]}' data-name='depthead'></i></td>";
                                    echo "<td><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='depthead'></i></td>";
                                        echo "</tr>";
                                    }
                                } else {
                                echo "<tr><td colspan='8'>No records found</td></tr>";
                                }
                                ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="formDiv--" id="form" style="display:none;">
                <form method="POST" action="" name="adddepthead" enctype="multipart/form-data">
                    <div class="form-header">
                        <h3>Add Department Head</h3>
                            <span class="close">&times;</span>
                    </div>
                    
                    <div class="form-content">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" id="firstName" name="firstName" placeholder="Enter first name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" id="lastName" name="lastName" placeholder="Enter last name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" placeholder="Enter email address" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phoneNumber">Phone Number</label>
                                <input type="text" id="phoneNumber" name="phoneNumber" placeholder="Enter phone number" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" placeholder="Enter password" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="yearlevel">Year Level</label>
                                <select id="yearlevel" name="yearlevel" required>
                        <option value="" selected>Select Year Level</option>
                        <?php
                        $yearlevelNames = getyearlevelNames();
                        foreach ($yearlevelNames as $yearlevel) {
                            echo '<option value="' . $yearlevel["yearlevelCode"] . '">' . $yearlevel["yearlevelName"] . '</option>';
                        }
                        ?>
                    </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-footer">
                        <button type="button" class="btn-cancel close-btn">Cancel</button>
                        <button type="submit" class="btn-submit" name="adddepthead">Save Department Head</button>
                    </div>
                </form>
            </div>
            
            <div class="formDiv--" id="editForm" style="display:none;">
    <form method="POST" action="" name="editdepthead" enctype="multipart/form-data">
                    <div class="form-header">
                        <h3>Edit Department Head</h3>
                <span class="close">&times;</span>
            </div>
                    
                    <div class="form-content">
                        <input type="hidden" name="editId" id="editId">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="editFirstName">First Name</label>
                                <input type="text" id="editFirstName" name="editFirstName" placeholder="Enter first name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="editLastName">Last Name</label>
                                <input type="text" id="editLastName" name="editLastName" placeholder="Enter last name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="editEmail">Email Address</label>
                                <input type="email" id="editEmail" name="editEmail" placeholder="Enter email address" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="editPhoneNumber">Phone Number</label>
                                <input type="text" id="editPhoneNumber" name="editPhoneNumber" placeholder="Enter phone number" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="editPassword">Password</label>
                                <input type="password" id="editPassword" name="editPassword" placeholder="Enter password" required>
        </div>
                            
                            <div class="form-group">
                                <label for="editYearlevel">Year Level</label>
                                <select id="editYearlevel" name="editYearlevel" required>
            <option value="" selected>Select Year Level</option>
            <?php
            foreach ($yearlevelNames as $yearlevel) {
                echo '<option value="' . $yearlevel["yearlevelCode"] . '">' . $yearlevel["yearlevelName"] . '</option>';
            }
            ?>
        </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-footer">
                        <button type="button" class="btn-cancel close-btn">Cancel</button>
                        <button type="submit" class="btn-submit" name="editdepthead">Update Department Head</button>
                    </div>
    </form>
            </div>
        </div>
    </section>

    <?php js_asset(["admin_functions", "active_link", "delete_request","editdept", "script"]) ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize table row animations
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    cell.style.setProperty('--td-index', index);
                });
            });
            
            // Dark mode toggle functionality
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
            
            // Handle form show/hide with animations
            const forms = document.querySelectorAll('.formDiv--');
            const overlay = document.getElementById('overlay');
            const showButtons = document.querySelectorAll('#showButton, #showButtonMobile');
            const closeBtns = document.querySelectorAll('.close, .close-btn');
            
            showButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const form = document.getElementById('form');
                    showForm(form);
                });
            });
            
            function showForm(form) {
                overlay.style.display = 'block';
                form.style.display = 'block';
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    form.classList.add('active');
                }, 10);
            }
            
            function hideForm() {
                forms.forEach(form => {
                    form.classList.remove('active');
                    setTimeout(() => {
                        form.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }, 300);
                });
                overlay.style.display = 'none';
            }
            
            closeBtns.forEach(btn => {
                btn.addEventListener('click', hideForm);
            });
            
            overlay.addEventListener('click', hideForm);
            
            // Enhance the edit functionality with animations
            document.querySelectorAll('.edit').forEach(editBtn => {
                editBtn.addEventListener('click', function() {
                    const editForm = document.getElementById('editForm');
                    showForm(editForm);
                    // The rest of the edit functionality is handled by your existing editdept.js
                });
            });
            
            // Add animation to form groups on form open
            forms.forEach(form => {
                const formGroups = form.querySelectorAll('.form-group');
                formGroups.forEach((group, index) => {
                    group.style.opacity = '0';
                    group.style.transform = 'translateY(10px)';
                    group.style.animation = `fadeUp 0.5s forwards ${0.1 * (index + 1)}s`;
                });
            });
        });
    </script>
</body>
</html>