<?php
// Get the email from the URL parameter
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : 'your email';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redemption Successful - Monsdigi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3440;
            --secondary-color: #3a4654;
            --accent-color: #4a90e2;
            --success-color: #28a745;
            --text-dark: #333;
            --text-muted: #6c757d;
            --text-light: #f8f9fa;
            --card-bg: #ffffff;
        }
        
        body {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2a3541 100%);
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            padding-bottom: 50px;
        }
        
        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 12px 0;
        }
        
        .navbar-nav {
            margin-left: auto;
        }
        
        .navbar-nav .nav-link {
            color: var(--text-light);
            margin: 0 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .success-card {
            background-color: var(--card-bg);
            border-radius: 16px;
            padding: 35px;
            max-width: 550px;
            margin: 50px auto;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .success-icon {
            font-size: 5rem;
            color: var(--success-color);
            margin-bottom: 20px;
        }
        
        .success-icon i {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        h1 {
            color: var(--text-dark);
            font-size: 28px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .status-badge {
            display: inline-block;
            background-color: #e8f5e9;
            color: var(--success-color);
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 30px;
            margin-bottom: 25px;
        }
        
        .message-box {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: left;
        }
        
        .message-box p {
            margin-bottom: 10px;
        }
        
        .email-highlight {
            font-weight: 600;
            color: var(--accent-color);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(0, 0, 0, 0.2);
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--primary-color) 100%);
        }
        
        .info-text {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 25px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .logo img {
            max-width: 200px;
            height: auto;
        }
        
        @media (max-width: 767px) {
            .success-card {
                margin: 30px 15px;
                padding: 25px 20px;
            }
            
            h1 {
                font-size: 24px;
            }
            
            .success-icon {
                font-size: 4rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logo.png" alt="Monsdigi Logo" height="30">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-qrcode me-1"></i> Redeem</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="success-card">
            <div class="logo">
                <img src="logo.png" alt="Monsdigi Logo">
            </div>
            
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <div class="status-badge">
                <i class="fas fa-check me-2"></i> Redemption Successful
            </div>
            
            <h1>Thank You for Your Redemption!</h1>
            
            <div class="message-box">
                <p>Your redemption code has been successfully processed.</p>
                <p>We have sent all the product details to: <span class="email-highlight"><?php echo $email; ?></span></p>
                <p>Please check your inbox (and spam folder) for an email with your product information.</p>
            </div>
            
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-home me-2"></i> Return to Home
            </a>
            
            <p class="info-text">
                <i class="fas fa-info-circle me-1"></i> If you don't receive the email within 15 minutes, please contact our support team.
            </p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>