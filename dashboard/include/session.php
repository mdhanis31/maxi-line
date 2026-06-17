<?php
// Mulai session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['id_tb_user']) || empty($_SESSION['id_tb_user'])) {
    // Redirect ke halaman login
    echo "<script>location.href='login.php';</script>";
    exit();
}