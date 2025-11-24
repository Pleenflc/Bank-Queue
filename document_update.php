<?php
require_once 'db.php';

$queue_number = null;
$result = [];

if (isset($_POST['queue_number']) && isset($_POST['priority']) && isset($_POST['status'])) {
    $queue_number = $_POST['queue_number'];
    $new_priority = $_POST['priority'];
    $new_status = $_POST['status'];

    try {
        $sql = "UPDATE document_queue SET priority = :new_priority, status = :new_status WHERE queue_number = :queue_number";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'new_priority' => $new_priority,
            'new_status' => $new_status,
            'queue_number' => $queue_number
        ]);
        header("Location: document_queue.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_POST['delete_queue_number'])) {
    $queue_number_to_delete = $_POST['delete_queue_number'];

    try {
        $sql = "DELETE FROM document_queue WHERE queue_number = :queue_number";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['queue_number' => $queue_number_to_delete]);
        header("Location: document_queue.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_GET['queue_number'])) {
    $queue_number = $_GET['queue_number'];
    try {
        $sql = "SELECT dq.queue_number, dq.service_id, s.Name AS service_name, dq.priority, dq.status, dq.time_created
                FROM document_queue dq
                JOIN service s ON dq.service_id = s.service_id
                WHERE dq.queue_number = :queue_number";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['queue_number' => $queue_number]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Update Document Service Queue</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
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
            25% { background-position: 50% 50%; }
            50% { background-position: 25% 75%; }
            100% { background-position: 0 100%; }
        }

        .navbar {
            background-color: #fff;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar h1 {
            color: #363735; 
            font-size: 40px;
            margin: 0;
        }

        .container {
            margin: 70px auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            max-width: 900px;
        }

        h1 {
            color: #363735;
            margin-bottom: 20px;
            text-align: center;
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

        .action-button, .delete-button {
            background-color: #5cd2c6;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 3px;
            transition: background-color 0.3s;
            margin-left: 10px;
        }

        .action-button:hover, .delete-button:hover {
            background-color: rgb(51, 132, 124);
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Update Document Service Queue</h1>

    <table>
        <thead>
            <tr>
                <th>Queue Number</th>
                <th>Service Type</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Time Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo htmlspecialchars($result['queue_number']); ?></td>
                <td><?php echo htmlspecialchars($result['service_name']); ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="queue_number" value="<?php echo htmlspecialchars($result['queue_number']); ?>">
                        <select name="priority" class="form-control">
                            <option value="Non-VIP" <?php echo $result['priority'] === 'Non-VIP' ? 'selected' : ''; ?>>Non-VIP</option>
                            <option value="VIP" <?php echo $result['priority'] === 'VIP' ? 'selected' : ''; ?>>VIP</option>
                        </select>
                </td>
                <td>
                    <select name="status" class="form-control">
                        <option value="Waiting" <?php echo $result['status'] === 'Waiting' ? 'selected' : ''; ?>>Waiting</option>
                        <option value="Serving" <?php echo $result['status'] === 'Serving' ? 'selected' : ''; ?>>Serving</option>
                        <option value="Completed" <?php echo $result['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </td>
                <td><?php echo htmlspecialchars($result['time_created']); ?></td>
                <td>
                    <button type="submit" class="action-button">Update</button>
                    </form>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="delete_queue_number" value="<?php echo htmlspecialchars($result['queue_number']); ?>">
                        <button type="submit" class="delete-button">Delete</button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
</div>

</body>
</html>
