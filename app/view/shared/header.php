<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventure</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            padding-top: 60px;
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php?page=events">Eventure</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=events">Events</a>
                    </li>
                    <?php if (!isset($_SESSION['user'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=register">Register</a>
                        </li>
                    <?php else: ?>
                        <?php if (isset($_SESSION['user']['role'])): ?>
                            <?php if ($_SESSION['user']['role'] === 'superadmin'): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?page=superadmin&action=dashboard">SuperAdmin Dashboard</a>
                                </li>
                            <?php endif; ?>
                            <?php if ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'superadmin'): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?page=admin&action=dashboard">Admin Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?page=event&action=create_event">Create Event</a>
                                </li>
                            <?php endif; ?>
                            <?php if ($_SESSION['user']['role'] === 'user'): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php?page=request_admin">Request Admin Access</a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=profile">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=logout">Logout</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
