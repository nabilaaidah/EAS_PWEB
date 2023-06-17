<?php
require 'koneksi.php'; // Assuming the database.php file is in the same directory

$message = ''; // Initialize the message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare the data for insertion
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $cst_uname = $_POST['cst_uname'];
    $cst_password = $_POST['cst_password'];
    $gender = $_POST['gender'];

    try {
        // Insert the data into the database
        $sql = "INSERT INTO customer (cst_name, cst_age, cst_address, cst_phonenumber, cst_username, cst_password, cst_gender)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $statement = $pdo->prepare($sql);
        $statement->execute([$full_name, $age, $address, $phone_number, $cst_uname, $cst_password, $gender]);

        $message = "Registration successful!";
        header("Location: login.php"); // Redirect to login.php
        exit();
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
    <link rel="stylesheet" href="css/register_style.css">
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
        background-color: #ff3333;
        color: #ffffff;
        }
    </style>

  </head>
  <body>
    <div class="container">
    <div>Welcome LaundryDar</div>
      <div class="title">Registration</div>
      <div class="content">
        <form method="POST">
          <div class="user-details">
            <div class="input-box">
              <span class="details">Full Name</span>
              <input type="text" name="full_name" placeholder="Enter your name" required>
            </div>
            <div class="input-box">
              <span class="details">Age</span>
              <input type="number" name="age" placeholder="Enter your age" required>
            </div>
            <div class="input-box">
              <span class="details">Address</span>
              <input type="text" name="address" placeholder="Enter your address" required>
            </div>
            <div class="input-box">
              <span class="details">Phone Number</span>
              <input type="text" name="phone_number" placeholder="Enter your number" required>
            </div>
            <div class="input-box">
              <span class="details">Username</span>
              <input type="text" name="cst_uname" placeholder="Enter your username" required>
            </div>
            <div class="input-box">
              <span class="details">Password</span>
              <input type="password" name="cst_password" placeholder="Enter your password" required>
            </div>
          </div>
          <div class="gender-details">
            <input type="radio" name="gender" id="dot-1" value="M" required>
            <input type="radio" name="gender" id="dot-2" value="F" required>
            <span class="gender-title">Gender</span>
            <div class="category">
              <label for="dot-1">
                <span class="dot one"></span>
                <span class="gender">Male</span>
              </label>
              <label for="dot-2">
                <span class="dot two"></span>
                <span class="gender">Female</span>
              </label>
            </div>
          </div>
          <div class="button">
            <a href="login.php"><input type="submit" value="Register"></a>
          </div>
          <?php if ($message && $message !== 'Registration successful!'): ?>
            <div class="message error">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        </form>
      </div>
    </div>
  </body>
</html>
