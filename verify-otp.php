<?php
// verify-otp.php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database configuration
$db_host = 'aws-0-ap-southeast-2.pooler.supabase.com';
$db_port = 6543;
$db_name = 'postgres';
$db_user = 'postgres.hmxrblblcpbikkxcwwni';
$db_pass = 'GgqIRwBL1ktX5xNt'; // Replace with your actual password

// Get input data
$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$otp = trim($data['otp'] ?? '');
$firstName = trim($data['first_name'] ?? '');
$lastName = trim($data['last_name'] ?? '');
$referralCode = trim($data['referral_code'] ?? '');

if (empty($email) || empty($otp)) {
    echo json_encode(['success' => false, 'error' => 'Email and OTP are required']);
    exit;
}

try {
    // Connect to Supabase PostgreSQL
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name;sslmode=require";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Start transaction
    $pdo->beginTransaction();

    // --- Validate referral code if provided ---
    if (!empty($referralCode)) {
        $stmt = $pdo->prepare("SELECT code, is_active FROM referral_codes WHERE code = :code");
        $stmt->execute(['code' => $referralCode]);
        $ref = $stmt->fetch();
        if (!$ref) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'error' => 'Invalid referral code: code does not exist']);
            exit;
        }
        if (!$ref['is_active']) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'error' => 'Invalid referral code: code is inactive']);
            exit;
        }
        // Optionally, you can retrieve the role from the referral code to assign to the user later.
    }

    // Check if OTP exists and is valid
    $stmt = $pdo->prepare("
        SELECT * FROM email_verifications 
        WHERE email = :email 
        AND otp = :otp 
        AND expires_at > NOW()
        AND attempts < 5
    ");
    $stmt->execute(['email' => $email, 'otp' => $otp]);
    $verification = $stmt->fetch();

    if (!$verification) {
        // Increment attempts
        $pdo->prepare("
            UPDATE email_verifications 
            SET attempts = attempts + 1 
            WHERE email = :email
        ")->execute(['email' => $email]);
        
        $pdo->commit();
        echo json_encode(['success' => false, 'error' => 'Invalid or expired verification code']);
        exit;
    }

    // Delete used OTP
    $pdo->prepare("DELETE FROM email_verifications WHERE email = :email")->execute(['email' => $email]);

    // Get the actual user ID from auth.users using the email
    $stmt = $pdo->prepare("SELECT id FROM auth.users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $authUser = $stmt->fetch();

    if (!$authUser) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => 'User not found in authentication system']);
        exit;
    }
    $userId = $authUser['id'];

    // Insert or update user_profiles with the correct user ID and referral code
    $stmt = $pdo->prepare("
        INSERT INTO user_profiles (id, email, first_name, last_name, referral_code, email_verified)
        VALUES (:id, :email, :first_name, :last_name, :referral_code, TRUE)
        ON CONFLICT (email) DO UPDATE
        SET email_verified = TRUE, 
            first_name = EXCLUDED.first_name,
            last_name = EXCLUDED.last_name,
            referral_code = EXCLUDED.referral_code,
            updated_at = NOW()
    ");
    
    $stmt->execute([
        'id' => $userId,
        'email' => $email,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'referral_code' => $referralCode === '' ? null : $referralCode
    ]);

    $pdo->commit();

    // Send welcome email
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'dost.asenxo@gmail.com';
        $mail->Password   = 'qkoczbdhdfcmqnoi';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('dost.asenxo@gmail.com', 'ASENXO');
        $mail->addAddress($email, "$firstName $lastName");
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to ASENXO!';
        $mail->Body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: 'Inter', sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #e2b974; color: #000; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { padding: 30px; background: #f9f9f9; }
                .button { display: inline-block; background: #e2b974; color: #000; padding: 12px 30px; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Welcome to ASENXO!</h1>
                </div>
                <div class='content'>
                    <h2>Hi " . htmlspecialchars($firstName) . ",</h2>
                    <p>Your email has been successfully verified. You can now log in to your account and start using ASENXO.</p>
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='http://" . $_SERVER['HTTP_HOST'] . "/login-mock.php' class='button'>Log In to Your Account</a>
                    </div>
                    <p>If you have any questions, feel free to contact our support team.</p>
                    <p>Best regards,<br>The ASENXO Team</p>
                </div>
            </div>
        </body>
        </html>
        ";
        $mail->send();
    } catch (Exception $e) {
        // Log welcome email error but don't fail verification
        error_log("Welcome email failed: " . $e->getMessage());
    }

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    if (isset($pdo)) $pdo->rollBack();
    error_log("Database error in verify-otp.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    if (isset($pdo)) $pdo->rollBack();
    error_log("Server error in verify-otp.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
?>
