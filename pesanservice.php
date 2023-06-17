<?php
session_start();
require 'koneksi.php';

// Memeriksa apakah pengguna sudah login dengan memeriksa keberadaan customerId dalam session
if (!isset($_SESSION['customerId'])) {
    // Jika customerId tidak ada dalam session, redirect ke halaman login atau halaman lainnya
    header("Location: login.php");
    exit();
}

// Mendapatkan customerId dari session
$customerId = $_SESSION['customerId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize the database connection here if not already initialized
    if (!isset($pdo)) {
        $pdo = new PDO('mysql:host=localhost;dbname=LaundryDar', 'root', 'phpmyadmin');
    }

    $stmt = $pdo->query('SELECT MAX(tsc_id) FROM transaction');
    $maxId = $stmt->fetchColumn();
    $newtscId = $maxId + 1;

    $tglSelesai = date('Y-m-d', strtotime('+3 days'));

    $stmt = $pdo->prepare('INSERT INTO transaction (tsc_tglmasuk, tsc_tglselesai, tsc_tglambil, tsc_totalharga, customer_cst_id) 
                           VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([date('Y-m-d'), $tglSelesai, null, 0, $customerId]);

    // Store the tsc_id in the session
    $_SESSION['tscId'] = $newtscId;

    $addmore = $_POST['addmore'] ?? [];

    $totalPrice = 0;
    foreach ($addmore as $detail) {
        $stmt = $pdo->query('SELECT MAX(td_id) FROM transaction_detail');
        $maxId = $stmt->fetchColumn();
        $newId = $maxId + 1;

        $selectedService = $detail['service'] ?? '';

        $stmt = $pdo->prepare('SELECT * FROM service WHERE svc_name = ?');
        $stmt->execute([$selectedService]);
        $service = $stmt->fetch();

        $service_svc_id = null;
        $svc_priceperkilo = 0;

        if ($service) {
            $service_svc_id = $service['svc_id'];
            $svc_priceperkilo = $service['svc_priceperkilo'];
        }

        $qty = $detail['qty'] ?? 0;
        $totaltd = $svc_priceperkilo * $qty;

        $stmt = $pdo->prepare('INSERT INTO transaction_detail (td_quantity, td_pricequantity, service_svc_id, transaction_tsc_id) 
                       VALUES (?, ?, ?, ?)');
        $stmt->execute([$qty, $totaltd, $service_svc_id, $newtscId]);

        $totalPrice += $totaltd;
    }

    $deliveryNeeded = ($_POST['delivery'] === 'yes') ?? false;

    $address = $_POST['address'] ?? '';

    $deliveryprice = 0;
    if ($deliveryNeeded) {
        $randomNumber = mt_rand(10000, 30000);
        $deliveryprice = round($randomNumber, -3);

        $stmt = $pdo->query('SELECT MAX(div_id) FROM delivery');
        $maxId = $stmt->fetchColumn();
        $newId = $maxId + 1;

        $timestamp = strtotime('+3 days');
        $dateAhead = date('Y-m-d', $timestamp);

        $stmt = $pdo->prepare('INSERT INTO delivery (div_address, div_date, div_price, transaction_tsc_id) 
                               VALUES (?, ?, ?, ?)');
        $stmt->execute([$address, $dateAhead, $deliveryprice, $newtscId]);
    }

    $totalPrice += $deliveryprice;

    $stmt = $pdo->prepare('UPDATE transaction SET tsc_totalharga = ? WHERE tsc_id = ?');
    $stmt->execute([$totalPrice, $newtscId]);

    header("Location: paymenttotal.php");
    exit();
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
      #addMoreBtn {
        margin-top: 10px;
      }
    </style>
  </head>
  <body>
    <form method="POST" class="form">
      <h1 class="text-center">Order Form</h1>
      <!-- Progress bar -->
      <div class="progressbar">
        <div class="progress" id="progress"></div>
        
        <div
          class="progress-step progress-step-active"
          data-title="Service"
        ></div>
        <div class="progress-step" data-title="Delivery"></div>
        <div class="progress-step" data-title="Total"></div>
        <div class="progress-step" data-title="Payment"></div>
      </div>

      <!-- Steps -->
      <div class="form-step form-step-active">
        <!-- First form step -->
        <div class="formwrapper">
          <div id="FormItems">
            <!-- transactionform.blade.php -->
            <table>
              <tr>
                <td>
                  <select name="addmore[0][service]" class="service form-control">
                    <option value="">Select a service</option>
                    <option value="Cuci Biasa">Cuci Biasa</option>
                    <option value="Cuci Kilat">Cuci Kilat</option>
                    <option value="Setrika Saja">Setrika Saja</option>
                    <option value="Cuci + Setrika">Cuci + Setrika</option>
                    <option value="Cuci Karpet">Cuci Karpet</option>
                    <option value="Cuci Springbed">Cuci Springbed</option>
                    <option value="Cuci Boneka">Cuci Boneka</option>
                    <option value="Cuci Jas">Cuci Jas</option>
                    <option value="Cuci Sepatu">Cuci Sepatu</option>
                    <option value="Cuci Tas">Cuci Tas</option>
                  </select>
                </td>
                <td>
                  <input type="text" name="addmore[0][qty]" placeholder="Enter your Qty" class="form-control" />
                </td>
              </tr>
            </table>
          </div>
          <input type="button" value="Add More" class="Addmore" onclick="addMoreFields()" id="addMoreBtn">
          <a href="#" class="btn btn-next">Next</a>
        </div>
      </div>
      <div class="form-step">
        <div class="input-group">
          <label for="delivery">Do you need delivery?</label>
          <div class="radio-group">
            <input type="radio" name="delivery" id="delivery-yes" value="yes" required />
            <label for="delivery-yes">Yes</label>
            <input type="radio" name="delivery" id="delivery-no" value="no" required />
            <label for="delivery-no">No</label>
          </div>
        </div>
        <div class="input-group">
          <label for="address">Address Optional</label>
          <input type="text" name="address" id="address" />
        </div>
        <div class="btns-group">
          <a href="#" class="btn btn-prev">Previous</a>
          <input type="submit" value="Submit" class="btn" />
        </div>
      </div>
    </form>

    <script>
      function addMoreFields() {
        var formItems = document.getElementById("FormItems");
        var rowCount = formItems.getElementsByTagName("tr").length;

        var newRow = document.createElement("tr");

        var serviceCell = document.createElement("td");
        var serviceSelect = document.createElement("select");
        serviceSelect.setAttribute("name", "addmore[" + rowCount + "][service]");
        serviceSelect.classList.add("service", "form-control");

        var serviceOption = document.createElement("option");
        serviceOption.setAttribute("value", "");
        serviceOption.appendChild(document.createTextNode("Select a service"));
        serviceSelect.appendChild(serviceOption);

        var serviceOptions = [
            "Cuci Biasa",
            "Cuci Kilat",
            "Setrika Saja",
            "Cuci + Setrika",
            "Cuci Karpet",
            "Cuci Springbed",
            "Cuci Boneka",
            "Cuci Jas",
            "Cuci Sepatu",
            "Cuci Tas",
        ];

        serviceOptions.forEach(function (option) {
            var serviceOption = document.createElement("option");
            serviceOption.setAttribute("value", option);
            serviceOption.appendChild(document.createTextNode(option));
            serviceSelect.appendChild(serviceOption);
        });

        serviceCell.appendChild(serviceSelect);
        newRow.appendChild(serviceCell);

        var qtyCell = document.createElement("td");
        var qtyInput = document.createElement("input");
        qtyInput.setAttribute("type", "text");
        qtyInput.setAttribute("name", "addmore[" + rowCount + "][qty]");
        qtyInput.setAttribute("placeholder", "Enter your Qty");
        qtyInput.classList.add("form-control");
        qtyCell.appendChild(qtyInput);
        newRow.appendChild(qtyCell);

        formItems.appendChild(newRow);
        }

    </script>
  </body>
</html>