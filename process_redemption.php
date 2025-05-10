<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Database connection settings
$host = "srv1919.hstgr.io";
$dbname = "u735634963_nedmons";
$username = "u735634963_Monsdigi1";
$password = "Reda@2003xvb";

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'errors' => []
];

// Function to sanitize input data
function sanitize($data) {
    return htmlspecialchars(trim(stripslashes($data)));
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
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
            // Create PDO connection with error handling
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $pdo = new PDO($dsn, $username, $password, $options);
            
            // Debug query
            $debug_stmt = $pdo->prepare("SELECT COUNT(*) as count FROM redemption_codes");
            $debug_stmt->execute();
            $table_exists = $debug_stmt->fetch();
            
            if ($table_exists['count'] === false) {
                throw new Exception("Redemption codes table not found");
            }
            
            // Check if the code exists and is valid
            $stmt = $pdo->prepare("SELECT * FROM redemption_codes 
                                 WHERE code = ? AND order_id = ?
                                 LIMIT 1");
            
            $stmt->execute([$redeem_code, $order_id]);
            $redemption = $stmt->fetch();
            
            if ($redemption) {
                if ($redemption['is_redeemed'] == 1) {
                    $response['message'] = "This code has already been redeemed.";
                } else {
                    // Get product details
                    $product_stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
                    $product_stmt->execute([$redemption['product_id']]);
                    $product = $product_stmt->fetch();
                    
                    if ($product) {
                        // Mark code as redeemed
                        $update_stmt = $pdo->prepare("UPDATE redemption_codes 
                                                   SET is_redeemed = 1, 
                                                       redeemed_at = NOW(), 
                                                       email = ?
                                                   WHERE code = ? 
                                                   AND order_id = ?");
                        
                        $update_stmt->execute([$email, $redeem_code, $order_id]);
                        
                        if (sendProductEmail($email, $product)) {
                            $response['success'] = true;
                            $response['message'] = "Your code has been redeemed successfully. Product details have been sent to your email.";
                        } else {
                            throw new Exception("Failed to send confirmation email");
                        }
                    } else {
                        $response['message'] = "Product not found. Please contact support.";
                    }
                }
            } else {
                $response['message'] = "Invalid redemption code or order ID. Please check and try again.";
            }
        } else {
            $response['message'] = "Please correct the following errors";
        }
    } catch(PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        $response['message'] = "Database connection error. Please try again later.";
        $response['debug'] = $e->getMessage(); // Remove this in production
    } catch(Exception $e) {
        error_log("General Error: " . $e->getMessage());
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
exit;

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
    return mail($email, $subject, $html_message, $headers);
}
?>