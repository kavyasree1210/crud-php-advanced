<?php
session_start();
include 'config.php';

if(isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // 🔐 Validation
    if(empty($username) || empty($password)) {
        echo "All fields are required!";
        exit();
    }

    // 🔐 Prepared Statement (Secure)
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // 🔐 Password Verification
        if(password_verify($password, $row['password'])) {

            // 🧠 Store session
            $_SESSION['user'] = $username;
            $_SESSION['role'] = $row['role'];

            header("Location: index.php");
            exit();

        } else {
            echo "Wrong Password!";
        }

    } else {
        echo "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>

<body>

<h2>Login</h2>

<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button name="submit">Login</button>
</form>

<br>
<a href="register.php">Don't have account? Register</a>

</body>
</html>