<?php
session_start();
include 'db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    // Check if username or email already exists
    $check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $check_result = $stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $error = "Username or email already exists!";
    } else {
        // Insert new user into the database
        $insert_sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'user')";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sss", $username, $password, $email);
        
        if ($stmt->execute()) {
            $success = "Registration successful! <a href='login.php' style='color: #e63946;'>Login here</a>";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - E-Library</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: 'Poppins', sans-serif;
        }
        .register-container {
            max-width: 400px;
            margin: 100px auto;
            background: #222;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(255, 0, 0, 0.5);
        }
        .form-control {
            background: #333;
            color: white;
            border: 1px solid #e63946;
        }
        .btn-register {
            background: #e63946;
            color: white;
            border: none;
            transition: 0.3s;
        }
        .btn-register:hover {
            background: #ff0000;
        }
    </style>
</head>
<body>
    <div class="register-container text-center">
        <h2>Register for E-Library</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"> <?php echo $error; ?> </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"> <?php echo $success; ?> </div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-register w-100">Register</button>
        </form>
        <p class="mt-3">Already have an account? <a href="login.php" style="color: #e63946;">Login here</a></p>
    </div>
</body>
</html>