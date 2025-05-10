<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monsdigi - Redeem Code</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3440;
            --secondary-color: #3a4654;
            --accent-color: #4a90e2;
            --text-dark: #333;
            --text-muted: #6c757d;
            --text-light: #f8f9fa;
            --card-bg: #ffffff;
            --input-border: #e0e0e0;
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
        
        .navbar-nav .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            bottom: -5px;
            left: 0;
            transition: width 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover:after,
        .navbar-nav .nav-link.active:after {
            width: 100%;
        }
        
        .navbar-nav .nav-link.active {
            color: #fff;
            font-weight: 600;
        }
        
        .navbar-toggler {
            border: none;
            color: var(--text-light);
        }
        
        .redemption-card {
            background-color: var(--card-bg);
            border-radius: 16px;
            padding: 35px;
            max-width: 550px;
            margin: 50px auto;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        .redemption-card:hover {
            transform: translateY(-5px);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .logo img {
            max-width: 280px;
            height: auto;
            transition: transform 0.3s ease;
        }
        
        .logo img:hover {
            transform: scale(1.05);
        }
        
        h1 {
            color: var(--text-dark);
            font-size: 26px;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
            letter-spacing: 1px;
            position: relative;
            padding-bottom: 15px;
        }
        
        h1:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--accent-color);
        }
        
        .form-label {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            margin-right: 8px;
            color: var(--accent-color);
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid var(--input-border);
            margin-bottom: 24px;
            transition: all 0.3s ease;
            font-size: 15px;
            box-shadow: none;
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15);
        }
        
        .btn-redeem {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            border: none;
            padding: 12px 35px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: block;
            margin: 25px auto 0;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        
        .btn-redeem:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(0, 0, 0, 0.2);
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--primary-color) 100%);
        }
        
        .btn-redeem:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 767px) {
            .redemption-card {
                margin: 30px 15px;
                padding: 25px 20px;
            }
            
            h1 {
                font-size: 22px;
            }
            
            .monsdigi-mascot {
                width: 80px;
                bottom: 10px;
                left: 10px;
            }
        }
        
        .loading-spinner {
            display: none;
            margin-right: 8px;
        }
        
        .form-group {
            position: relative;
            margin-bottom: 24px;
        }
        
        .error-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: -20px;
            margin-bottom: 16px;
            display: none;
        }

        .email-note {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 14px;
            color: #2e7d32;
        }
        
        .email-note i {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logo.png" alt="Monsdigi Logo" height="30">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-qrcode me-1"></i> Redeem</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="redemption-card">
            <div class="logo">
                <img src="logo.png" alt="Monsdigi Logo">
            </div>
            
            <h1>REDEEM YOUR CODE</h1>
            
            <form id="redemptionForm">
                <div class="form-group">
                    <label for="orderID" class="form-label"><i class="fas fa-hashtag"></i> YOUR ORDER ID</label>
                    <input type="text" class="form-control" id="orderID" name="orderID" placeholder="Enter your order ID" required>
                    <div class="error-feedback" id="orderIDError"></div>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label"><i class="fas fa-envelope"></i> YOUR EMAIL</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
                    <div class="error-feedback" id="emailError"></div>
                </div>
                
                <div class="form-group">
                    <label for="redeemCode" class="form-label"><i class="fas fa-key"></i> YOUR REDEEM CODE</label>
                    <input type="text" class="form-control" id="redeemCode" name="redeemCode" placeholder="Enter your redemption code" required>
                    <div class="error-feedback" id="redeemCodeError"></div>
                </div>
                
                <div class="email-note">
                    <i class="fas fa-envelope-open-text"></i>
                    After clicking "REDEEM NOW", your order details and product access will be sent directly to your email address.
                </div>
                
                <button type="submit" class="btn btn-redeem" id="redeemButton">
                    <span class="spinner-border spinner-border-sm loading-spinner" role="status" aria-hidden="true"></span>
                    <i class="fas fa-unlock-alt me-2"></i> REDEEM NOW
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('redemptionForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = this;
            const submitButton = document.getElementById('redeemButton');
            const loadingSpinner = submitButton.querySelector('.loading-spinner');
            
            // Reset error messages
            document.querySelectorAll('.error-feedback').forEach(el => {
                el.style.display = 'none';
                el.textContent = '';
            });
            
            // Disable form submission
            submitButton.disabled = true;
            loadingSpinner.style.display = 'inline-block';
            
            try {
                const formData = new FormData(form);
                const response = await fetch('process_redemption.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: result.message,
                        confirmButtonColor: '#4a90e2'
                    }).then(() => {
                        window.location.href = `redemption_success.php?email=${encodeURIComponent(formData.get('email'))}`;
                    });
                } else {
                    if (result.errors && result.errors.length > 0) {
                        result.errors.forEach(error => {
                            if (error.includes('Order ID')) {
                                document.getElementById('orderIDError').textContent = error;
                                document.getElementById('orderIDError').style.display = 'block';
                            } else if (error.includes('email')) {
                                document.getElementById('emailError').textContent = error;
                                document.getElementById('emailError').style.display = 'block';
                            } else if (error.includes('Redemption code')) {
                                document.getElementById('redeemCodeError').textContent = error;
                                document.getElementById('redeemCodeError').style.display = 'block';
                            }
                        });
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message,
                        confirmButtonColor: '#4a90e2'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again.',
                    confirmButtonColor: '#4a90e2'
                });
            } finally {
                submitButton.disabled = false;
                loadingSpinner.style.display = 'none';
            }
        });
    </script>
</body>
</html>