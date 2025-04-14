<?php
// Handle user login and registration logic

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
            $stmt = $pdo->prepare("INSERT INTO tbldepthead (firstName, lastName, emailAddress, password, phoneNumber, yearLevelCode) VALUES (:firstName, :lastName, :email, :password, :phoneNumber, :yearLevelCode)");
            $stmt->execute([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => $hashedPassword,
                'phoneNumber' => $phoneNumber,
                'yearLevelCode' => $yearLevelCode,
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
    <link rel="stylesheet" href="resources/assets/css/login_styles.css">
    <title>ATTENDIFY - Login and Sign Up</title>
</head>

<body>
    <div class="container">
        <!-- Sign-in Section -->
        <div class="sign-in-container">
            <h1>Sign In</h1>
            <form method="POST">
                <!-- Existing login form -->
            </form>
        </div>

        <!-- Sign-up Section -->
        <div class="sign-up-container">
            <h1>Sign Up</h1>
            <form method="POST">
                <div class="input-group">
                    <select name="user_type" required>
                        <option value="">Select User</option>
                        <option value="administrator">Administrator</option>
                        <option value="depthead">Department Head</option>
                    </select>
                </div>
                <div class="input-group">
                    <input type="text" name="first_name" placeholder="First Name" required>
                </div>
                <div class="input-group">
                    <input type="text" name="last_name" placeholder="Last Name" required>
                </div>
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="input-group">
                    <input type="text" name="phone_number" placeholder="Phone Number (For Dept Head)">
                </div>
                <div class="input-group">
                    <input type="text" name="year_level_code" placeholder="Year Level Code (For Dept Head)">
                </div>
                <input type="submit" class="btn" name="signup" value="Sign Up">
            </form>
        </div>
    </div>
</body>

</html>
