<?php
session_start();
$email = $_GET['email'] ?? '';
if (empty($email)) {
    header('Location: register-mock.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASENXO | Verify Email</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #0a0a0a;
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #fff;
        }
        .verify-card {
            background: #0e0e0e;
            border: 1px solid #222;
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }
        .verify-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .back-link {
            color: #888;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s;
        }
        .back-link:hover { color: #00df8d; }
        .logo-img { height: 30px; }
        .mail-icon {
            width: 80px;
            height: 80px;
            background: rgba(226,185,116,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #00df8d;
            font-size: 40px;
        }
        h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
            text-align: center;
            margin-bottom: 10px;
            color: #fff;
        }
        .description {
            text-align: center;
            color: #888;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .email-highlight {
            text-align: center;
            background: #1a1a1a;
            padding: 15px;
            border-radius: 12px;
            color: #00df8d;
            font-weight: 500;
            margin-bottom: 30px;
            word-break: break-all;
        }
        .otp-inputs {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin: 30px 0;
        }
        .otp-box {
            width: 50px;
            height: 60px;
            border: 2px solid #333;
            border-radius: 8px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            background: transparent;
            color: #fff;
            transition: border-color 0.3s;
        }
        .otp-box:focus {
            border-color: #00df8d;
            outline: none;
        }
        .verify-btn {
            width: 100%;
            background: #00df8d;
            color: #000;
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.3s;
            margin-top: 20px;
        }
        .verify-btn:hover { opacity: 0.9; }
        .verify-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .resend-section {
            text-align: center;
            margin-top: 20px;
        }
        .resend-link {
            background: none;
            border: none;
            color: #22c55e;
            cursor: pointer;
            font-size: 14px;
            text-decoration: underline;
        }
        .resend-link:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            text-decoration: none;
        }
        .timer-text {
            color: #888;
            font-size: 14px;
            margin-top: 10px;
        }
        .form-message {
            margin-top: 1rem;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.9rem;
            display: none;
        }
        .form-message.success {
            background-color: rgba(46,204,113,0.15);
            color: #2ecc71;
            border: 1px solid rgba(46,204,113,0.3);
            display: block;
        }
        .form-message.error {
            background-color: rgba(231,76,60,0.15);
            color: #e74c3c;
            border: 1px solid rgba(231,76,60,0.3);
            display: block;
        }
        @keyframes cardIntro {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .verify-card { animation: cardIntro 0.7s ease-out; }
    </style>
</head>
<body>
    <div class="verify-card">
        <div class="verify-header">
            <a href="register-mock.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="index.php"><img src="src/img/logo-name.png" class="logo-img" alt="ASENXO Logo"></a>
        </div>

        <div class="mail-icon">
            <i class="fas fa-envelope-open-text"></i>
        </div>

        <h2>Verify your email</h2>
        <p class="description">
            We've sent a 6-digit verification code to
        </p>
        <div class="email-highlight" id="displayEmail">
            <?php echo htmlspecialchars($email); ?>
        </div>

        <div class="otp-inputs" id="otpInputs">
            <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*">
            <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*">
            <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*">
            <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*">
            <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*">
            <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*">
        </div>

        <div id="verificationMessage" class="form-message"></div>

        <button class="verify-btn" id="verifyBtn">Verify Email</button>

        <div class="resend-section">
            <p class="timer-text" id="timerText">Resend code in 59 seconds</p>
            <button class="resend-link" id="resendBtn" disabled>Resend Code</button>
        </div>
        
        <p style="text-align: center; color: #666; font-size: 12px; margin-top: 20px;">
            For testing: Check the alert or console for your OTP code
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script>
    (function() {
        const SUPABASE_URL = 'https://hmxrblblcpbikkxcwwni.supabase.co';
        const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw';
        
        const supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY, {
            global: {
                headers: {
                'apikey': SUPABASE_ANON_KEY,
                'Authorization': `Bearer ${SUPABASE_ANON_KEY}`
                }
            }
            });

        const urlParams = new URLSearchParams(window.location.search);
        const email = "<?php echo $email; ?>";
        
        const otpBoxes = document.querySelectorAll('.otp-box');
        const verifyBtn = document.getElementById('verifyBtn');
        const resendBtn = document.getElementById('resendBtn');
        const timerText = document.getElementById('timerText');
        const verificationMessage = document.getElementById('verificationMessage');

        let resendTimerInterval = null;
        let seconds = 59;

        function showMessage(text, type = 'success') {
            verificationMessage.className = `form-message ${type}`;
            verificationMessage.innerHTML = text;
        }

        function updateTimer() {
            timerText.textContent = `Resend code in ${seconds} seconds`;
            resendBtn.disabled = true;
        }

        function resetTimer() {
            if (resendTimerInterval) clearInterval(resendTimerInterval);
            seconds = 59;
            updateTimer();
            resendTimerInterval = setInterval(() => {
                seconds--;
                if (seconds <= 0) {
                    clearInterval(resendTimerInterval);
                    timerText.textContent = 'Ready to resend';
                    resendBtn.disabled = false;
                } else {
                    updateTimer();
                }
            }, 1000);
        }

        otpBoxes.forEach((box, idx) => {
            box.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                if (e.target.value.length === 1 && idx < 5) otpBoxes[idx + 1].focus();
            });
            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && idx > 0) otpBoxes[idx - 1].focus();
            });
        });

        async function verifyOtp() {
    const enteredOtp = Array.from(otpBoxes).map(b => b.value).join('');
    if (enteredOtp.length !== 6) {
        showMessage('Please enter the 6-digit code.', 'error');
        return;
    }

    verifyBtn.disabled = true;
    verifyBtn.textContent = 'Verifying...';

    try {
        const { data: record, error: fetchError } = await supabase
            .from('email_verifications')
            .select('*')
            .eq('email', email)
            .single();

        if (fetchError || !record) {
            throw new Error('No active verification code found for this email.');
        }

        if (enteredOtp === record.otp) {
            await supabase.from('user_profiles').update({ email_verified: true }).eq('email', email);
            await supabase.from('email_verifications').delete().eq('email', email);
            
            showMessage('✅ Verified! Redirecting...', 'success');
            setTimeout(() => window.location.href = 'login-mock.php?verified=true', 2000);
        } else {
            await supabase.from('email_verifications')
                .update({ attempts: (record.attempts || 0) + 1 })
                .eq('email', email);
            throw new Error('Invalid code. Please try again.');
        }
    } catch (err) {
        showMessage(err.message, 'error');
        verifyBtn.disabled = false;
        verifyBtn.textContent = 'Verify Email';
    }
}
        async function resendOtp() {
            resendBtn.disabled = true;
            try {
                const newOtp = Math.floor(100000 + Math.random() * 900000).toString();
                const expiresAt = new Date(Date.now() + 10 * 60000).toISOString();

                const { error } = await supabase.from('email_verifications').upsert({ 
                    email: email, otp: newOtp, expires_at: expiresAt, attempts: 0 
                });

                if (error) throw error;
                
                fetch('send-otp.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: email, otp: newOtp })
                });

                alert(`New code sent: ${newOtp}`);
                resetTimer();
            } catch (err) {
                showMessage('Resend failed: ' + err.message, 'error');
                resendBtn.disabled = false;
            }
        }

        resetTimer();
        verifyBtn.addEventListener('click', verifyOtp);
        resendBtn.addEventListener('click', resendOtp);
    })();
</script>
</body>
</html>