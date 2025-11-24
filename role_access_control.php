<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$adminRole = $_SESSION['admin_role'] ?? null;

$allowedPages = [
    'Teller' => ['teller_queue.php'],
    'Customer Service' => ['cs_queue.php'],
    'Mobile Banking' => ['mobile_queue.php'],
    'Document Service' => ['document_queue.php'],
    'Friendly Assistance' => ['assistance_queue.php'],
    'Account Safety' => ['account_queue.php']
];

$currentPage = basename($_SERVER['PHP_SELF']);

if ($adminRole !== 'Admin') {
    if (!isset($allowedPages[$adminRole]) || !in_array($currentPage, $allowedPages[$adminRole])) {
        $redirectPage = $allowedPages[$adminRole][0] ?? 'admin.php';
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: 'You are not authorized to access this page.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#8b0000'
                }).then(function() {
                    window.location.href = '$redirectPage';
                });
            });
        </script>";
        exit;
    }
}
?>