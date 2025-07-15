<?php
// signup.php
session_start();
require_once 'config.php'; // Include the database configuration

$page_title = "Sign Up - MemoSync";
$company_name = "MemoSync";

$name = $email = $password = $confirm_password = "";
$name_err = $email_err = $password_err = $confirm_password_err = "";
$success_message = "";

// Handle signup form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate Name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your full name.";
    } else {
        $name = trim($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $name_err = "Only letters and white space allowed for name.";
        } else if (strlen($name) < 3 || strlen($name) > 50) {
            $name_err = "Name must be between 3 and 50 characters.";
        }
    }

    // Validate Email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email address.";
    } else {
        $email = trim($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format.";
        } else {
            // Check if email already exists
            $sql = "SELECT id FROM users WHERE email = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $param_email);
                $param_email = $email;
                if ($stmt->execute()) {
                    $stmt->store_result();
                    if ($stmt->num_rows == 1) {
                        $email_err = "This email is already registered.";
                    }
                } else {
                    $email_err = "Oops! Something went wrong. Please try again later.";
                }
                $stmt->close();
            }
        }
    }

    // Validate Password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please create a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate Confirm Password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm your password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Passwords do not match.";
        }
    }

    // If no errors, insert user into database
    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare an insert statement
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $param_name, $param_email, $param_password);

            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_password = $hashed_password;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $success_message = "Account created successfully! You can now sign in.";
                // Clear form fields
                $name = $email = $password = $confirm_password = "";
                // Redirect to login page after a short delay or immediately
                header("refresh:3;url=login.php"); // Redirect after 3 seconds
                // header("Location: login.php"); // Immediate redirect
                // exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
}
$conn->close(); // Close connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
            color: white; min-height: 100vh; overflow-x: hidden;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .page-bg { position: fixed; top: 0; left: 0; right: 0; bottom: 0; overflow: hidden; z-index: -1; }
        .glow-orb {
            position: absolute; width: 300px; height: 300px; border-radius: 50%;
            background: radial-gradient(circle, rgba(233, 30, 99, 0.2) 0%, transparent 70%);
            filter: blur(40px); animation: float 6s ease-in-out infinite;
        }
        .glow-orb:nth-child(1) { top: 10%; right: 20%; animation-delay: 0s; }
        .glow-orb:nth-child(2) { bottom: 20%; left: 15%; background: radial-gradient(circle, rgba(156, 39, 176, 0.2) 0%, transparent 70%); animation-delay: 3s; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .signup-section { display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .signup-container {
            background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px; padding: 50px; width: 100%; max-width: 450px; text-align: center;
            backdrop-filter: blur(10px);
        }
        .signup-container h1 {
            font-size: 2.5rem; margin-bottom: 10px;
            background: linear-gradient(45deg, #e91e63, #9c27b0);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .signup-container p { color: rgba(255, 255, 255, 0.7); margin-bottom: 40px; font-size: 1.1rem; }
        .form-group { margin-bottom: 25px; text-align: left; position: relative; } /* Added position relative */
        .form-group label { display: block; margin-bottom: 8px; color: rgba(255, 255, 255, 0.9); font-weight: 500; }
        .form-group input {
            width: 100%; padding: 15px; border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px; background: rgba(255, 255, 255, 0.05); color: white;
            font-size: 1rem; transition: all 0.3s ease;
        }
        .form-group input:focus {
            outline: none; border-color: #e91e63; background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.1);
        }
        .form-group input::placeholder { color: rgba(255, 255, 255, 0.5); }
        .signup-btn {
            width: 100%; background: linear-gradient(45deg, #e91e63, #9c27b0);
            color: white; padding: 15px; border-radius: 10px; border: none;
            font-weight: 600; font-size: 1.1rem; cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease; margin-bottom: 30px;
        }
        .signup-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(233, 30, 99, 0.3); }
        .index-link { color: rgba(255, 255, 255, 0.7); font-size: 0.95rem; }
        .index-link a { color: #e91e63; text-decoration: none; font-weight: 600; }
        .index-link a:hover { text-decoration: underline; }
        .error-message {
            background: rgba(244, 67, 54, 0.1); border: 1px solid rgba(244, 67, 54, 0.3);
            color: #ff6b6b; padding: 10px 15px; border-radius: 8px; margin-top: 8px; font-size: 0.85rem;
            position: absolute; bottom: -25px; left: 0; width: 100%;
        }
        .success-message {
            background: rgba(76, 175, 80, 0.1); border: 1px solid rgba(76, 175, 80, 0.3);
            color: #8bc34a; padding: 15px; border-radius: 10px; margin-bottom: 25px; font-size: 0.9rem;
            text-align: center;
        }
        .terms-text { color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-bottom: 30px; line-height: 1.4; }
        .terms-text a { color: #e91e63; text-decoration: none; }
        .terms-text a:hover { text-decoration: underline; }
        @media (max-width: 768px) {
            .signup-container { padding: 30px; margin: 20px; }
            .signup-container h1 { font-size: 2rem; }
        }
    </style>
</head>
<body>
    <div class="page-bg">
        <div class="glow-orb"></div>
        <div class="glow-orb"></div>
    </div>

    <main>
        <section class="signup-section">
            <div class="container">
                <div class="signup-container">
                    <h1>Get Started</h1>
                    <p>Create your MemoSync account today</p>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="success-message">
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" placeholder="Enter your full name" value="<?php echo htmlspecialchars($name); ?>" required>
                            <?php if (!empty($name_err)): ?><span class="error-message"><?php echo $name_err; ?></span><?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
                            <?php if (!empty($email_err)): ?><span class="error-message"><?php echo $email_err; ?></span><?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Create a password" required>
                            <?php if (!empty($password_err)): ?><span class="error-message"><?php echo $password_err; ?></span><?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                            <?php if (!empty($confirm_password_err)): ?><span class="error-message"><?php echo $confirm_password_err; ?></span><?php endif; ?>
                        </div>
                        
                        <div class="terms-text">
                            By creating an account, you agree to our <a href="#terms">Terms of Service</a> and <a href="#privacy">Privacy Policy</a>.
                        </div>
                        
                        <button type="submit" class="signup-btn">Create Account</button>
                    </form>
                    
                    <div class="index-link">
                        Already have an account? <a href="login.php">Sign in here</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Remove JavaScript alerts for validation, PHP handles it now.
        // Keep input focus effects and password strength indicator if desired.
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                // Adjust parentElement to target the form-group for visual effect
                const formGroup = this.closest('.form-group');
                if (formGroup) {
                    formGroup.style.transform = 'translateY(-2px)';
                }
            });
            
            input.addEventListener('blur', function() {
                const formGroup = this.closest('.form-group');
                if (formGroup) {
                    formGroup.style.transform = 'translateY(0)';
                }
            });
        });

        // Password strength indicator (client-side visual feedback)
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            let strength = '';
            if (password.length >= 8) {
                strength = 'strong';
            } else if (password.length >= 6) {
                strength = 'medium';
            } else {
                strength = 'weak';
            }
            
            this.style.borderColor = strength === 'strong' ? '#4caf50' : strength === 'medium' ? '#ff9800' : '#f44336';
        });
    </script>
</body>
</html>
