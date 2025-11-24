<?php
require_once 'db.php';

$serviceType = $_GET['service'] ?? 'Unknown';
$priority = 'Non-VIP';
$queueNumber = '';
$currentTime = date('Y-m-d H:i:s');

$validServices = [
    'Teller' => 'A',
    'Customer Service' => 'B',
    'Mobile Banking' => 'C',
    'Document Service' => 'D',
    'Friendly Assistance' => 'E',
    'Account Safety' => 'F'
];

if (!array_key_exists($serviceType, $validServices)) {
    die('Invalid service type: ' . htmlspecialchars($serviceType));
}

$prefix = $validServices[$serviceType];
$tableMap = [
    'Teller' => 'teller_queue',
    'Customer Service' => 'cs_queue',
    'Mobile Banking' => 'mobile_queue',
    'Document Service' => 'document_queue',
    'Friendly Assistance' => 'assistance_queue',
    'Account Safety' => 'account_queue'
];

$tableName = $tableMap[$serviceType];

$stmt = $pdo->prepare("SELECT MAX(queue_number) AS max_number FROM $tableName");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$lastQueueNumber = $row['max_number'] ?? null;

if (!isset($lastQueueNumber)) {
    $nextNumber = 1;
} else {
    preg_match('/\d+/', $lastQueueNumber, $matches);
    if (empty($matches)) {
        die('Invalid queue number format in database!');
    }
    $nextNumber = (int)$matches[0] + 1;
}

$queueNumber = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

$stmt = $pdo->prepare("INSERT INTO $tableName (queue_number, priority, status, time_created, service_id) VALUES (?, ?, ?, ?, ?)");
$serviceIDQuery = $pdo->prepare("SELECT service_id FROM service WHERE Name = ?");
$serviceIDQuery->execute([$serviceType]);

$serviceIDRow = $serviceIDQuery->fetch(PDO::FETCH_ASSOC);

if (!$serviceIDRow) {
    die("Error: Unable to find service ID for '$serviceType'.");
}

try {
    $stmt->execute([$queueNumber, $priority, 'waiting', $currentTime, $serviceIDRow['service_id']]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Successful Register</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background: linear-gradient(to right, #363753, #fefefe);
            color: #333;
            margin: 0;
            background-size: 200% 100%;
            animation: moveBackground 5s linear infinite;
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

        .container {
            margin: 70px auto;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            padding: 50px;
            max-width: 800px;
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .queue-number {
            font-size: 120px;
            color: #363753;
            font-family: Arial, sans-serif;
        }

        .info {
            text-align: left;
            margin-left: 0;
        }

        .info h3 {
            margin: 10px 0;
            font-size: 28px;
            color: rgb(0, 0, 0);
        }

        .info p {
            font-size: 22px;
            color: rgb(0, 0, 0);
            margin: 10px 0;
        }

        .login-success {
            margin-top: 20px;
            font-size: 48px;
            text-align: center;
            color: #363753;
        }

        .btn-back {
            display: inline-block;
            margin: 20px 10px;
            padding: 10px 20px;
            color: #fefefe;
            background-color: #5cd2c6;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .btn-back:hover {
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
    </style>
</head>
<body>
    <div class="navbar">
        <h1>BANK INDEPENDENT</h1>
        <p>Jl. Bambu Kuning Selatan, RT.003/RW.004, Sepanjang Jaya, Kec. Rawalumbu, Kota Bks, Jawa Barat 17114</p>
    </div>

    <div class="container" id="captureArea">
        <h1 class="login-success">Successful register</h1> 
        <div class="row">
            <div class="queue-number" id="queue-number"><?php echo htmlspecialchars($queueNumber); ?></div>
            <div class="info">
                <h3 id="selected-service">Service type: <?php echo htmlspecialchars($serviceType); ?></h3>
                <p class="time">Time Created: <strong><?php echo htmlspecialchars($currentTime); ?></strong></p>
                <p class="waiting-message" style="font-size: 16px;">Please wait, thank you for using BANK services.</p>
            </div>
        </div>
    </div>

    <div style="text-align: center;">
        <button class="btn-back" id="btnBack">Back</button>
        <button class="btn-back" id="downloadBtn">Download PNG</button>
    </div>

    <script>
        document.getElementById("btnBack").onclick = function() {
            Swal.fire({
                title: "Back to menu?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "Wait"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'customer.php';
                }
            });
        };

        document.getElementById("downloadBtn").onclick = function () {
            const captureTarget = document.getElementById("captureArea");
            html2canvas(captureTarget).then(canvas => {
                const link = document.createElement('a');
                link.download = 'Queue_<?php echo $queueNumber; ?>.png';
                link.href = canvas.toDataURL("image/png");
                link.click();
            });
        };
    </script>

    <footer>
        <p>&copy; <?= date("Y") ?> BANK INDEPENDENT. Cikarang.</p>
    </footer>
</body>
</html>
