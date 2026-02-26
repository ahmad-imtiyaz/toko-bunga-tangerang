<?php
require_once(__DIR__ . '/../../includes/config.php');

session_start();

function isLoggedIn(): bool {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/admin/');
        exit;
    }
}

function login(string $username, string $password): bool {
    $stmt = db()->prepare("SELECT * FROM admin WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_user'] = $admin['username'];
        return true;
    }
    return false;
}

function logout(): void {
    session_destroy();
    header('Location: ' . BASE_URL . '/admin/');
    exit;
}

function adminUser(): string {
    return $_SESSION['admin_user'] ?? 'Admin';
}
