<?php
session_start();
include 'db.php';

$stmtServices = $pdo->prepare("SELECT * FROM service WHERE service_id !=7");
$stmtServices->execute();
$services = $stmtServices->fetchAll();

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['loginAs'])) {
        $selectedService = $_POST['loginAs'];
        header("Location: receive.php?service=" . urlencode($selectedService));
        exit();
    } else {
        $error = "Please select a service.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Customer - BANK INDEPENDENT</title>

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      background: linear-gradient(to right, #363753, #fefefe);
      color: #333;
      margin: 0;
      background-size: 200% 100%;
      animation: moveBackground 5s linear infinite;
    }

    @keyframes moveBackground {
      0% { background-position: 100% 0; }
      25% { background-position: 50% 50%;}
      50% { background-position: 25% 75%; }
      100% { background-position: 0 100%; }
    }

    .navbar {
      background-color: #fefefe;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .navbar h1 {
      color: #363753;
      font-size: 42px;
      margin: 0;
      font-weight: bold;
    }

    .form-section {
      max-width: 600px;
      background-color: rgba(255, 255, 255, 0.9);
      margin: 70px auto;
      padding: 50px 40px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
    }

    .form-section h2 {
      text-align: center;
      color: #363753;
      margin-bottom: 30px;
      font-size: 30px;
      font-weight: 700;
    }

    .service-option {
      background-color: #dfe3ee;
      padding: 15px 20px;
      margin-bottom: 15px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      transition: transform 0.2s;
    }

    .service-option:hover {
      transform: translateY(-3px);
    }

    .service-option input {
      margin-right: 10px;
    }

    label {
      font-weight: bold;
      color: #363753;
      font-size: 1.1rem;
    }

    .btn-submit {
      width: 100%;
      padding: 15px;
      font-size: 1.1rem;
      background-color: #5cd2c6;
      border: none;
      border-radius: 10px;
      color: #fff;
      transition: background-color 0.3s;
      cursor: pointer;
    }

    .btn-submit:hover {
      background-color: rgb(64, 155, 146);
    }

    footer {
      text-align: center;
      padding: 20px 0;
      background-color: #fefefe;
      margin-top: 60px;
      font-size: 14px;
      color: #555;
    }
    .logout-button {
  background-color: #5cd2c6;
  color: white; 
  border: none; 
  padding: 10px 20px; 
  cursor: pointer; 
  border-radius: 5px; 
  transition: background-color 0.3s; 
  text-decoration: none;
  display: inline-block;
  margin-top: 20px;
}
.logout-button:hover {
  background-color: rgb(64, 155, 146);
}

  </style>
</head>
<body>

<div class="navbar">
  <h1>BANK INDEPENDENT</h1>
  <p>Jl. Bambu Kuning Selatan, RT.003/RW.004, Sepanjang Jaya, Kec. Rawalumbu, Kota Bks, Jawa Barat 17114</p>
</div>

<div class="form-section">
  <h2>Get Queue Number</h2>
  <form method="POST" id="serviceForm" onsubmit="handleFormSubmit(event)">
    <?php foreach ($services as $service): ?>
      <div class="service-option">
        <label>
          <input type="radio" name="loginAs" value="<?= htmlspecialchars($service['Name']) ?>">
          <?= htmlspecialchars($service['Name']) ?>
        </label>
      </div>
    <?php endforeach; ?>
    <button type="submit" class="btn-submit">Select Service</button>
    <p><a href="customer_info.php"class="logout-button">Back To Dashboard</a></p>
  </form>
</div>

<script>
function handleFormSubmit(event) {
  event.preventDefault();
  const selected = document.querySelector('input[name="loginAs"]:checked');

  if (selected) {
    Swal.fire({
      title: "Service Selected!",
      text: "You chose: " + selected.value,
      icon: "success",
      confirmButtonColor: '#5cd2c6'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById("serviceForm").submit();
      }
    });
  } else {
    Swal.fire({
      title: "Oops!",
      text: "Please select a service before proceeding.",
      icon: "warning",
      confirmButtonColor: '#5cd2c6'
    });
  }
}
</script>

<footer>
  <p>&copy; <?= date("Y") ?> BANK INDEPENDENT. Cikarang.</p>
</footer>

</body>
</html>