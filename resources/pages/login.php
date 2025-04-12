<?php

// Handle user login logic

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $userType = $_POST['user_type'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($password)) {
        $errors['password'] = 'Password cannot be empty';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        exit();
    }

    if ($userType == "administrator") {
        $stmt = $pdo->prepare("SELECT * FROM tbladmin WHERE emailAddress = :email");
    } elseif ($userType == "depthead") {
        $stmt = $pdo->prepare("SELECT * FROM tbldepthead WHERE emailAddress = :email");
    }
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['Id'],
            'email' => $user['emailAddress'],
            'name' => $user['firstName'],
            'role' => $userType,
        ];

        header('Location: home');
        exit();
    } else {
        $errors['login'] = 'Invalid email or password';
        $_SESSION['errors'] = $errors;
    }
}
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
}

function display_error($error, $is_main = false)
{
    global $errors;
    if (isset($errors["{$error}"])) {
        echo '<div class="' . ($is_main ? 'error-main' : 'error') . '">
                  <p>' . $errors["{$error}"] . '</p>
           </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="resources/images/tablogo.png" type="image/svg+xml">
    <title>ATTENDIFY</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="resources/assets/css/login_styles.css">
    <style>
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
            width: 1250px;
            height: 700px;
            display: flex;
            box-shadow: 0 70px 10px rgba(0, 0, 0, 0.2);
            border-radius: 20px;
            overflow: hidden;
            background-color:rgb(255, 255, 255);
            backdrop-filter: blur(5px);
        }

        .sign-in-container {
            flex: 1;
            background:rgb(253, 253, 253);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px 50px;
        }

        .sign-in-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .sign-in-container form {
            width: 100%;
            margin-top: -10px;
        }

        .sign-in-container .input-group {
            margin-bottom: 12px;
            position: relative;
        }

        .sign-in-container .input-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 14px;
        }

        .sign-in-container input,
        .sign-in-container select {
            width: 100%;
            padding: 10px 15px 10px 35px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background-color: rgba(255, 255, 255, 0.8);
            height: 40px;
        }

        .sign-in-container .btn {
            width: 100%;
            padding: 10px;
            border: none;
            background: #8d1c1c;
            color: #ffffff;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        .sign-in-container .btn:hover {
            background: #e04326;
        }

        .sign-in-container .icons {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .sign-in-container .icons i {
            margin: 0 10px;
            font-size: 20px;
            color: #666;
            cursor: pointer;
        }

        .sign-in-container .recover {
            text-align: right;
            margin-top: 10px;
        }

        .sign-in-container .recover a {
            color: #ff4b2b;
            text-decoration: none;
            font-size: 12px;
        }

        .overlay-container {
            flex: 1;
            background: linear-gradient(to right, #ff4b2b, #ff416c);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 0 50px;
            text-align: center;
        }

        .overlay-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .overlay-container p {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .overlay-container .btn {
            padding: 10px 20px;
            border: 1px solid #ffffff;
            background: transparent;
            color: white;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
        }

        .overlay-container .btn:hover {
            background: white;
            color: #ff4b2b;
        }
        /* Responsive Design */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
        height: auto;
    }

    .sign-in-container,
    .overlay-container {
        padding: 40px 20px;
    }

    .sign-in-container .btn,
    .overlay-container .btn {
        max-width: 100%;
    }
}

@media (max-width: 480px) {
    .sign-in-container h1,
    .overlay-container h1 {
        font-size: 20px;
    }

    .sign-in-container input,
    .sign-in-container select {
        font-size: 10px;
    }

    .sign-in-container .btn,
    .overlay-container .btn {
        padding: 8px;
        font-size: 12px;
    }
}
.sign-in-container .logo {
    width: 100px;
    margin-bottom: 10px;
}

.datetime-container {
    text-align: center;
    padding: 20px;
    margin-bottom: 20px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.5s ease;
}

.time {
    font-size: 7rem;
    font-weight: 700;
    color: #8d1c1c;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    font-family: 'Digital', sans-serif;
    letter-spacing: 2px;
    margin-bottom: 5px;
}

.date {
    font-size: 1.2rem;
    color: #666;
    font-weight: 500;
    color: #8d1c1c;
    text-transform: uppercase;
    letter-spacing: 1px;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

.datetime-container:hover {
    animation: pulse 1s infinite;
}

/* Digital font for clock */
@font-face {
    font-family: 'Digital';
    src: url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&display=swap');
}

/* Responsive design */
@media (max-width: 768px) {
    .time {
        font-size: 2.5rem;
    }
    .date {
        font-size: 1rem;
    }
}

/* Updated input group styles */
.input-group {
    position: relative;
    margin-bottom: 25px;
    transition: all 0.3s ease;
}

.input-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: rgba(141, 28, 28, 0.1);
    transition: all 0.3s ease;
}

.input-icon i {
    font-size: 14px;
    color: #8d1c1c;
    transition: all 0.3s ease;
}

/* User icon specific styling */
.user-icon {
    background: rgba(141, 28, 28, 0.1);
}

.user-icon i {
    color: #8d1c1c;
}

/* Email icon specific styling */
.email-icon {
    background: rgba(141, 28, 28, 0.1);
}

.email-icon i {
    color: #8d1c1c;
}

/* Password icon specific styling */
.password-icon {
    background: rgba(141, 28, 28, 0.1);
}

.password-icon i {
    color: #8d1c1c;
}

/* Input field styling */
.input-group input,
.input-group select {
    width: 100%;
    padding: 15px 20px 15px 55px;
    border: 2px solid rgba(141, 28, 28, 0.2);
    border-radius: 15px;
    font-size: 15px;
    background: rgba(255, 255, 255, 0.9);
    transition: all 0.3s ease;
    height: 55px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

/* Hover effects */
.input-group:hover .input-icon {
    transform: translateY(-50%) scale(1.1);
    background: rgba(141, 28, 28, 0.2);
}

.input-group:hover input,
.input-group:hover select {
    border-color: rgba(141, 28, 28, 0.4);
    box-shadow: 0 6px 12px rgba(141, 28, 28, 0.08);
}

/* Focus effects */
.input-group input:focus,
.input-group select:focus {
    border-color: #8d1c1c;
    box-shadow: 0 8px 16px rgba(141, 28, 28, 0.1);
    transform: translateY(-2px);
}

.input-group input:focus + .input-icon,
.input-group select:focus + .input-icon {
    background: #8d1c1c;
    transform: translateY(-50%) scale(1.2);
}

.input-group input:focus + .input-icon i,
.input-group select:focus + .input-icon i {
    color: white;
}

/* Animation for icons */
@keyframes iconPulse {
    0% { transform: translateY(-50%) scale(1); }
    50% { transform: translateY(-50%) scale(1.1); }
    100% { transform: translateY(-50%) scale(1); }
}

.input-icon:hover {
    animation: iconPulse 1s infinite;
}

/* Placeholder Styling */
.input-group input::placeholder {
    color: #999;
    font-size: 15px;
    font-weight: 400;
    transition: all 0.3s ease;
}

/* Focus Effects */
.input-group input:focus,
.input-group select:focus {
    border-color: #8d1c1c;
    box-shadow: 0 8px 15px rgba(141, 28, 28, 0.1);
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 1);
}

.input-group input:focus::placeholder {
    opacity: 0.7;
    transform: translateX(5px);
}

.input-group input:focus + i,
.input-group select:focus + i {
    color: #8d1c1c;
    transform: translateY(-50%) scale(1.2);
    opacity: 1;
}

/* Hover Effects */
.input-group:hover input,
.input-group:hover select {
    border-color: rgba(141, 28, 28, 0.4);
    box-shadow: 0 6px 12px rgba(141, 28, 28, 0.08);
}

.input-group:hover i {
    transform: translateY(-50%) scale(1.1);
    opacity: 1;
}

/* Select Dropdown Styling */
.input-group select {
    appearance: none;
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%238d1c1c' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 20px center;
    padding-right: 50px;
}

.input-group select option {
    font-size: 15px;
    padding: 10px;
    background: white;
    color: #333;
}

/* Animation for focus */
@keyframes inputFocus {
    0% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
    100% { transform: translateY(0); }
}

.input-group input:focus,
.input-group select:focus {
    animation: inputFocus 0.4s ease;
}

/* Glass morphism effect */
.input-group::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 20px;
    z-index: -1;
    background: linear-gradient(
        135deg,
        rgba(255, 255, 255, 0.1),
        rgba(255, 255, 255, 0.05)
    );
    backdrop-filter: blur(10px);
}

