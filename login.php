<?php 
session_start();
include 'db.php';

$services = [];
$stmt = $pdo->query("SELECT * FROM service");
$services = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $inputRole = $_POST['loginAs'];

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE Email = :email");
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['Password'])) {
        $stmtService = $pdo->prepare("SELECT Name FROM service WHERE service_id = :service_id");
        $stmtService->execute(['service_id' => $admin['service_id']]);
        $service = $stmtService->fetch();

        if ($service && $service['Name'] === $inputRole) {
            $_SESSION['admin_email'] = $admin['Email'];
            $_SESSION['admin_role'] = $service['Name'];
            $_SESSION['admin'] = $admin;

            if ($service['Name'] === 'Teller') {
                header("Location: teller_queue.php");
            } elseif ($service['Name'] === 'Customer Service') {
                header("Location: cs_queue.php"); 
            } elseif ($service['Name'] === 'Mobile Banking') {
                header("Location: mobile_queue.php");
            } elseif ($service['Name'] === 'Document Service') {
                header("Location: document_queue.php");
            } elseif ($service['Name'] === 'Friendly Assistance') {
                header("Location: assistance_queue.php");
            } elseif ($service['Name'] === 'Account Safety') {
                header("Location: account_queue.php");
            } elseif ($service['Name'] === 'Admin') {
                header("Location: admin.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Incorrect role selected.";
        }
    } else {
        $_SESSION['error'] = "Invalid email or password.";
    }

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
      25% { background-position: 75% 25%;}
      50% { background-position: 50% 50%;}
      75% { background-position: 25% 75%; }
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

        .container {
            margin: 70px auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            max-width: 500px;
        }

        h1.bank-title {
            color: #363735;
            margin: 0 20px;
            text-align: center;
        }

        h1.user-login {
            color:rgb(0, 0, 0); 
            margin: 10px;
            text-align: center;
        }

        label {
            font-weight: bold;
            color: #343a40;
            display: inline-block; 
            width: 100%; 
            margin-bottom: 10px; 
        }

        .form-control {
            border-radius: 30px;
            border: 1px solid #ced4da;
            transition: border-color 0.2s ease;
            margin-bottom: 15px; 
        }


        .btn {
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

        .btn:hover {
            background-color:rgb(53, 124, 117);
        }
    </style>
</head>
<body>

<div class="navbar">
  <h1>BANK INDEPENDENT</h1>
  <p>Jl. Bambu Kuning Selatan, Sepanjang Jaya, Rawalumbu, Kota Bks, Jawa Barat</p>
</div>

<div>
    <form method="POST">
        <div class="container">
            <h1 class="bank-title">BANK INDEPENDENT</h1>
            <h1 class="user-login">User Login</h1>
            <hr class="mb-3">
            
            <label for="email"><b>Email</b></label>
            <input class="form-control" id="email" type="email" name="email" required>

            <label for="password"><b>Password</b></label>
            <input class="form-control" id="password" type="password" name="password" required>

            <label for="role"><b>Login As:</b></label>
            <select class="form-control" id="role" name="loginAs" required>
                <option value="" disabled selected>Select your role</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= htmlspecialchars($service['Name']) ?>">
                        <?= htmlspecialchars($service['Name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <hr class="mb-3">
            <input class="btn" type="submit" id="login" name="login" value="LOGIN">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </form>
</div>

<?php 
if (isset($_SESSION['error'])) {
    echo "<script>Swal.fire('Error!', '" . $_SESSION['error'] . "', 'error');</script>";
    unset($_SESSION['error']); 
}
?>

</body>
</html>
