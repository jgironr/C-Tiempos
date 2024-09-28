<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Temporizador y Cronómetro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">

</head>

<body>
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div>
            <a class="navbar-brand nav-link bg-dark rounded" id="homeButton" href="<?= site_url('timersController') ?>">
                <i class="fas fa-home"></i>
            </a>
            <a class="navbar-brand bg-dark nav-link text-light rounded px-3 py-2" id="viewWorkspacesButton"
                href="<?= site_url('TimersController/timer')?>">
                <i class="fas fa-clock mr-1"></i> Cronómetros
            </a>
        </div>
    </nav>

    <nav class="navbar navbar-dark bg-dark fixed-top">
    <div>
        <a class="navbar-brand nav-link bg-dark rounded" id="homeButton" href="<?= site_url('timersController') ?>">
            <i class="fas fa-home"></i>
        </a>
        <a class="navbar-brand bg-dark nav-link text-light rounded px-3 py-2" id="viewWorkspacesButton"
            href="<?= site_url('TimersController/timer')?>">
            <i class="fas fa-clock mr-1"></i> Cronómetros
        </a>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']->role == 'admin'): ?>
            <a class="navbar-brand bg-dark nav-link text-light rounded px-3 py-2" href="<?= site_url('UserController/index') ?>">
                <i class="fas fa-users"></i> Gestión de Usuarios
            </a>
        <?php endif; ?>
    </div>
</nav>

<nav class="navbar navbar-dark bg-dark fixed-top">
    <div>
        <a class="navbar-brand nav-link bg-dark rounded" id="homeButton" href="<?= site_url('timersController') ?>">
            <i class="fas fa-home"></i>
        </a>
        <a class="navbar-brand bg-dark nav-link text-light rounded px-3 py-2" id="viewWorkspacesButton"
            href="<?= site_url('TimersController/timer')?>">
            <i class="fas fa-clock mr-1"></i> Cronómetros
        </a>
        <?php if (isset($_SESSION['user'])): ?>
            <?php if ($_SESSION['user']->role == 'admin'): ?>
                <a class="navbar-brand bg-dark nav-link text-light rounded px-3 py-2" href="<?= site_url('UserController/index') ?>">
                    <i class="fas fa-users"></i> Gestión de Usuarios
                </a>
            <?php endif; ?>
            <a class="navbar-brand bg-dark nav-link text-light rounded px-3 py-2" href="<?= site_url('UserController/logout') ?>">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        <?php else: ?>
            <a class="navbar-brand bg-dark nav-link text-light rounded px-3 py-2" href="<?= site_url('UserController/login') ?>">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </a>
        <?php endif; ?>
    </div>
</nav>


</body>    