/* Error state styling */
.input-group.error input,
.input-group.error select {
    border-color: #dc3545;
    background-color: rgba(220, 53, 69, 0.05);
}

.input-group.error i {
    color: #dc3545;
}

/* Form container enhancement */
form {
    background: rgba(255, 255, 255, 0.95);
    padding: 30px;
    border-radius: 25px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}

/* Add floating label animation */
.input-group label {
    position: absolute;
    left: 50px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 15px;
    color: #999;
    pointer-events: none;
    transition: all 0.3s ease;
}

.input-group input:focus ~ label,
.input-group input:valid ~ label {
    top: 0;
    left: 20px;
    font-size: 12px;
    color: #8d1c1c;
    background: white;
    padding: 0 5px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .input-group input,
    .input-group select {
        height: 50px;
        font-size: 15px;
    }
    
    .input-group i {
        font-size: 16px;
    }
}

/* Updated button style */
.btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 10px;
    background: #8d1c1c;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn:hover {
    background: #7a1818;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(141, 28, 28, 0.2);
}

/* Error message styling */
.error {
    background: #fff3f3;
    color: #dc3545;
    padding: 8px 12px;
    border-radius: 8px;
    margin-bottom: 10px;
    border-left: 4px solid #dc3545;
    font-size: 14px;
}

