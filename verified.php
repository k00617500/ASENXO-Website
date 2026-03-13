<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASENXO | Email Verified</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700&display=swap" rel="stylesheet">

    
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="src/css/verified-style.css">
    <style>
        @keyframes cardIntro {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.98);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .register-card {
            animation: cardIntro 0.7s cubic-bezier(0.2, 0.9, 0.3, 1) forwards;
            transform-origin: center;
            will-change: transform, opacity;
        }
    
        body {
            animation: bodyFade 0.9s ease-out;
        }
        @keyframes bodyFade {
            0% { background-color: #000; }
            100% { background-color: #0a0a0a; }
        }

        .register-card {
            background: var(--bg-card, #0e0e0e);
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="form-col">
      
             <div class="verify-header">
                <a href="login-mock.php" class="back-link" aria-label="Go back">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <a href="index.php"><img src="src/img/logo-name.png" class="logo-img" alt="ASENXO Logo"></a>
            </div>

            <div class="mail-icon-wrapper">
                <i class="fas fa-check-circle"></i>
            </div>

            <h2>Your email address has been verified</h2>
            <p class="description">
                Your account is ready. Click below to log in and start trading.
            </p>

            <div class="login-action">
                <a href="login-mock.php" class="login-button">
                    Click here to login!
                </a>
            </div>

    
            <div class="footnote">
                <span>Need help? </span><a href="#">Contact support</a>
            </div>
        </div>
    </div>
  
</body>
</html>