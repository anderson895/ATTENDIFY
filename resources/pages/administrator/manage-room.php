<?php
if (isset($_POST["addRoom"])) {
    $roomName = htmlspecialchars(trim($_POST["roomName"]));
    $roomCode = htmlspecialchars(trim($_POST["roomCode"]));
    $roomType = htmlspecialchars(trim($_POST["roomType"]));
    $capacity = filter_var($_POST["capacity"], FILTER_VALIDATE_INT);
    $dateRegistered = date("Y-m-d");

    if ($roomName && $roomCode && $roomType && $capacity) {
        $query = $pdo->prepare("SELECT * FROM tblroom WHERE roomCode = :roomCode");
        $query->bindParam(':roomCode', $roomCode);
        $query->execute();

        if ($query->rowCount() > 0) {
            $_SESSION['message'] = "Room Already Exists";
        } else {
            $query = $pdo->prepare("INSERT INTO tblroom (roomName, roomCode, roomType, capacity, dateRegistered) 
                                     VALUES (:roomName, :roomCode, :roomType, :capacity, :dateRegistered)");
            $query->bindParam(':roomName', $roomName);
            $query->bindParam(':roomCode', $roomCode);
            $query->bindParam(':roomType', $roomType);
            $query->bindParam(':capacity', $capacity);
            $query->bindParam(':dateRegistered', $dateRegistered);
            $query->execute();

            $_SESSION['message'] = "Room Inserted Successfully";
        }
    } else {
        $_SESSION['message'] = "Invalid input for room";
    }
}

if (isset($_POST["editRoom"])) {
    $roomID = filter_var($_POST["roomID"], FILTER_VALIDATE_INT);
    $roomName = htmlspecialchars(trim($_POST["roomName"]));
    $roomCode = htmlspecialchars(trim($_POST["roomCode"]));
    $roomType = htmlspecialchars(trim($_POST["roomType"]));
    $capacity = filter_var($_POST["capacity"], FILTER_VALIDATE_INT);

    if ($roomID && $roomName && $roomCode && $roomType && $capacity) {
        $query = $pdo->prepare("UPDATE tblroom SET roomName = :roomName, roomCode = :roomCode, roomType = :roomType, capacity = :capacity WHERE Id = :roomID");
        $query->bindParam(':roomName', $roomName);
        $query->bindParam(':roomCode', $roomCode);
        $query->bindParam(':roomType', $roomType);
        $query->bindParam(':capacity', $capacity);
        $query->bindParam(':roomID', $roomID);
        $query->execute();

        $_SESSION['message'] = "Room Updated Successfully";
    } else {
        $_SESSION['message'] = "Invalid input for room";
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
        form input[type="number"],
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
        form input[type="number"]:focus,
        form select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
            transform: translateY(-2px);
        }

        form input[type="text"]::placeholder,
        form input[type="number"]::placeholder,
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

        /* Confirmation Modal */
        .confirmation-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: var(--card-bg);
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            z-index: 1001;
            width: 90%;
            max-width: 400px;
            animation: fadeIn 0.3s ease;
            border: 1px solid var(--border-color);
            text-align: center;
        }

        .confirmation-modal h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .confirmation-modal p {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }

        .confirmation-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .confirmation-buttons button {
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-no {
            background-color: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
        }

        .btn-no:hover {
            background-color: var(--light-bg);
            color: var(--text-primary);
        }

        .btn-yes {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-yes:hover {
            background-color: var(--dark-red);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(229, 9, 20, 0.3);
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
                <h2 class="section--title">MANAGE ROOMS</h2>
                <button id="theme-toggle" class="theme-toggle" title="Toggle dark/light mode">
                    <i class="ri-sun-line" id="theme-icon"></i>
                </button>
            </div>
            
            <div class="overview">
                <div class="cards">
                    <div id="addRoom" class="card">
                        <div class="card--data">
                            <div class="card--content">
                                <button class="add"><i class="ri-add-line"></i>Add Room</button>
                                <h1><?php total_rows('tblroom') ?></h1>
                                <p>Total Rooms</p>
                            </div>
                            <i class="ri-building-2-line card--icon--lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            <?php showMessage() ?>
            
            <div class="table-container">
                <div class="title">
                    <h2 class="section--title">ROOMS</h2>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Room Name</th>
                                <th>Room Code</th>
                                <th>Room Type</th>
                                <th>Capacity</th>
                                <th>Date Registered</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tblroom ORDER BY dateRegistered DESC";
                            $result = fetch($sql);

                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowroom{$row["Id"]}'>";
                                    echo "<td>" . $row["roomName"] . "</td>";
                                    echo "<td>" . $row["roomCode"] . "</td>";
                                    echo "<td>" . $row["roomType"] . "</td>";
                                    echo "<td>" . $row["capacity"] . "</td>";
                                    echo "<td>" . $row["dateRegistered"] . "</td>";
                                    echo "<td><i class='ri-edit-line edit' data-id='{$row["Id"]}' data-name='room'></i></td>";
                                    echo "<td><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='room'></i></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No rooms found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Room Form -->
        <div class="formDiv" id="addRoomForm" style="display:none;">
            <form method="POST" action="" name="addRoom" enctype="multipart/form-data">
                <div class="form-title">
                    <p>Add New Room</p>
                    <span class="close">&times;</span>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="roomName">Room Name</label>
                        <input type="text" id="roomName" name="roomName" placeholder="Enter room name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="roomCode">Room Code</label>
                        <input type="text" id="roomCode" name="roomCode" placeholder="Enter room code" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="roomType">Room Type</label>
                        <select required name="roomType" id="roomType">
                            <option value="" selected>Select Room Type</option>
                            <option value="Classroom">Classroom</option>
                            <option value="Laboratory">Laboratory</option>
                            <option value="Conference Room">Conference Room</option>
                            <option value="Office">Office</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="capacity">Capacity</label>
                        <input type="number" id="capacity" name="capacity" placeholder="Enter room capacity" required min="1">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-cancel">Cancel</button>
                    <input type="submit" class="submit" value="Save Room" name="addRoom">
                </div>
            </form>
        </div>

        <!-- Edit Room Form -->
        <div class="formDiv" id="editRoomForm" style="display:none;">
            <form method="POST" action="">
                <div class="form-title">
                    <p>Edit Room</p>
                    <span class="close">&times;</span>
                </div>
                
                <input type="hidden" name="roomID" id="editRoomID">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="editRoomName">Room Name</label>
                        <input type="text" id="editRoomName" name="roomName" placeholder="Enter room name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editRoomCode">Room Code</label>
                        <input type="text" id="editRoomCode" name="roomCode" placeholder="Enter room code" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editRoomType">Room Type</label>
                        <select name="roomType" id="editRoomType" required>
                            <option value="">Select Room Type</option>
                            <option value="Classroom">Classroom</option>
                            <option value="Laboratory">Laboratory</option>
                            <option value="Conference Room">Conference Room</option>
                            <option value="Office">Office</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="editCapacity">Capacity</label>
                        <input type="number" id="editCapacity" name="capacity" placeholder="Enter room capacity" required min="1">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-cancel">Cancel</button>
                    <input type="submit" class="submit" value="Update Room" name="editRoom">
                </div>
            </form>
        </div>
    </section>

    <?php js_asset(["delete_request", "editForms", "addRoom", "active_link"]) ?>

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

            // Add animation delay to table cells
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    cell.style.setProperty('--td-index', index);
                });
            });
            
            // Handle delete confirmation
            document.querySelectorAll('.delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    
                    // Create confirmation modal
                    const modal = document.createElement('div');
                    modal.className = 'confirmation-modal';
                    modal.innerHTML = `
                        <h3>Delete Confirmation</h3>
                        <p>Are you sure to delete this?</p>
                        <div class="confirmation-buttons">
                            <button class="btn-no">No</button>
                            <button class="btn-yes">Yes</button>
                        </div>
                    `;
                    
                    // Show overlay
                    showOverlay();
                    
                    // Add modal to body
                    document.body.appendChild(modal);
                    
                    // Handle No button
                    modal.querySelector('.btn-no').addEventListener('click', () => {
                        document.body.removeChild(modal);
                        hideOverlay();
                    });
                    
                    // Handle Yes button
                    modal.querySelector('.btn-yes').addEventListener('click', () => {
                        // Create and submit form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '';
                        
                        const idInput = document.createElement('input');
                        idInput.type = 'hidden';
                        idInput.name = name + 'ID';
                        idInput.value = id;
                        
                        const submitInput = document.createElement('input');
                        submitInput.type = 'hidden';
                        submitInput.name = 'delete' + name.charAt(0).toUpperCase() + name.slice(1);
                        submitInput.value = '1';
                        
                        form.appendChild(idInput);
                        form.appendChild(submitInput);
                        
                        document.body.appendChild(form);
                        form.submit();
                    });
                    
                    // Close modal when clicking outside
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            document.body.removeChild(modal);
                            hideOverlay();
                        }
                    });
                    
                    // Close modal with Escape key
                    document.addEventListener('keydown', function closeOnEscape(e) {
                        if (e.key === 'Escape') {
                            document.body.removeChild(modal);
                            hideOverlay();
                            document.removeEventListener('keydown', closeOnEscape);
                        }
                    });
                });
            });
        });
    </script>
</body>
</html> 