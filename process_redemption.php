<?php
// Database connection settings
$host = "monsdigi.com";
$dbname = "u735634963_nedmons";
$username = "u735634963_Monsdigi1";
$password = "5]9yBfTBi";

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'errors' => []
];

// Function to sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $order_id = isset($_POST['orderID']) ? sanitize($_POST['orderID']) : '';
    $redeem_code = isset($_POST['redeemCode']) ? sanitize($_POST['redeemCode']) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    
    // Validate required fields
    if (empty($order_id)) {
        $response['errors'][] = "Order ID is required";
    }
    
    if (empty($redeem_code)) {
        $response['errors'][] = "Redemption code is required";
    }
    
    if (empty($email)) {
        $response['errors'][] = "Email address is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['errors'][] = "Invalid email format";
    }
    
    // If no validation errors, proceed to database check
    if (empty($response['errors'])) {
        try {
            // Create PDO connection
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            
            // Set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Prepare SQL query to check redemption code and order ID
            $stmt = $pdo->prepare("SELECT p.*, r.is_redeemed FROM redemption_codes r 
                                   JOIN products p ON r.product_id = p.id
                                   WHERE r.code = :code AND r.order_id = :order_id
                                   LIMIT 1");
            
            $stmt->bindParam(':code', $redeem_code, PDO::PARAM_STR);
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);
            $stmt->execute();
            
            // Check if the code exists and is valid
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Check if the code has already been redeemed
                if ($row['is_redeemed'] == 1) {
                    $response['message'] = "This code has already been redeemed.";
                } else {
                    // Mark code as redeemed
                    $update_stmt = $pdo->prepare("UPDATE redemption_codes 
                                                 SET is_redeemed = 1, redeemed_at = NOW(), email = :email
                                                 WHERE code = :code AND order_id = :order_id");
                    
                    $update_stmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $update_stmt->bindParam(':code', $redeem_code, PDO::PARAM_STR);
                    $update_stmt->bindParam(':order_id', $order_id, PDO::PARAM_STR);
                    $update_stmt->execute();
                    
                    // Prepare email with product info
                    sendProductEmail($email, $row);
                    
                    $response['success'] = true;
                    $response['message'] = "Your code has been redeemed successfully. Product details have been sent to your email.";
                }
            } else {
                $response['message'] = "Invalid redemption code or order ID. Please check and try again.";
            }
            
        } catch(PDOException $e) {
            $response['message'] = "Database error: " . $e->getMessage();
            // In production, you would log this error instead of displaying it
        }
    } else {
        $response['message'] = "Please correct the following errors:";
    }
}

/**
 * Function to send styled email with product information
 * 
 * @param string $email Recipients email address
 * @param array $product Product information from database
 * @return bool Success status
 */
