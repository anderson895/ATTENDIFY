<?php
session_start(); // Ensure session is started

// Create the PDO connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=attendify', 'root', ''); // Replace 'attendify' with your database name
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage()); // Handle connection errors
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Login logic (unchanged)
    } elseif (isset($_POST['signup'])) {
        // Sign-up logic
        $userType = $_POST['user_type'];
        $firstName = trim($_POST['first_name']);
        $lastName = trim($_POST['last_name']);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $phoneNumber = $_POST['phone_number'] ?? null;
        $yearLevelCode = $_POST['year_level_code'] ?? null;

        if (empty($firstName) || empty($lastName)) {
            $errors['name'] = 'First and last names are required';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        if (empty($password)) {
            $errors['password'] = 'Password cannot be empty';
        }

        if ($userType === 'depthead') {
            if (empty($phoneNumber)) {
                $errors['phone_number'] = 'Phone number is required for Department Head';
            }
            if (empty($yearLevelCode)) {
                $errors['year_level_code'] = 'Year level code is required for Department Head';
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            exit();
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($userType == "administrator") {
            $stmt = $pdo->prepare("INSERT INTO tbladmin (firstName, lastName, emailAddress, password) VALUES (:firstName, :lastName, :email, :password)");
            $stmt->execute([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => $hashedPassword,
            ]);
        } elseif ($userType == "depthead") {
            $stmt = $pdo->prepare("INSERT INTO tbldepthead (firstName, lastName, emailAddress, password, phoneNumber) VALUES (:firstName, :lastName, :email, :password, :phoneNumber)");
            $stmt->execute([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => $hashedPassword,
                'phoneNumber' => $phoneNumber,
            ]);
        }

        $_SESSION['success'] = 'Account created successfully';
        header('Location: login.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATTENDIFY - Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('resources/images/tup.gif');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #dd797e;
            color: #333;
        }
        .container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 1200px;
            display: flex;
            overflow: hidden;
            min-height: 600px;
        }

        .form-section {
            flex: 1;
            padding: 40px;
            position: relative;
        }

        .form-section h1 {
            font-size: 2.5rem;
            color: #8d1c1c;
            margin-bottom: 1.5rem;
            font-weight: 600;
            position: relative;
        }

        .form-section h1::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 60px;
            height: 4px;
            background: #8d1c1c;
            border-radius: 2px;
        }

        .input-group {
            position: relative;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .field-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            background: rgba(141, 28, 28, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .field-icon i {
            font-size: 18px;
            color: #8d1c1c;
            transition: all 0.3s ease;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 15px 15px 15px 65px;
            border: 2px solid rgba(141, 28, 28, 0.2);
            border-radius: 15px;
            font-size: 15px;
            background: white;
            transition: all 0.3s ease;
            height: 55px;
        }

        .input-group:hover .field-icon {
            transform: translateY(-50%) scale(1.1);
            background: rgba(141, 28, 28, 0.2);
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #8d1c1c;
            box-shadow: 0 0 0 4px rgba(141, 28, 28, 0.1);
            transform: translateY(-2px);
        }

        .input-group input:focus + .field-icon {
            background: #8d1c1c;
            transform: translateY(-50%) scale(1.1) rotate(360deg);
        }

        .input-group input:focus + .field-icon i {
            color: white;
        }

        /* Field animations */
        @keyframes fieldFloat {
            0% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0); }
        }

        @keyframes iconPulse {
            0% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-50%) scale(1.1); }
            100% { transform: translateY(-50%) scale(1); }
        }

        @keyframes iconSpin {
            from { transform: translateY(-50%) rotate(0deg); }
            to { transform: translateY(-50%) rotate(360deg); }
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            background: linear-gradient(135deg, #8d1c1c, #dd797e);
            color: white;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-top: 1rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(141, 28, 28, 0.2);
        }

        .illustration {
            flex: 1;
            background: linear-gradient(135deg, 
                rgba(141, 28, 28, 0.85), 
                rgba(221, 121, 126, 0.85)
            ),
            url('resources/images/tupmanila11122.png');
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            transition: all 0.5s ease;
        }

        .illustration img {
            display: none;
        }

        .illustration::after {
            position: absolute;
            bottom: 40px;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            opacity: 0;
            animation: fadeIn 1s ease forwards;
        }

        @keyframes fadeIn {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        .illustration:hover {
            background-blend-mode: soft-light;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .illustration {
                min-height: 300px;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-section {
            animation: fadeIn 0.5s ease;
        }

        /* Floating labels */
        .input-group {
            position: relative;
        }

        .input-group label {
            position: absolute;
            left: 16px;
            top: -10px;
            background: white;
            padding: 0 5px;
            font-size: 14px;
            color: #666;
            z-index: 1;
        }

        /* Add these styles for the back button */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(141, 28, 28, 0.1);
            border: none;
            border-radius: 12px;
            color: #8d1c1c;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 100;
            text-decoration: none;
        }

        .back-button i {
            font-size: 18px;
            transition: transform 0.3s ease;
        }

        .back-button:hover {
            background: rgba(165, 19, 19, 0.2);
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(141, 28, 28, 0.15);
        }

        .back-button:hover i {
            transform: translateX(-3px);
        }

        /* Add animation for the back button */
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .back-button {
            animation: slideInLeft 0.5s ease forwards;
        }

        /* Add hover effect */
        .back-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(141, 28, 28, 0.1) 0%,);
            border-radius: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .back-button:hover::before {
            opacity: 100;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .back-button {
                top: 15px;
                left: 15px;
                padding: 8px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <a href="login.php" class="back-button">
        <i class="fas fa-arrow-left"></i>
        Back to Login
    </a>
    <div class="container">
        <div class="form-section">
            <h1>New Account</h1>
            <form method="POST" class="signup-form">
                <div class="input-group">
                    <select name="user_type" required>
                        <option value="">Select User Type</option>
                        <option value="administrator">Administrator</option>
                    </select>
                    <div class="field-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
                <div class="input-group">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <div class="field-icon">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div class="input-group">
                    <input type="text" name="last_name" placeholder="Last Name" required>
                    <div class="field-icon">
                        <i class="fas fa-user-tag"></i>
                    </div>
                </div>
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email Address" required>
                    <div class="field-icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Create Password" required>
                    <div class="field-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
                <div class="input-group">
                    <input type="tel" name="phone_number" placeholder="Phone Number">
                    <div class="field-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                </div>
                <button type="submit" class="btn" name="signup">
                    <i class="fas fa-user-plus"></i>
                    Create Account
                </button>
            </form>
        </div>
        <div class="illustration">
            <img src="resources\images\tupmanila11122.png" alt="Sign Up Illustration">
        </div>
    </div>
    <script>
        // Add hover animations to input groups
        document.querySelectorAll('.input-group').forEach(group => {
            group.addEventListener('mouseover', () => {
                const icon = group.querySelector('.field-icon i');
                icon.style.animation = 'iconPulse 1s infinite';
            });

            group.addEventListener('mouseout', () => {
                const icon = group.querySelector('.field-icon i');
                icon.style.animation = '';
            });
        });

        // Add focus animations
        document.querySelectorAll('.input-group input, .input-group select').forEach(input => {
            input.addEventListener('focus', () => {
                const icon = input.nextElementSibling;
                icon.style.animation = 'iconSpin 0.5s ease-out';
            });

            input.addEventListener('blur', () => {
                const icon = input.nextElementSibling;
                icon.style.animation = '';
            });
        });

        // Add floating animation to the form on load
        document.querySelector('.signup-form').style.animation = 'fieldFloat 3s ease-in-out infinite';
    </script>
</body>

</html>
