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
            /* Light Theme Variables */
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

        /* Dark Theme */
        html[data-theme='dark'] {
            --background-color: #121212;
            --card-bg: #1e1e1e;
            --light-bg: #252525;
            
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --text-muted: #888888;
            
            --border-color: #333333;
        }

        /* Main Content */
        .main--content {
            background-color: var(--light-bg);
            padding: 2rem;
            transition: var(--transition);
        }

        /* Title Section */
        .title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding: 0.5rem 0;
        }

        .section--title {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 600;
            position: relative;
            padding-left: 1rem;
        }

        .section--title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        /* Overview Card */
        .cards {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .cards:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .card--data {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
        }

        .card--content {
            flex: 1;
            order: 1;
        }

        .card--content .add {
            margin-bottom: 1rem;
        }

        .card--content h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0.5rem 0;
        }

        .card--content p {
            color: var(--text-secondary);
            font-size: 1rem;
            margin: 0;
        }

        .card--icon--lg {
            font-size: 3rem;
            color: var(--primary-color);
            opacity: 0.8;
            order: 2;
            margin-left: 2rem;
        }

        /* Table Styles */
        .table-container {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            margin: 2rem 0;
            min-height: 600px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th {
            background-color: var(--background-color);
            color: var(--text-primary);
            font-weight: 600;
            padding: 1.2rem;
            text-align: left;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border-color);
        }

        td {
            padding: 1.2rem;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
        }

        tbody tr {
            transition: var(--transition);
        }

        tbody tr:hover {
            background-color: var(--background-color);
            transform: scale(1.01);
        }

        /* Buttons */
        .add {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
        }

        .add:hover {
            background-color: var(--dark-red);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(229, 9, 20, 0.2);
        }

        .delete {
            color: var(--primary-color);
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
        }

        .delete:hover {
            color: var(--dark-red);
        }

        /* Form Modal */
        .formDiv-room {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--card-bg);
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            width: 90%;
            max-width: 500px;
        }

        .formDiv-room form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .formDiv-room input,
        .formDiv-room select {
            padding: 0.8rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--background-color);
            color: var(--text-primary);
        }

        .formDiv-room input:focus,
        .formDiv-room select:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 999;
            display: none;
        }

        /* Theme Toggle */
        .theme-toggle-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

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

        /* Responsive Design */
        @media (max-width: 768px) {
            .main--content {
                padding: 1rem;
            }
            
            .search-box {
                width: 100%;
            }
            
            .table-container {
                padding: 1rem;
            }
            
            th, td {
                padding: 1rem;
            }
        }

        /* Search Box Styles */
        .search-wrapper {
            margin-bottom: 2rem;
        }

        .search-box {
            width: 400px;
            padding: 1rem 1.2rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--card-bg);
            color: var(--text-primary);
            font-size: 1rem;
            transition: var(--transition);
        }

        .search-box:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
        }

        /* Action Buttons */
        .actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .edit, .delete {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
        }

        .edit {
            color: #2196F3;
        }

        .delete {
            color: var(--primary-color);
        }

        .edit:hover, .delete:hover {
            background-color: var(--light-bg);
        }

        /* Update the delete button styles */
        .ri-delete-bin-line {
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

        .ri-delete-bin-line::before {
            margin-right: 4px;
        }

        .ri-delete-bin-line:hover {
            background-color: var(--dark-red);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3);
        }

        /* Add text to delete button */
        .ri-delete-bin-line::after {
            content: 'Delete';
            font-size: 0.9rem;
        }

        /* Update table cell padding for action buttons */
        td:last-child {
            padding: 0.5rem;
        }

        /* Update table hover effect */
        tbody tr:hover {
            background-color: var(--light-bg);
            transform: scale(1.01);
            box-shadow: var(--shadow-sm);
        }
    </style>
</head>

<body>
    <?php include 'includes/topbar.php' ?>
    <section class="main">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main--content">
            <div class="title">
                <h2 class="section--title">MANAGE ROOMS</h2>
                <button id="theme-toggle" class="theme-toggle" title="Toggle dark/light mode">
                    <i class="ri-sun-line" id="theme-icon"></i>
                </button>
            </div>

            <div class="cards">
                <div class="card--data">
                    <div class="card--content">
                        <button class="add show-form">
                            <i class="ri-add-line"></i>Add Room
                        </button>
                        <h1><?php echo count(fetch("SELECT * FROM tblroom")); ?></h1>
                        <p>Total Rooms</p>
                    </div>
                    <i class="ri-building-line card--icon--lg"></i>
                </div>
            </div>

            <div class="table-container">
                <div class="search-wrapper">
                    <input type="text" class="search-box" placeholder="Search rooms...">
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
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tblroom";
                            $stmt = $pdo->query($sql);
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowroom{$row["Id"]}'>";
                                    echo "<td>" . $row["className"] . "</td>";
                                    echo "<td>" . $row["yearlevelCode"] . "</td>";
                                    echo "<td>" . $row["currentStatus"] . "</td>";
                                    echo "<td>" . $row["capacity"] . "</td>";
                                    echo "<td>" . $row["classification"] . "</td>";
                                    echo "<td><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='room'></i></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' style='text-align: center;'>No records found</td></tr>";
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
                        <option value="" selected>Room type</option>
                        <option value="Normal">Normal</option>
                        <option value="Computer Lab">Computer Laboratory</option>
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

    <script>
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
        });

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

        // Add this to your existing DOMContentLoaded event listener
        const searchBox = document.querySelector('.search-box');
        if (searchBox) {
            searchBox.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('tbody tr');
                
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    </script>

    <?php js_asset(["active_link", "delete_request"]) ?>
</body>

</html>