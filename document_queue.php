<?php
session_start();
require_once 'role_access_control.php';
require_once 'db.php';

$adminEmail = $_SESSION['admin_email'] ?? 'Guest';
$adminRole = $_SESSION['admin_role'] ?? 'User';

$result = []; 
$totalVisitors = 0;
$vipCount = 0;
$nonVipCount = 0;
$waitingCount = 0;
$servingCount = 0;
$completeCount = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['queue_numbers'])) {
    $queueNumbers = explode(',', $_POST['queue_numbers']);
    if (!empty($queueNumbers)) {
        $placeholders = implode(',', array_fill(0, count($queueNumbers), '?'));
        $sql = "DELETE FROM document_queue WHERE queue_number IN ($placeholders)";
        
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute($queueNumbers)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $stmt->errorInfo()]);
        }
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit;
    }
}

try {
    $sql = "SELECT 
                dq.queue_number, 
                dq.priority, 
                dq.status, 
                dq.time_created, 
                dq.service_id,
                s.Name as service_name
            FROM document_queue dq
            LEFT JOIN service s ON dq.service_id = s.service_id
            ORDER BY 
                CASE 
                    WHEN dq.priority = 'VIP' THEN 1 
                    ELSE 2 
                END, dq.time_created";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $row) {
        $totalVisitors++;

        if (strtoupper($row['priority']) === 'VIP') {
            $vipCount++;
        } else {
            $nonVipCount++;
        }

        $status = strtolower($row['status']);
        if ($status === 'waiting') {
            $waitingCount++;
        } elseif ($status === 'serving') {
            $servingCount++;
        } elseif ($status === 'completed' || $status === 'complete') {
            $completeCount++;
        }
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $result = []; 
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Service Queue Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
          body {
      font-family: 'Quicksand', sans-serif;
      background: linear-gradient(to right, #dfe3ee, #fefefe);
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
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);

      text-align: center;
    }

    .navbar h1 {
      color: #363753;
      font-size: 42px;
      margin: 0;
      font-weight: bold;
    }

        .profile {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-left: auto; 
        }

        .profile-info {
            margin-left: 10px; 
        }

        .logout-button {
            background-color: #5cd2c6;
            color: white; 
            border: none; 
            padding: 8px 15px; 
            cursor: pointer; 
            border-radius: 3px; 
            transition: background-color 0.3s; 
            margin-left: 10px; 
        }

        .action-button {
            background-color: #5cd2c6;
            color: white; 
            text-decoration: none;
            border: none; 
            padding: 8px 15px; 
            cursor: pointer; 
            border-radius: 3px; 
            transition: background-color 0.3s; 
            margin: 5px;
        }

        .logout-button:hover, .action-button:hover {
            background-color:rgb(66, 151, 143); 
        }

        .container {
            display: flex;
            width: 100%;
            height: calc(100vh - 70px);
        }

        .nav-menu {
            background-color: #000;
            color: white;
            padding: 20px; 
            width: 200px; 
            height: 100%;
            border-radius: 5px 0 0 5px; 
            display: flex; 
            flex-direction: column; 
        }

        .nav-menu h4 {
            color: #dfe3ee;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            display: block; 
            padding: 10px 0; 
        }

        .nav-menu a:hover {
            background-color: #333; 
            border-radius: 5px; 
        }

        .content {
            flex-grow: 1;
            padding: 20px; 
            overflow-y: auto;
            background-color: transparent;
            border-radius: 0 5px 5px 0;
        }

        h1 {
            color: #363735; 
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 10px; 
            text-align: center; 
            border-bottom: 1px solid #ccc; 
        }
        
        th {
            background-color: #dfe3ee; 
            color: #363735;
        }
        
        tr:hover {
            background-color: #f5f5f5; 
        }

        .visitor-report {
            margin-bottom: 20px; 
            margin-top: 10px; 
            font-size: 16px; 
            display: none; 
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>BANK INDEPENDENT</h1>
    <p>Jl. Bambu Kuning Selatan, RT.003/RW.004, Sepanjang Jaya, Kec. Rawalumbu, Kota Bks, Jawa Barat 17114</p>
    <div class="profile">
        <div class="profile-info">
            <span>Role: <?php echo htmlspecialchars($adminRole); ?></span> | 
            <span>Email: <?php echo htmlspecialchars($adminEmail); ?></span>
        </div>
        <button class="logout-button" id="logoutButton">Logout</button>
    </div>
</div>

<div class="container">
    <div class="nav-menu">
        <h4>Navigation</h4>
        <a href="teller_queue.php">Teller Service</a>
        <a href="cs_queue.php">Customer Service</a>
        <a href="mobile_queue.php">Mobile Banking</a>
        <a href="document_queue.php">Document Service</a>
        <a href="assistance_queue.php">Friendly Assistance</a>
        <a href="account_queue.php">Account Safety</a>
    </div>
    
    <div class="content">
        <h1>Document Service Queue Dashboard</h1>

        <button class="action-button" id="toggleVisitorReportButton">Show Visitor Report</button>
        
        <div class="visitor-report" id="visitorReport">
            <p>Number of visitors today: <strong><?php echo $totalVisitors; ?></strong></p>
            <p>VIP: <strong><?php echo $vipCount; ?></strong></p>
            <p>Non-VIP: <strong><?php echo $nonVipCount; ?></strong></p>

            <table>
                <thead>
                    <tr>
                        <th>Waiting</th>
                        <th>Serving</th>
                        <th>Completed</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong><?php echo $waitingCount; ?></strong></td>
                        <td><strong><?php echo $servingCount; ?></strong></td>
                        <td><strong><?php echo $completeCount; ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Queue Number</th>
                    <th>Service Type</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Time Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($result)): ?>
                    <?php foreach ($result as $row): ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="select-row" value="<?php echo htmlspecialchars($row['queue_number']); ?>">
                            </td>
                            <td><?php echo htmlspecialchars($row['queue_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['service_name'] ?? 'Unknown'); ?></td>
                            <td><?php echo !empty($row['priority']) ? htmlspecialchars($row['priority']) : 'Non-VIP'; ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['time_created']); ?></td>
                            <td>
                                <a href="document_update.php?queue_number=<?php echo htmlspecialchars($row['queue_number']); ?>" class="action-button">Update</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <button class="action-button" id="deleteSelectedButton">Delete Selected</button>
    </div>
</div>

<script>
    document.getElementById('logoutButton').addEventListener('click', function() {
        Swal.fire({
            title: "Are you sure?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, logout!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'login.php'; 
            }
        });
    });

    document.getElementById('toggleVisitorReportButton').addEventListener('click', function() {
        const reportDiv = document.getElementById('visitorReport');
        if (reportDiv.style.display === 'none' || reportDiv.style.display === '') {
            reportDiv.style.display = 'block'; 
            this.textContent = 'Hide Visitor Report'; 
        } else {
            reportDiv.style.display = 'none'; 
            this.textContent = 'Show Visitor Report'; 
        }
    });

    document.getElementById('deleteSelectedButton').addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.select-row:checked')).map(checkbox => checkbox.value);
        
        if (selected.length > 0) {
            Swal.fire({
                title: "Are you sure you want to delete selected items?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete!"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({ queue_numbers: selected.join(',') }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire("Deleted!", "Selected items have been deleted.", "success");
                            location.reload(); 
                        } else {
                            Swal.fire("Error!", "There was an error deleting the items: " + (data.error ? data.error : ""), "error");
                        }
                    });
                }
            });
        } else {
            Swal.fire("Warning!", "No items selected.", "warning");
        }
    });
</script>

</body>
</html>