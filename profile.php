<?php
// Start the session
session_start();

// Create a new PDO instance
try {
    $pdo = new PDO('mysql:host=localhost;dbname=LaundryDar', 'root', 'phpmyadmin');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Check if the customerId is set in the session
if (isset($_SESSION['customerId'])) {
    // Retrieve the customerId from the session
    $customerId = $_SESSION['customerId'];

    // Check if the customerId data is available
    if (isset($customerId) && !empty($customerId)) {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Get the updated data from the form submission
                $name = $_POST['name'];
                $age = $_POST['age'];
                $address = $_POST['address'];

                // Prepare the SQL query
                $stmt = $pdo->prepare("UPDATE customer SET cst_name = :name, cst_age = :age, cst_address = :address WHERE cst_id = :customerId");

                // Bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':age', $age);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':customerId', $customerId);

                // Execute the query
                $stmt->execute();
            }

            // Prepare the SQL query to retrieve the updated data
            $stmt = $pdo->prepare("SELECT * FROM customer WHERE cst_id = :customerId");

            // Bind the customerId parameter
            $stmt->bindParam(':customerId', $customerId);

            // Execute the query
            $stmt->execute();

            // Fetch the row as an associative array
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if a row is found
            if ($row) {
                // Extract row data
                $name = $row['cst_name'];
                $age = $row['cst_age'];
                $customerIdname = $row['cst_username'];
                $phoneNumber = $row['cst_phonenumber'];
                $address = $row['cst_address'];
            } else {
                // Handle the case where the row is not found
                $name = 'N/A';
                $age = 'N/A';
                $customerIdname = 'N/A';
                $phoneNumber = 'N/A';
                $address = 'N/A';
            }
        } catch (PDOException $e) {
            // Handle the database connection error
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Handle the case where customerId data is not available
        $name = 'N/A';
        $age = 'N/A';
        $customerIdname = 'N/A';
        $phoneNumber = 'N/A';
        $address = 'N/A';
    }
} else {
    // Handle the case when the customerId is not set in the session
    echo "Customer ID not found!";
}
?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>LaundryDar</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Free HTML Templates" name="keywords" />
    <meta content="Free HTML Templates" name="description" />

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon" />

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;800&display=swap"
      rel="stylesheet"
    />

    <!-- Font Awesome -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css"
      rel="stylesheet"
    />

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/csshome/style.css" rel="stylesheet" />
  </head>

  <body>
    <!-- Topbar Start -->
    <div class="container-fluid bg-primary py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-6 text-center text-lg-left mb-2 mb-lg-0">
            <div class="d-inline-flex align-items-center">
              <a class="text-white pr-3" href="">FAQs</a>
              <span class="text-white">|</span>
              <a class="text-white px-3" href="">Help</a>
              <span class="text-white">|</span>
              <a class="text-white pl-3" href="">Support</a>
            </div>
          </div>
          <div class="col-md-6 text-center text-lg-right">
            <div class="d-inline-flex align-items-center">
              <a class="text-white px-3" href="">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a class="text-white px-3" href="">
                <i class="fab fa-twitter"></i>
              </a>
              <a class="text-white px-3" href="">
                <i class="fab fa-linkedin-in"></i>
              </a>
              <a class="text-white px-3" href="">
                <i class="fab fa-instagram"></i>
              </a>
              <a class="text-white pl-3" href="">
                <i class="fab fa-youtube"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid position-relative nav-bar p-0">
      <div
        class="container-lg position-relative p-0 px-lg-3"
        style="z-index: 9"
      >
        <nav
          class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0 pl-3 pl-lg-5"
        >
          <a href="" class="navbar-brand">
            <h1 class="m-0 text-secondary">
              <span class="text-primary">Laundry</span>Dar
            </h1>
          </a>
          <button
            type="button"
            class="navbar-toggler"
            data-toggle="collapse"
            data-target="#navbarCollapse"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div
            class="collapse navbar-collapse justify-content-between px-3"
            id="navbarCollapse"
          >
            <div class="navbar-nav ml-auto py-0">
              <a href="dashboard.php" class="nav-item nav-link">Home</a>
              <a href="orderhistory.php" class="nav-item nav-link">Order History</a>
              <a href="about.php" class="nav-item nav-link">About</a>
              <div class="nav-item dropdown">
                <a
                  href="#"
                  class="nav-link dropdown-toggle active"
                  data-toggle="dropdown"
                  >Profile</a
                >
                <div class="dropdown-menu border-0 rounded-0 m-0">
                  <a href="profile.php" class="dropdown-item active"
                    >My Profile</a
                  >
                  <a href="login.php" class="dropdown-item">Log Out</a>
                </div>
              </div>
              <a href="" class="nav-item nav-link">Contact</a>
            </div>
          </div>
        </nav>
      </div>
    </div>
    <!-- Navbar End -->

    <!-- Page Header Start -->
    <div
      class="page-header container-fluid bg-secondary pt-2 pt-lg-5 pb-2 mb-5"
    >
      <div class="container py-5">
        <div class="row align-items-center py-4">
          <div class="col-md-6 text-center text-md-left">
            <h1 class="mb-4 mb-md-0 text-white">My Profile</h1>
          </div>
          <div class="col-md-6 text-center text-md-right">
            <div class="d-inline-flex align-items-center">
              <a class="btn text-white" href="dashboard.php">Home</a>
              <i class="fas fa-angle-right text-white"></i>
              <a class="btn text-white disabled" href="profile.php">Profile</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page Header Start -->

        <!-- Profile Start-->
    <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
                    <span class="font-weight-bold"><?php echo $name; ?></span>
                    <span class="text-black-50"><?php echo $customerIdname; ?></span>
                </div>
            </div>
            <div class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Profile</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="labels">Name</label>
                            <input type="text" class="form-control" value="<?php echo $name; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Age</label>
                            <input type="text" class="form-control" value="<?php echo $age; ?>" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="labels">username</label>
                            <input type="text" class="form-control" value="<?php echo $customerIdname; ?>" readonly>
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Nomor Telepon / WhatsApp</label>
                            <input type="text" class="form-control" value="<?php echo $phoneNumber; ?>" readonly>
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Alamat</label>
                            <input type="text" class="form-control" value="<?php echo $address; ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---Profile End-->


    <!-- Footer Start -->
    <div
      class="container-fluid bg-primary text-white mt-5 pt-5 px-sm-3 px-md-5"
    >
      <div class="row pt-5">
        <div class="col-lg-3 col-md-6 mb-5">
          <a href=""
            ><h1 class="text-secondary mb-3">
              <span class="text-white">Laundry</span>Dar
            </h1></a
          >
          <p>
            Kami mengutamakan kepercayaan pelanggan, privasi, dan keamanan.
            Setiap pakaian yang Anda serahkan kepada kami akan ditangani dengan
            hati-hati dan keamanan yang terjamin.
          </p>
          <div class="d-flex justify-content-start mt-4">
            <a
              class="btn btn-outline-light rounded-circle text-center mr-2 px-0"
              style="width: 38px; height: 38px"
              href="#"
              ><i class="fab fa-twitter"></i
            ></a>
            <a
              class="btn btn-outline-light rounded-circle text-center mr-2 px-0"
              style="width: 38px; height: 38px"
              href="#"
              ><i class="fab fa-facebook-f"></i
            ></a>
            <a
              class="btn btn-outline-light rounded-circle text-center mr-2 px-0"
              style="width: 38px; height: 38px"
              href="#"
              ><i class="fab fa-linkedin-in"></i
            ></a>
            <a
              class="btn btn-outline-light rounded-circle text-center mr-2 px-0"
              style="width: 38px; height: 38px"
              href="#"
              ><i class="fab fa-instagram"></i
            ></a>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-5">
          <h4 class="text-white mb-4">Hubungi Kami</h4>
          <p>Hubungi kami untuk layanan LaundryDar</p>
          <p>
            <i class="fa fa-map-marker-alt mr-2"></i>Jl. Keputih Perintis IA No.
            42A
          </p>
          <p><i class="fa fa-phone-alt mr-2"></i>+012 345 67890</p>
          <p><i class="fa fa-envelope mr-2"></i>laundrydar@gmail.com</p>
        </div>
        <div class="col-lg-3 col-md-6 mb-5">
          <h4 class="text-white mb-4">Quick Links</h4>
          <div class="d-flex flex-column justify-content-start">
            <a class="text-white mb-2" href="#"
              ><i class="fa fa-angle-right mr-2"></i>Home</a
            >
            <a class="text-white mb-2" href="#"
              ><i class="fa fa-angle-right mr-2"></i>About Us</a
            >
            <a class="text-white" href="#"
              ><i class="fa fa-angle-right mr-2"></i>Contact Us</a
            >
          </div>
        </div>
        <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <img class="rounded-circle mt-5" src="https://placeimg.com/200/200/people" width="90" />
                    <span class="font-weight-bold"><?php echo $customerIdname; ?></span>
                    <span class="text-black-50"><?php echo $phoneNumber; ?></span>
                </div>
            </div>
            <div class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Profile Settings</h4>
                    </div>
                    <form method="POST">
                        <div class="row mt-2">
                            <div class="col-md-6"><label class="labels">Name</label><input type="text" class="form-control" name="name" value="<?php echo $name; ?>" /></div>
                            <div class="col-md-6"><label class="labels">Age</label><input type="text" class="form-control" name="age" value="<?php echo $age; ?>" /></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Address</label><input type="text" class="form-control" name="address" value="<?php echo $address; ?>" /></div>
                        </div>
                        <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="submit">Save Profile</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
      </div>
    </div>
    <div class="container-fluid bg-dark text-white py-4 px-sm-3 px-md-5">
      <p class="m-0 text-center text-white">
        &copy;
        <a class="text-white font-weight-medium" href="#">LaundryDar</a>.
        All Rights Reserved.
      </p>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary back-to-top"
      ><i class="fa fa-angle-double-up"></i
    ></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
  </body>
</html>

