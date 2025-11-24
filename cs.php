<?php
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM cs_queue ORDER BY time_created DESC");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Customer Info - BANK INDEPENDENT</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      background: linear-gradient(to right, #363753, #fefefe);
      color: #fff;
      margin: 0;
      background-size: 200% 100%;
      animation: moveBackground 5s linear infinite;
    }

    @keyframes moveBackground {
      0% { background-position: 100% 0; }
      25% { background-position: 50% 50%; }
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

    .navbar p {
      font-size: 14px;
      color: #555;
      margin-top: 5px;
    }

    .container {
      margin-top: 50px;
      padding: 0 30px;
    }

    .left-info h2 {
      font-size: 26px;
      font-weight: 700;
    }

    .left-info p, .left-info ul {
      font-size: 16px;
    }

    .left-info ul {
      padding-left: 20px;
    }

    .table-section h3 {
      color: #fff;
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      color: #333;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }

    th {
      background-color: #5cd2c6;
      color: white;
    }

    .btn-back {
      margin-top: 20px;
      background-color: #5cd2c6;
      text-decoration: none;
      color: white;
      padding: 10px 5px;
      width: 300px ;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      display: block;
      text-align: center;
    }

    .btn-back:hover {
      background-color: rgb(64, 155, 146);
    }

  </style>
</head>
<body>

<div class="navbar">
  <h1>BANK INDEPENDENT</h1>
  <p>Jl. Bambu Kuning Selatan, Sepanjang Jaya, Rawalumbu, Kota Bks, Jawa Barat</p>
</div>

<div class="container">
  <div class="row g-4 align-items-start">
    <div class="col-md-6 left-info">
      <h2>Customer Support Service</h2>
      <p><strong>See the queue number</strong> to know your turn in line.</p>
      <p>Services typically provided by our Customer Support Team include:</p>
      <ul>
        <li>Handling customer complaints and inquiries</li>
        <li>Providing assistance with online and mobile banking</li>
        <li>Guiding customers on card issues or lost accounts</li>
        <li>Following up on unresolved service requests</li>
      </ul>
      <a href="customer_info.php" class="btn-back">Back to Home</a>
    </div>
    
    <div class="col-md-6 table-section">
      <h3 class="mb-4">Customer Support Queue</h3>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>No.</th>
              <th>Queue Number</th>
              <th>Priority</th>
              <th>Status</th>
              <th>Time Created</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($results && count($results) > 0) {
              $no = 1;
              foreach ($results as $row) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['queue_number']}</td>
                        <td>{$row['priority']}</td>
                        <td>{$row['Status']}</td>
                        <td>{$row['time_created']}</td>
                      </tr>";
                $no++;
              }
            } else {
              echo "<tr><td colspan='5' class='text-center'>No data available.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

</body>
</html>
