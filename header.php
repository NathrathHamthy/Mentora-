<?php
require_once __DIR__ . '/auth.php';

// Determine if we're in admin pages
$isAdminPage = strpos($_SERVER['PHP_SELF'], '/admin/') !== false;

// Set correct CSS path based on location
$cssPath = $isAdminPage ? '../assets/css/style.css' : 'assets/css/style.css';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Education Portal'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo $cssPath; ?>" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?php echo $isAdminPage ? '../index.php' : 'index.php'; ?>">
                <i class="fas fa-brain interactive-icon"></i> MENTORA
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $isAdminPage ? '../dashboard.php' : 'dashboard.php'; ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $isAdminPage ? '../departments.php' : 'departments.php'; ?>">Departments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $isAdminPage ? '../upload.php' : 'upload.php'; ?>">Upload</a>
                        </li>
                        <?php if (isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $isAdminPage ? 'index.php' : 'admin/index.php'; ?>">Admin Panel</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i>
                                <?php echo htmlspecialchars($_SESSION['full_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo $isAdminPage ? '../profile.php' : 'profile.php'; ?>">
                                    <i class="fas fa-user me-2"></i>Profile
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo $isAdminPage ? '../dashboard.php' : 'dashboard.php'; ?>">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a></li>
                                <?php if (isAdmin()): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?php echo $isAdminPage ? 'index.php' : 'admin/index.php'; ?>">
                                        <i class="fas fa-cogs me-2"></i>Admin Panel
                                    </a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item logout-btn" href="<?php echo $isAdminPage ? '../logout.php' : 'logout.php'; ?>" onclick="return confirm('Are you sure you want to logout?')">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a></li>
                            </ul>
                        </li>
                        <!-- Visible logout button for desktop -->
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link" href="<?php echo $isAdminPage ? '../logout.php' : 'logout.php'; ?>" onclick="return confirm('Are you sure you want to logout?')">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </a>
                        </li>
                        <!-- Alternative visible logout button for mobile -->
                        <li class="nav-item d-lg-none">
                            <a class="nav-link" href="<?php echo $isAdminPage ? '../logout.php' : 'logout.php'; ?>" onclick="return confirm('Are you sure you want to logout?')">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $isAdminPage ? '../login.php' : 'login.php'; ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $isAdminPage ? '../register.php' : 'register.php'; ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">