<?php
session_start(); // Start the session

$host = 'localhost'; // Database host
$dbname = 'edutrack'; // Database name
$dbusername = 'root'; // Database username
$dbpassword = ''; // Database password

// Create a connection to the database
$mysqli = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = $_POST['password']; // Ambil password tanpa hashing

    // Fetch user from the database
    $result = $mysqli->query("SELECT * FROM pengguna WHERE username='$username'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password directly
        if ($password === $user['password']) { // Bandingkan password
            // Start user session or redirect to the dashboard
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username; // Simpan username di session
            $_SESSION['tipe_pengguna'] = $user['tipe_pengguna']; // Simpan tipe pengguna
            switch ($user['tipe_pengguna']) {
                case 'siswa':
                    header('Location: mapel_pilihan.php');
                    break;
                case 'guru':
                    header('Location: guru_dashboard.php');
                    break;
                case 'admin':
                    header('Location: admin_dashboard.php');
                    break;
            }
            exit();
        } else {
            $login_error = "Invalid password!";
        }
    } else {
        $login_error = "User not found!";
    }
}

// Handle password reset request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_otp'])) {
    $username = $mysqli->real_escape_string($_POST['username']);
    
    // Fetch user from the database
    $result = $mysqli->query("SELECT * FROM pengguna WHERE username='$username'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Send OTP via WhatsApp
        $otp = rand(100000, 999999);
        $phone = $user['phone'];
        $merchantKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZGVudGlmaWVyIjo0Mjc5fQ.Buu45WDCUVF_i7KxwoqgAhJPcM1PQVvtenfplZjBG50';
        $gatewayKey = 'bf033aa5-7536-4539-9569-78f431969053';

        // Request OTP
        $ch = curl_init('https://api.fazpass.com/v1/otp/request');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $merchantKey,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'phone' => $phone,
            'otp' => $otp,
            'gateway_key' => $gatewayKey
        ]));
        
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode the response
        $response = json_decode($response, true);
        
        // Check if OTP request was successful
        if (isset($response['success']) && $response['success']) {
            // Save OTP in session for verification
            $_SESSION['otp'] = $otp; 
            $_SESSION['user_id'] = $user['id']; // Save user ID for later use
            $_SESSION['otp_sent'] = true; // Flag for showing OTP form
            $otp_message = "OTP has been sent to your WhatsApp number.";
        } else {
            $otp_error = "Failed to send OTP. Please try again.";
        }
    } else {
        $otp_error = "User not found!";
    }
}

// Handle OTP verification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_otp'])) {
    $entered_otp = $_POST['otp'];
    if ($entered_otp == $_SESSION['otp']) {
        // Proceed to reset password
        unset($_SESSION['otp']); // Remove OTP from session after verification
        $_SESSION['reset_password'] = true; // Flag for displaying reset password form
    } else {
        $otp_error = "Invalid OTP!";
    }
}

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT); // Hash the new password
    $user_id = $_SESSION['user_id']; // Get the user ID from session

    // Update password in the database
    $mysqli->query("UPDATE pengguna SET password='$new_password' WHERE id='$user_id'");
    $reset_message = "Password reset successful. You can now log in.";
    unset($_SESSION['reset_password']); // Remove the reset password flag
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #504E76; /* Pastel Purple */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .outer-container {
            display: flex;
            background-color: #f8f8f8;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 1200px;
            width: 90%;
            height: 600px;
        }
        .image-container {
            flex: 1.5;
            background-image: url('https://i.pinimg.com/736x/5e/58/08/5e58089f91fd44a4040e05b6b6d1afa8.jpg');
            background-size: cover;
            background-position: center;
        }
        .content-container {
            flex: 1;
            background-color: #F8F8F8;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center; /* Center horizontally */
            text-align: center;
        }
        h2 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: #504E76; /* Dark Purple */
            font-weight: 600;
        }
        .form-control {
            padding: 8px; /* Keep padding the same */
            margin-bottom: 10px;
            border: 2px solid #504E76;
            border-radius: 5px;
            font-size: 1rem;
            width: 250px; /* Set a specific width */
            max-width: 100%; /* Ensure it doesn't exceed the parent container */
        }
        .btn-primary {
            background-color: #FCDD9D; /* Light Orange */
            border-color: #FCDD9D;
            color: #504E76;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .btn-success {
            background-color: #F1642E; /* Rich Orange */
            border-color: #F1642E;
            color: #FDF8E2;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .error, .success {
            color: red;
            margin: 10px 0;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="outer-container">
        <div class="image-container"></div>
        <div class="content-container">
            <h2>Login EduTrack</h2>
            <form action="" method="POST" id="login_form" onsubmit="return validateForm(this);">
                <div class="mb-3">
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100" >Login</button>
                <br><br>
                <a href="reset_password.php" onclick="toggleForms()" style="color: #504E76;" >Forgot Password?</a>
                <?php if (isset($login_error)): ?>
                    <div class="error"><?= htmlspecialchars($login_error) ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script>
        // Function to validate form inputs
        function validateForm(form) {
            const username = form.username.value;
            const password = form.password.value;

            if (username.trim() === '' || password.trim() === '') {
                alert('Please fill in all fields.');
                return false;
            }
            return true;
        }

        // Function to toggle visibility of forms
        function toggleForms() {
            var loginForm = document.getElementById('login_form');
            // Add additional functionality if needed for toggling forms
        }
    </script>
</body>
</html>
