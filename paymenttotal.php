<?php
session_start();
require 'koneksi.php';

// Check if customerId exists in the session, otherwise set it to null
$customerId = isset($_SESSION['customerId']) ? $_SESSION['customerId'] : null;

try {
    // Establish a database connection using PDO (assuming the connection details are defined in koneksi.php)
    $pdo = new PDO('mysql:host=localhost;dbname=LaundryDar', 'root', 'phpmyadmin');
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the SQL query to fetch the totalPrice
    $stmt = $pdo->prepare('SELECT tsc_totalharga FROM transaction WHERE customer_cst_id = ? ORDER BY tsc_id DESC LIMIT 1');
    $stmt->execute([$customerId]);

    // Fetch the totalPrice from the query result
    $transaction = $stmt->fetch();
    $totalPrice = $transaction ? $transaction['tsc_totalharga'] : 0;
} catch (PDOException $e) {
    // Handle database connection errors
    echo 'Connection failed: ' . $e->getMessage();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/transactionform_style.css" />
  <script src="js/transactionform.js" defer></script>
  <title>Order Form</title>
  <style>
    .input-group label[for="paidPrice"] {
      color: green;
      font-weight: bold;
    }
  </style>  
</head>
<body>
<form class="form">
  <h1 class="text-center">Payment Form</h1>
  <!-- Progress bar -->
  <div class="progressbar">
    <div class="progress" id="progress"></div>
    <div class="progress-step progress-step-active" data-title="Service"></div>
    <div class="progress-step progress-step-active" data-title="Delivery"></div>
    <div class="progress-step progress-step-active" data-title="Total"></div>
    <div class="progress-step" data-title="Payment"></div>
  </div>

  <!-- Steps -->
  <div class="form-step form-step-active">
    <div class="input-group">
      <label for="confirmPassword">Payment Information</label>
      <span class="warning-text">Information below is the total you have to pay!</span>
    </div>
    <div class="input-group">
      <label for="totalPrice">Total Price</label>
      <input type="text" name="totalPrice" id="totalPrice" value="<?php echo $totalPrice; ?>" readonly />
    </div>
    <div class="btns-group">
      <a href="pesanservice.php" class="btn btn-prev">Previous</a>
      <a href="paymentform.php" class="btn btn-prev">Next</a>
    </div>
  </div>
</form>
</body>
</html>
