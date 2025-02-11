<?php 
session_start();
include "config2.php"; // Pastikan $conn terhubung ke database

// Redirect jika user belum melalui tahap verifikasi OTP
if (!isset($_SESSION['phone'])) {
    header('Location: login.php');
    exit;
}

// Ambil nomor telepon dari session
$phone = $_SESSION['phone'];

// Pesan notifikasi
$message = '';
$hashed_password = ''; // Initialize to avoid "undefined variable" notice
$debug_info = ''; // Variable to collect debug info

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Pastikan password cocok
    if ($new_password === $confirm_password) {
        // Debugging: Lihat password yang diterima
        $debug_info .= "DEBUG - Password before hashing: " . htmlspecialchars($new_password) . "<br>";

        // Hash password baru
        $hashed_password = $new_password;
        
        // Debugging: Lihat password setelah hashing
        $debug_info .= "DEBUG - Password hashed: " . htmlspecialchars($hashed_password) . "<br>";
        
        // Prepare dan eksekusi statement SQL untuk memperbarui password
        $stmt = $conn->prepare("UPDATE pengguna SET password = ? WHERE phone = ?");
        $stmt->bind_param("ss", $hashed_password, $phone);

        if ($stmt->execute()) {
            // Berhasil memperbarui password tanpa mengecek affected_rows
            $message = "Password berhasil diupdate. Silakan login.";
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit;
        } else {
            $message = "Error executing update: " . htmlspecialchars($stmt->error);
        }
        $stmt->close();
    } else {
        $message = "Password tidak cocok. Silakan coba lagi.";
    }
}

// Debugging: Outputkan nilai session dan query
$debug_info .= "Phone in session: " . htmlspecialchars($phone) . "<br>";
if ($hashed_password) {
    $debug_info .= "New Password (hashed): " . htmlspecialchars($hashed_password) . "<br>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
            background-color: #504E76;
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
            color: #504E76;
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
            background-color: #FCDD9D;
            border-color: #FCDD9D;
            color: #504E76;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .btn-success {
            background-color: #F1642E;
            border-color: #F1642E;
            color: #FDF8E2;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .otp-input {
            width: 35px;
            height: 35px;
            font-size: 1.1rem;
            text-align: center;
            margin-right: 4px;
            border: 2px solid #504E76;
            border-radius: 5px;
        }
        .shake {
            animation: shake 0.3s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
        }
    </style>
</head>
<body>
    <div class="outer-container">
        <div class="image-container"></div> <!-- Image container with fixed dimensions -->
        <div class="content-container">
            <h2>Reset Password</h2>
            <form method="POST">
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>" disabled>
                </div>
                <div class="mb-3">
                    <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                </div>
                <button type="submit" name="update_password" class="btn btn-primary">Update Password</button>
            </form>

            <?php if (!empty($message)): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
