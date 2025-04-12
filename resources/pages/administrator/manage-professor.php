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

            /* Search Bar Colors - Constant */
            --search-bg: #ffffff;
            --search-text: #333333;
            --search-border: #dcdcdc;
            --search-placeholder: #666666;
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
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.2);
            --shadow-md: 0 5px 15px rgba(0,0,0,0.3);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.4);
        }

        /* Main Content */
        .main--content {
            background-color: var(--light-bg);
            padding: 2rem;
            transition: var(--transition);
        }

        /* Search Bar */
        .search-wrapper {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .search-box {
            background-color: var(--search-bg) !important;
            border: 1px solid var(--search-border) !important;
            color: var(--search-text) !important;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            width: 300px;
            transition: var(--transition);
        }

        .search-box:focus {
            outline: none;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
        }

        /* Overview Cards */
        .cards {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 1.5rem;
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

        .card--icon--lg {
            font-size: 3rem;
            color: var(--primary-color);
            opacity: 0.8;
            order: 2;
            margin-left: 2rem;
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

        /* Table Container */
        .table-container {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-md);
            padding: 2rem;
            margin-top: 2rem;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .table-container:hover {
            box-shadow: var(--shadow-lg);
        }

        /* Table Styles */
        .table {
            width: 100%;
            overflow-x: auto;
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
            transition: var(--transition);
        }

        tbody tr {
            transition: var(--transition);
        }

        tbody tr:hover {
            background-color: var(--background-color);
            transform: scale(1.01);
            box-shadow: var(--shadow-sm);
        }

        /* Buttons */
        .add {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(229, 9, 20, 0.2);
        }

        .add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(229, 9, 20, 0.3);
        }

        .delete {
            background-color: var(--primary-color);
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            border: none;
        }

        .delete:hover {
            background-color: var(--dark-red);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3);
        }

        /* Add loading state */
        .delete.loading {
            opacity: 0.7;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Section Title */
        .section--title {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section--title::before {
            content: '';
            width: 4px;
            height: 24px;
            background-color: var(--primary-color);
            border-radius: 2px;
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

            .search-wrapper {
                flex-direction: column;
                gap: 1rem;
            }

            .search-box {
                width: 100%;
            }

            .cards {
                padding: 1rem;
            }

            .table-container {
                padding: 1rem;
            }

            th, td {
                padding: 0.8rem;
            }

            .card--content h1 {
                font-size: 2rem;
            }
        }

        /* Modal Overlay */
        .modal-overlay {
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

        /* Form Modal */
        .formDiv-- {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            display: none;
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -48%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        /* Form Container */
        .form-container {
            padding: 2rem;
        }

        /* Form Header */
        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .form-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .form-title p {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .form-title i {
            font-size: 1.8rem;
            color: var(--primary-color);
        }

        .close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: rgba(229, 9, 20, 0.1);
            color: var(--primary-color);
            transition: var(--transition);
        }

        .close:hover {
            background: var(--primary-color);
            color: white;
            transform: rotate(90deg);
        }

        /* Form Content */
        .form-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--background-color);
            color: var(--text-primary);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
            outline: none;
        }

        /* Dataset Section */
        .dataset-section {
            background: var(--background-color);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            border: 1px solid var(--border-color);
        }

        .dataset-header {
            margin-bottom: 1rem;
        }

        .dataset-header h3 {
            color: var(--text-primary);
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .dataset-header p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .image-box {
            width: 100%;
            height: 200px;
            border: 2px dashed var(--border-color);
            border-radius: var(--border-radius);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            gap: 1rem;
        }

        .image-box:hover {
            border-color: var(--primary-color);
            background: rgba(229, 9, 20, 0.05);
        }

        .image-box i {
            font-size: 3rem;
            color: var(--primary-color);
        }

        .image-box p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        #multiple-images {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .captured-image {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        /* Form Actions */
        .form-actions {
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .btn-cancel {
            padding: 0.8rem 1.5rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: transparent;
            color: var(--text-secondary);
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-cancel:hover {
            background: var(--light-bg);
            color: var(--text-primary);
        }

        .btn-submit {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            background: var(--primary-color);
            color: white;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
        }

        .btn-submit:hover {
            background: var(--dark-red);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.3);
        }
    </style>
</head>

<body>
    <?php include 'includes/topbar.php'; ?>

    <section class="main">
        <?php include "Includes/sidebar.php"; ?>

        <div class="main--content">
            <div class="title">
                <h2 class="section--title">MANAGE PROFESSORS</h2>
                <button id="theme-toggle" class="theme-toggle" title="Toggle dark/light mode">
                    <i class="ri-sun-line" id="theme-icon"></i>
                </button>
            </div>


            <div class="overview">
                <div class="cards">
                    <div class="card--data">
                        <div class="card--content">
                            <button class="add" id="showButton">
                                <i class="ri-add-line"></i>Add Professor
                            </button>
                            <h1><?php echo count(fetch("SELECT * FROM tblprofessor")); ?></h1>
                            <p>Total Professors</p>
                        </div>
                        <i class="ri-user-line card--icon--lg"></i>
                    </div>
                </div>
            </div>

            <div class="table-container">
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tblprofessor";
                            $result = fetch($sql);
                            if ($result) {
                                foreach ($result as $row) {
                                    echo "<tr id='rowprofessor{$row["Id"]}'>";
                                    echo "<td>" . htmlspecialchars($row["registrationNumber"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["firstName"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["lastName"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["yearlevel"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["courseCode"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                    echo "<td><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='professor'>Delete</i></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' style='text-align: center;'>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-overlay" id="modalOverlay"></div>

            <div class="formDiv--" id="form">
                <div class="form-container">
                    <form method="post">
                        <div class="form-header">
                            <div class="form-title">
                                <i class="ri-user-add-line"></i>
                                <p>Add New Professor</p>
                            </div>
                            <div class="close">&times;</div>
                        </div>

                        <div class="form-content">
                            <div class="form-left">
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
                                    <label for="registrationNumber">Registration Number</label>
                                    <input type="text" id="registrationNumber" name="registrationNumber" placeholder="Enter registration number" required>
                                    <p id="error" style="color: var(--primary-color); font-size: 0.9rem; margin-top: 0.5rem; display: none;">Invalid characters in registration number.</p>
                                </div>

                                <div class="form-group">
                                    <label for="yearlevel">Year Level</label>
                                    <select id="yearlevel" name="yearlevel" required>
                                        <option value="" selected disabled>Select Year Level</option>
                                        <?php
                                        $yearlevelNames = getyearlevelNames();
                                        foreach ($yearlevelNames as $yearlevel) {
                                            echo '<option value="' . $yearlevel["yearlevelCode"] . '">' . $yearlevel["yearlevelName"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="course">Course</label>
                                    <select id="course" name="course" required>
                                        <option value="" selected disabled>Select Course</option>
                                        <?php
                                        $courseNames = getCourseNames();
                                        foreach ($courseNames as $course) {
                                            echo '<option value="' . $course["courseCode"] . '">' . $course["name"] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="dataset-section">
                                <div class="dataset-header">
                                    <h3>Professor Dataset</h3>
                                    <p>Capture multiple images for facial recognition</p>
                                </div>

                                <div id="open_camera" class="image-box" onclick="takeMultipleImages()">
                                    <i class="ri-camera-line"></i>
                                    <p>Click to capture images</p>
                                </div>

                                <div id="multiple-images">
                                    <!-- Captured images will appear here -->
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-cancel">Cancel</button>
                            <button type="submit" class="btn-submit" name="addprofessor">Save Professor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php js_asset(["admin_functions", "delete_request", "script", "active_link"]) ?>

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

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const tableRows = document.querySelectorAll('tbody tr');
                    
                    tableRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }

            // Initialize table row animations
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    cell.style.setProperty('--td-index', index);
                });
            });

            // Delete functionality
            const deleteButtons = document.querySelectorAll('.delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const professorId = this.getAttribute('data-id');
                    const row = document.getElementById(`rowprofessor${professorId}`);
                    
                    if (confirm('Are you sure you want to delete this professor?')) {
                        fetch('delete_professor.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `id=${professorId}&type=professor`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the row from the table
                                row.remove();
                                // Update the total count
                                const totalElement = document.querySelector('.card--content h1');
                                if (totalElement) {
                                    const currentTotal = parseInt(totalElement.textContent);
                                    totalElement.textContent = (currentTotal - 1).toString();
                                }
                                // Show success message
                                alert('Professor deleted successfully');
                            } else {
                                alert(data.message || 'Error deleting professor');
                            }
                        })
                    }
                });
            });
        });
    </script>
</body>
</html>