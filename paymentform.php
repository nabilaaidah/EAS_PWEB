<?php
// Check if the payment method is submitted
if (isset($_POST['pm_method'])) {
    $pm_method = $_POST['pm_method'];
    session_start();

    // Retrieve customerId from the session
    $customerId = isset($_SESSION['customerId']) ? $_SESSION['customerId'] : null;

    try {
        // Establish a database connection using PDO
        $pdo = new PDO('mysql:host=localhost;dbname=LaundryDar', 'root', 'phpmyadmin');
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the latest transaction for the customer
        $stmt = $pdo->prepare('SELECT * FROM transaction WHERE customer_cst_id = ? ORDER BY tsc_id DESC LIMIT 1');
        $stmt->execute([$customerId]);
        $transaction = $stmt->fetch();

        // Get the max pm_id from the payment table
        $stmt = $pdo->query('SELECT MAX(pm_id) FROM payment');
        $maxId = $stmt->fetchColumn();
        $newId = $maxId + 1;

        // Prepare the insert statement
        $stmt = $pdo->prepare('INSERT INTO payment (pm_method, pm_date, pm_amount, transaction_tsc_id) VALUES (?, ?, ?, ?)');
        $stmt->execute([$pm_method, date('Y-m-d H:i:s'), $transaction['tsc_totalharga'], $transaction['tsc_id']]);

        // Redirect to the order history page or any other desired page
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        // Handle database connection errors
        echo 'Connection failed: ' . $e->getMessage();
    }
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
  </head>
  <body>
    <form method="POST" class="form">
      <h1 class="text-center">Payment Form</h1>
      <!-- Progress bar -->
      <div class="progressbar">
          <div
          class="progress-step progress-step-active"
          data-title="Service"
        ></div>
        <div class="progress-step progress-step-active" data-title="Delivery"></div>
        <div class="progress-step progress-step-active" data-title="Total"></div>
        <div class="progress-step progress-step-active" data-title="Payment"></div>
      </div>

      <!-- Steps -->
      <div class="form-step form-step-active">
        <div class="input-group">
            <label for="pm_method">Payment Method</label>
            <input type="text" name="pm_method" id="pm_method" placeholder="Enter your payment method" />
        </div>
        <div class="input-group">
          <label for="confirmPassword">Warning</label>
          <span class="warning-text">Please remember to save your payment receipt.</span>
        </div>        
        <div class="btns-group">
          <a href="paymenttotal.php" class="btn btn-prev">Previous</a>
          <input type="submit" value="Submit" class="btn" />
        </div>
      </div>
    </form>
  </body>
</html>