.signup-section {
    margin-top: 12px;
    text-align: center;
}

.signup-section p {
    color: #666;
    font-size: 14px;
    margin: 0;
}

.signup-link {
    color: #8d1c1c;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-left: 5px;
}

.signup-link:hover {
    color: #7a1818;
    text-decoration: underline;
}

.login-btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 10px;
    background: #8d1c1c;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    position: relative;
    overflow: hidden;
}

/* Add pulse animation */
.login-btn:hover {
    background: #7a1818;
    animation: loginPulse 1.5s infinite;
    box-shadow: 0 5px 15px rgba(141, 28, 28, 0.2);
}

.login-btn:active {
    transform: scale(0.95);
}

/* Login button specific animation */
@keyframes loginPulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

/* Add ripple effect */
.login-btn::after {
    content: '';
    position: absolute;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: scale(0);
    animation: ripple 0.6s linear;
    opacity: 1;
}

@keyframes ripple {
    0% {
        transform: scale(0);
        opacity: 1;
    }
    100% {
        transform: scale(4);
        opacity: 0;
    }
}

/* Optional: Add icon to login button */
.login-btn::before {
    content: '\f2f6'; /* Login icon from Font Awesome */
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    margin-right: 8px;
    font-size: 18px;
}

.signup-btn {
    padding: 10px 30px;
    border: 2px solid #8d1c1c;
    border-radius: 10px;
    background: transparent;
    color: #8d1c1c;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.signup-btn:hover {
    background: rgba(141, 28, 28, 0.1);
    transform: translateY(-2px);
}

.attendance-btn {
    margin-top: 20px;
    padding: 12px 25px;
    background: #8d1c1c;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
}

.attendance-btn:hover {
    background: #7a1818;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(141, 28, 28, 0.2);
}

.attendance-btn i {
    font-size: 18px;
}

/* Animation for the button */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.attendance-btn:active {
    transform: scale(0.95);
}

.attendance-btn:hover {
    animation: pulse 1.5s infinite;
}

/* Header Content Styling */
.header-content {
    text-align: center;
    margin-bottom: 7px;
    padding: 10px;
}

/* ATTENDIFY Title Styling */
.attendify-title {
    font-size: 2.8rem;
    margin-bottom: 5px;
    font-weight: 800;
    margin: 0;
    padding: 0;
    color: #8d1c1c;
    letter-spacing: 4px;
    position: relative;
    text-shadow: 2px 2px 4px rgba(141, 28, 28, 0.1);
}

.attendify-title span {
    display: inline-block;
    animation: float 5s ease-in-out infinite;
    animation-delay: calc(0.1s * var(--i));
    transition: all 0.3s ease;
}

.attendify-title span:nth-child(1) { --i: 1; }
.attendify-title span:nth-child(2) { --i: 2; }
.attendify-title span:nth-child(3) { --i: 3; }
.attendify-title span:nth-child(4) { --i: 4; }
.attendify-title span:nth-child(5) { --i: 5; }
.attendify-title span:nth-child(6) { --i: 6; }
.attendify-title span:nth-child(7) { --i: 7; }
.attendify-title span:nth-child(8) { --i: 8; }
.attendify-title span:nth-child(9) { --i: 9; }

