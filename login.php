<?php
require 'koneksi.php'; // Assuming the database.php file is in the same directory

$message = ''; // Initialize the message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare the data for login
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Check if the username and password match the database records
        $sql = "SELECT * FROM customer WHERE cst_username = ? AND cst_password = ?";
        $statement = $pdo->prepare($sql);
        $statement->execute([$username, $password]);

        if ($statement->rowCount() > 0) {
            // Login successful
            $customer = $statement->fetch(); // Ambil data customer dari hasil query
            $customerId = $customer['cst_id']; // Ambil cst_id dari hasil query
            
            // Simpan cst_id ke dalam session
            session_start();
            $_SESSION['customerId'] = $customerId;
            
            $message = "Login successful!";
            header("Location: dashboard.php"); // Redirect to dashboard.php
            exit();
        }
         else {
            // Login failed
            $message = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<!-- Created By CodingLab - www.codinglabweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>LaundryDar</title>
    <link rel="stylesheet" href="css/login_style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
      .message {
        margin-top: 10px;
        font-size: 16px;
        color: #ffffff;
        background-color: #34a853;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
      }
      .error {
        background-color: #ea4335;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div>Welcome LaundryDar</div>
      <div class="title">Login</div>
      <div class="content">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
          <div class="user-details">
            <div class="input-box">
              <span class="details">Username</span>
              <input type="text" name="username" placeholder="Enter your username" required>
              <?php if (isset($message) && $message === 'Invalid username or password.'): ?>
                <span class="text-danger"><?php echo $message; ?></span>
              <?php endif; ?>
            </div>
            <div class="input-box">
              <span class="details">Password</span>
              <input type="password" name="password" placeholder="Enter your password" required>
            </div>
          </div>
          <div class="button">
            <input type="submit" value="Login">
          </div>
        </form>
        <?php if (isset($message) && $message === 'Login successful!'): ?>
          <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <div class="register-link">Belum punya akun? <a href="register.php">Register Sekarang</a></div>
      </div>
    </div>
  </body>
</html>