function sendProductEmail($email, $product) {
    // Email subject
    $subject = "Your Monsdigi Product: " . $product['name'];

    // Create styled HTML email
    $html_message = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Your Monsdigi Product</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333333;
                margin: 0;
                padding: 0;
            }
            .email-container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
            }
            .header {
                background-color: #2c3440;
                padding: 20px;
                text-align: center;
                border-radius: 8px 8px 0 0;
            }
            .header img {
                max-width: 200px;
                height: auto;
            }
            .content {
                background-color: #ffffff;
                padding: 30px;
                border-left: 1px solid #dddddd;
                border-right: 1px solid #dddddd;
            }
            .footer {
                background-color: #f5f5f5;
                padding: 20px;
                text-align: center;
                font-size: 12px;
                color: #777777;
                border-radius: 0 0 8px 8px;
                border: 1px solid #dddddd;
            }
            h1 {
                color: #2c3440;
                margin-top: 0;
                margin-bottom: 20px;
                font-size: 24px;
            }
            .product-details {
                background-color: #f9f9f9;
                padding: 20px;
                border-radius: 8px;
                margin-bottom: 25px;
                border: 1px solid #eeeeee;
            }
            .product-name {
                font-size: 18px;
                font-weight: bold;
                color: #4a90e2;
                margin-bottom: 10px;
            }
            .product-description {
                margin-bottom: 15px;
            }
            .product-details table {
                width: 100%;
                border-collapse: collapse;
            }
            .product-details table td {
                padding: 8px 10px;
                border-bottom: 1px solid #eeeeee;
            }
            .product-details table td:first-child {
                font-weight: bold;
                width: 40%;
            }
            .button {
                display: inline-block;
                background-color: #4a90e2;
                color: #ffffff;
                text-decoration: none;
                padding: 12px 30px;
                border-radius: 4px;
                font-weight: bold;
                margin-top: 20px;
            }
            .important-note {
                background-color: #fff9e6;
                border-left: 4px solid #ffc107;
                padding: 15px;
                margin-top: 25px;
            }
            @media only screen and (max-width: 600px) {
                .email-container {
                    width: 100%;
                    padding: 10px;
                }
                .content {
                    padding: 20px;
                }
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="header">
                <img src="https://monsdigi.com/images/logo.png" alt="Monsdigi Logo">
            </div>
            <div class="content">
                <h1>Your Product is Ready!</h1>
                <p>Dear Customer,</p>
                <p>Thank you for redeeming your code. Below are the details of your product:</p>
                
                <div class="product-details">
                    <div class="product-name">' . htmlspecialchars($product['name']) . '</div>
                    <div class="product-description">' . htmlspecialchars($product['description']) . '</div>
                    
                    <table>
                        <tr>
                            <td>Product ID</td>
                            <td>' . htmlspecialchars($product['id']) . '</td>
                        </tr>';
    
    // Add dynamic product fields from database
    // These will vary based on your database structure
    if (!empty($product['license_key'])) {
        $html_message .= '
                        <tr>
                            <td>License Key</td>
                            <td><strong>' . htmlspecialchars($product['license_key']) . '</strong></td>
                        </tr>';
    }

    if (!empty($product['download_url'])) {
        $html_message .= '
                        <tr>
                            <td>Download Link</td>
                            <td><a href="' . htmlspecialchars($product['download_url']) . '">Click here to download</a></td>
                        </tr>';
    }

    if (!empty($product['expiry_date'])) {
        $html_message .= '
                        <tr>
                            <td>Valid Until</td>
                            <td>' . htmlspecialchars($product['expiry_date']) . '</td>
                        </tr>';
    }

    $html_message .= '
                    </table>
                </div>';
    
    // Add access button if URL is provided
    if (!empty($product['access_url'])) {
        $html_message .= '
                <center>
                    <a href="' . htmlspecialchars($product['access_url']) . '" class="button">Access Your Product</a>
                </center>';
    }

    $html_message .= '
                <div class="important-note">
                    <strong>Important:</strong> Please keep this email for your records. The license key and download information provided above is unique to your purchase.
                </div>
                
                <p>If you have any questions or need assistance, please don\'t hesitate to contact our support team at support@monsdigi.com.</p>
                
                <p>Best regards,<br>The Monsdigi Team</p>
            </div>
            <div class="footer">
                <p>&copy; ' . date('Y') . ' Monsdigi. All rights reserved.</p>
                <p>This email was sent to ' . htmlspecialchars($email) . ' because you redeemed a product code.</p>
            </div>
        </div>
    </body>
    </html>';

    // Plain text version for email clients that don't support HTML
    $plain_message = "Your Monsdigi Product\n\n" .
        "Thank you for redeeming your code. Below are the details of your product:\n\n" .
        "Product: " . $product['name'] . "\n" .
        "Description: " . $product['description'] . "\n";

    if (!empty($product['license_key'])) {
        $plain_message .= "License Key: " . $product['license_key'] . "\n";
    }

    if (!empty($product['download_url'])) {
        $plain_message .= "Download Link: " . $product['download_url'] . "\n";
    }

    if (!empty($product['expiry_date'])) {
        $plain_message .= "Valid Until: " . $product['expiry_date'] . "\n";
    }

    // Email headers
    $headers = "From: Monsdigi <noreply@monsdigi.com>\r\n";
    $headers .= "Reply-To: support@monsdigi.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Send email
    $mail_sent = mail($email, $subject, $html_message, $headers);
    
    return $mail_sent;
}

// Return JSON response for AJAX requests
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// For regular form submissions, redirect with appropriate message
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($response['success']) {
        // Redirect to success page
        header("Location: redemption_success.php?email=" . urlencode($email));
        exit;
    } else {
        // Redirect back to the form with error message
        $error_msg = urlencode($response['message']);
        
        if (!empty($response['errors'])) {
            $error_msg .= ': ' . urlencode(implode(', ', $response['errors']));
        }
        
        header("Location: index.php?error=" . $error_msg);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Redemption</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #3a4654 0%, #2a3541 100%);
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .processing-card {
            background-color: white;
            border-radius: 16px;
            padding: 35px;
            max-width: 550px;
            margin: 50px auto;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
        }
        .spinner-border {
            width: 3rem;
            height: 3rem;
            color: #4a90e2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="processing-card">
            <div class="spinner-border mb-4" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h2 class="mb-4">Processing Your Redemption</h2>
            <p class="text-muted">Please wait while we validate your information...</p>
        </div>
    </div>
    
    <script>
        // This page will only show if the form is submitted directly and JavaScript is disabled
        // Otherwise, the PHP redirect will take effect
    </script>
</body>
</html>