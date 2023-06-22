<?php
session_start(); // Start the session

// Establish a database connection using PDO
$pdo = new PDO('mysql:host=localhost;dbname=LaundryDar', 'root', 'phpmyadmin');
// Set PDO error mode to exception
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if the payment method is submitted
if (isset($_POST['pm_method'])) {
    // Your existing code here

    // Fetch the latest transaction for the customer
    $stmt = $pdo->prepare('SELECT * FROM transaction WHERE customer_cst_id = ? ORDER BY tsc_id DESC LIMIT 1');
    $stmt->execute([$_SESSION['customerId']]); // Retrieve customer ID from session
    $transaction = $stmt->fetch();

    // Your existing code here

    // Redirect to the order history page or any other desired page
    header("Location: dashboard.php");
    exit();
}

// Delete a row
if (isset($_POST['delete_row'])) {
    $rowId = $_POST['delete_row'];

    // Delete the related rows from the transaction_detail table
    $stmt = $pdo->prepare('DELETE FROM transaction_detail WHERE transaction_tsc_id = ?');
    $stmt->execute([$rowId]);

    $stmt = $pdo->prepare('DELETE FROM payment WHERE transaction_tsc_id = ?');
    $stmt->execute([$rowId]);

    $stmt = $pdo->prepare('DELETE FROM delivery WHERE transaction_tsc_id = ?');
    $stmt->execute([$rowId]);

    // Delete the row from the transaction table
    $stmt = $pdo->prepare('DELETE FROM transaction WHERE tsc_id = ?');
    $stmt->execute([$rowId]);

    // Redirect to the order history page or any other desired page
    header("Location: orderhistory.php");
    exit();
}

// Display all rows for a specific customer
try {
    // Fetch all transactions for the customer
    $stmt = $pdo->prepare('SELECT * FROM transaction WHERE customer_cst_id = ?');
    $stmt->execute([$_SESSION['customerId']]); // Retrieve customer ID from session
    $transactions = $stmt->fetchAll();
} catch (PDOException $e) {
    // Handle database connection errors
    echo 'Connection failed: ' . $e->getMessage();
}
?>

<!-- Rest of your HTML code -->


<!-- Your HTML code here -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>LaundryDar</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="Free HTML Templates" name="keywords"/>
    <meta content="Free HTML Templates" name="description"/>

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon"/>

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;800&display=swap" rel="stylesheet"/>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet"/>

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet"/>

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/csshome/style.css" rel="stylesheet"/>
</head>

<body>
<!-- Navbar Start -->
<div class="container-fluid position-relative nav-bar p-0">
    <div class="container-lg position-relative p-0 px-lg-3" style="z-index: 9">
        <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0 pl-3 pl-lg-5">
            <a href="" class="navbar-brand">
                <h1 class="m-0 text-secondary">
                    <span class="text-primary">Laundry</span>Dar
                </h1>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="orderhistory.php">Order History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
<!-- Navbar End -->

<!-- Page Header Start -->
<div class="page-header py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center text-secondary">Order History</h1>
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Order History Start -->
<div class="container pb-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Entry Date</th>
                        <th>Completion Date</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($transactions as $transaction) { ?>
                        <tr>
                            <td><?php echo $transaction['tsc_id']; ?></td>
                            <td><?php echo $transaction['tsc_tglmasuk']; ?></td>
                            <td><?php echo $transaction['tsc_tglselesai']; ?></td>
                            <td><?php echo $transaction['tsc_totalharga']; ?></td>
                            <td>
                                <form method="post" action="">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this transaction?')"
                                            name="delete_row" value="<?php echo $transaction['tsc_id']; ?>">Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Order History End -->

<!-- Your JavaScript code here -->
<!-- Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.min.js"></script>

<!-- Libraries -->
<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Custom Javascript -->
<script src="js/script.js"></script>

</body>
</html>
