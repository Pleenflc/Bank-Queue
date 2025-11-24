<?php
session_start();
include 'db.php';

// Ambil data service untuk dropdown
$services = [];
$stmt = $pdo->query("SELECT * FROM service");
$services = $stmt->fetchAll();

// Proses pendaftaran pengguna
if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['loginAs']; // nama role dari dropdown (sesuai kolom Name di tabel service)

    // Cek apakah email sudah ada di database
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE Email = :email");
    $stmt->execute(['email' => $email]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        $_SESSION['error'] = "Email already registered.";
    } else {
        // Cek apakah role valid berdasarkan tabel service
        $stmtRole = $pdo->prepare("SELECT service_id FROM service WHERE Name = :role");
        $stmtRole->execute(['role' => $role]);
        $service = $stmtRole->fetch();

        if (!$service) {
            $_SESSION['error'] = "Invalid role selected.";
        } else {
            $service_id = $service['service_id'];
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO admin (Email, Password, service_id) VALUES (:email, :password, :service_id)");
            if ($stmt->execute([
                'email' => $email,
                'password' => $hashedPassword,
                'service_id' => $service_id
            ])) {
                $_SESSION['success'] = "User registered successfully! You can log in now.";
                // Jangan redirect dengan header() agar alert bisa muncul dulu
            } else {
                $_SESSION['error'] = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Registration</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;700&display=swap" rel="stylesheet" />
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
            0% {
                background-position: 100% 0;
            }
            25% {
                background-position: 75% 25%;
            }
            50% {
                background-position: 50% 50%;
            }
            75% {
                background-position: 25% 75%;
            }
            100% {
                background-position: 0 100%;
            }
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
            color: rgb(0, 0, 0);
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
            background-color: rgb(53, 124, 117);
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
            <div class="container" data-aos="fade-up">
                <h1 class="bank-title">BANK INDEPENDENT</h1>
                <h1 class="user-login">User Registration</h1>
                <hr class="mb-3" />

                <label for="registerEmail"><b>Email</b></label>
                <input class="form-control" id="registerEmail" type="email" name="email" required />

                <label for="registerPassword"><b>Password</b></label>
                <input class="form-control" id="registerPassword" type="password" name="password" required />

                <label for="registerRole"><b>Role</b></label>
                <select class="form-control" id="registerRole" name="loginAs" required>
                    <option value="" disabled selected>Select your role</option>
                    <?php foreach ($services as $service): ?>
                    <option value="<?= htmlspecialchars($service['Name']) ?>">
                        <?= htmlspecialchars($service['Name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <hr class="mb-3" />
                <input class="btn" type="submit" id="register" name="register" value="REGISTER" />
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </form>
    </div>

    <?php 
    if (isset($_SESSION['error'])) {
        echo "<script>Swal.fire('Error!', '" . $_SESSION['error'] . "', 'error');</script>";
        unset($_SESSION['error']); 
    }
    if (isset($_SESSION['success'])) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '" . $_SESSION['success'] . "'
            }).then(() => {
                window.location.href = 'login.php';
            });
        </script>";
        unset($_SESSION['success']); 
    }
    ?>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
