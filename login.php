<?php
session_start();
include 'config.php';

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verify password
        if(password_verify($password, $row['password'])){
            $_SESSION['user'] = $username;
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

<h2>Login</h2>

<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button name="submit">Login</button>
</form>

<br>
<a href="register.php">Don't have account? Register</a>