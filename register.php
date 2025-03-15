<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "flat_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Ensure all fields are filled
    if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        
        // Ensure passwords match
        if ($password === $confirm_password) {

            // Prepare and bind statement (storing plain text password)
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, confirm_password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $password, $confirm_password);

            if ($stmt->execute()) {
                $success = "✅ User registered successfully!";
            } else {
                $error = "❌ Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error = "❌ Passwords do not match!";
        }
    } else {
        $error = "❌ All fields are required!";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('https://plus.unsplash.com/premium_photo-1683141219653-fc199f8ae98f?q=80&w=2021&auto=format&fit=crop') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h4 class="text-center">Register</h4>
        
        <?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
        <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form method="post" class="mt-3 p-3">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
            <a class="btn btn-danger w-100 mt-2" href="homepage.html">Back</a>
        </form>
    </div>
</body>
</html>