/* University Title Styling */
.university-title {
    margin-top: 5px;
    padding: 5px 0;
    position: relative;
}

.university-name {
    font-size: 1.2rem;
    margin-bottom: 15px;
    font-weight: 600;
    color: #333;
    margin: 0;
    padding: 0;
    letter-spacing: 1px;
    animation: slideIn 1s ease;
}

.campus-name {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 5px;
}

.campus-name h5 {
    font-size: 1.1rem;
    font-weight: 500;
    color: #666;
    margin: 0;
    padding: 0;
    letter-spacing: 1.5px;
    animation: fadeIn 1.2s ease;
}

.dash {
    color: #8d1c1c;
    font-weight: 300;
    animation: width 1.5s ease;
}

/* Animations */
@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes width {
    from {
        width: 0;
        opacity: 0;
    }
    to {
        width: 20px;
        opacity: 1;
    }
}

/* Hover Effects */
.attendify-title:hover span {
    color: #7a1818;
    text-shadow: 3px 3px 6px rgba(141, 28, 28, 0.2);
    transform: translateY(-5px);
}

.university-name:hover {
    color: #8d1c1c;
    transition: color 0.3s ease;
}

/* Decorative Elements */
.attendify-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60%;
    height: 0.5px;
    background: linear-gradient(90deg, transparent, #8d1c1c, transparent);
    animation: fadeIn 1.5s ease;
}

/* Responsive Design */
@media (max-width: 768px) {
    .attendify-title {
        font-size: 2.5rem;
        letter-spacing: 2px;
    }

    .university-name {
        font-size: 1.2rem;
    }

    .campus-name h5 {
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .attendify-title {
        font-size: 2rem;
        letter-spacing: 1px;
    }

    .university-name {
        font-size: 1rem;
    }

    .campus-name h5 {
        font-size: 0.9rem;
    }
}
    </style>
</head>

<body>
    <div class="container">
        <div class="sign-in-container">
            <img src="resources/images/tablogo.png" alt="ATTENDIFY Logo" class="logo">
            <div class="header-content">
                <h1 class="attendify-title">
                    <span>A</span>
                    <span>T</span>
                    <span>T</span>
                    <span>E</span>
                    <span>N</span>
                    <span>D</span>
                    <span>I</span>
                    <span>F</span>
                    <span>Y</span>
                </h1>
                <div class="university-title">
                    <h4 class="university-name">Technological University of the Philippines</h4>
                    
                </div>
            </div>
            <?php display_error('login', true); ?>
            <form method="POST" action="">
                <div class="input-group">
                    <select name="user_type" required>
                        <option value="">Select User</option>
                        <option value="administrator">Administrator</option>
                        <option value="depthead">Department Head</option>
                    </select>
                    <div class="input-icon user-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
                
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                    <div class="input-icon email-icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <?php display_error('email'); ?>
                </div>
                
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                    <div class="input-icon password-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <?php display_error('password'); ?>
                </div>
                
                <input type="submit" class="btn login-btn" value="Login" name="login">
            </form>

            <div class="signup-section">
                <p>Don't have an account? <a href="signup.php?request_site=attendance" class="signup-link">Sign Up</a></p>
            </div>
        </div>

        <!-- Overlay Section -->
        <div class="overlay-container">
            <div class="datetime-container">
                <div class="time" id="current-time">00:00:00</div>
                <div class="date" id="current-date">Loading...</div>
            </div>
        </div>
    </div>

    <script>
        function updateDateTime() {
            const now = new Date();
            
            // Update time
            const timeElement = document.getElementById('current-time');
            timeElement.textContent = now.toLocaleTimeString('en-US', {
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            
            // Update date
            const dateElement = document.getElementById('current-date');
            dateElement.textContent = now.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Add blinking effect for the time separators
            const time = timeElement.textContent;
            timeElement.innerHTML = time.replace(/:/g, '<span class="blink">:</span>');
        }

        // Update every second
        setInterval(updateDateTime, 1000);
        
        // Initial update
        updateDateTime();

        // Add this if you want the colons to blink
        const style = document.createElement('style');
        style.textContent = `
            .blink {
                animation: blink 1s infinite;
            }
            @keyframes blink {
                50% { opacity: 0; }
            }
        `;
        document.head.appendChild(style);

        function markAttendance() {
            // Add your attendance marking logic here
            window.location.href = 'attendance.php'; // Or your attendance page URL
        }
    </script>
</body>

</html>
