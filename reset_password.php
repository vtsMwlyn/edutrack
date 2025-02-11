<?php
session_start();
include "config.php";

function sendOtp($phone) {
    $data = [
        'phone' => $phone,
        'gateway_key' => GATEWAY_KEY,
    ];

    $ch = curl_init(API_URL_SEND);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . MERCHANT_KEY,
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    // Log the result for debugging
    file_put_contents('api_log.txt', "Send OTP Response: " . $result . "\n", FILE_APPEND);
    
    if ($result === false) {
        return ['status' => 'error', 'message' => 'Unable to contact the API. ' . $error];
    }

    return json_decode($result, true);
}

function verifyOtp($phone, $otp, $otp_id) {
    $data = [
        'phone' => $phone,
        'otp' => $otp,
        'otp_id' => $otp_id,
        'gateway_key' => GATEWAY_KEY,
    ];

    $ch = curl_init(API_URL_VERIFY);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . MERCHANT_KEY,
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    // Log the result for debugging
    file_put_contents('api_log.txt', "Verify OTP Response: " . $result . "\n", FILE_APPEND);

    if ($result === false) {
        return ['status' => 'error', 'message' => 'Unable to contact the API. ' . $error];
    }

    return json_decode($result, true);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['send_otp'])) {
        $phone = $_POST['phone'];
        $response = sendOtp($phone);
        
        if (isset($response['status']) && $response['status'] === true) {
            $_SESSION['phone'] = $phone;
            $_SESSION['otp_id'] = $response['data']['id'];
            $message = "Kode OTP Terkirim ke No Tujuan $phone. Silahkan Cek Pesan Whatsapp Anda Untuk Melihat Kode OTP.";
        } else {
            $message = "Failed to send OTP: " . ($response['message'] ?? 'Unknown error');
        }
    } elseif (isset($_POST['verify_otp'])) {
        $otp = implode('', $_POST['otp']); // Concatenate the individual OTP digits
        $phone = $_SESSION['phone'] ?? '';
        $otp_id = $_SESSION['otp_id'] ?? '';

        if ($phone && $otp_id) {
            $response = verifyOtp($phone, $otp, $otp_id);
            if (isset($response['status']) && $response['status'] === true) {
                $_SESSION['welcome_message'] = "Welcome, $phone!";
                header('Location: newpass.php');
                exit;
            } else {
                $message = "Gagal memverifikasi Kode OTP: " . ($response['message'] ?? 'Unknown error');
            }
        } else {
            $message = "No Telepon / Kode OTP Tidak Terdaftar.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with OTP</title>
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
        .otp-input {
            width: 35px;
            height: 35px;
            font-size: 1.1rem;
            text-align: center;
            margin-right: 4px;
            border: 2px solid #504E76; /* Soft Green */
            border-radius: 5px;
        }
        /* Shake animation */
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
        <div class="image-container"></div>
        <div class="content-container">
            <h2>Request OTP</h2>
            <form id="loginForm" method="POST" class="mb-4">
                <div class="mb-3">
                    <input type="text" name="phone" class="form-control" placeholder="Enter your phone number" required>
                </div>
                <button type="submit" name="send_otp" class="btn btn-primary w-100">Get Code</button>
            </form>

            <h2>Input Code OTP</h2>
            <form id="otpForm" method="POST">
                <div class="d-flex justify-content-center mb-3">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                        <input type="text" name="otp[]" class="otp-input" maxlength="1" required oninput="moveFocus(event, <?php echo $i + 1; ?>)">
                    <?php endfor; ?>
                </div>
                <button type="submit" name="verify_otp" class="btn btn-success w-100">Verify</button>
                <div class="text-center mt-2">
                <a href="login.php" class="text-decoration-none" style="color: #504E76;">Back</a>

    </div>
            </form>
                            
            <div id="message" class="message text-center mt-3">
                <?php if (!empty($_SESSION['message'])) {
                    echo htmlspecialchars($_SESSION['message']);
                    unset($_SESSION['message']);
                } ?>
            </div>
           
        </div>
       
    </div>

    <script>

        
        function moveFocus(event, index) {
            const input = event.target;
            if (input.value.length === 1 && index < 6) {
                input.nextElementSibling.focus();
            } else if (input.value.length === 0 && index > 1) {
                input.previousElementSibling.focus();
            }
        }

        
    </script>
</body>
</html>