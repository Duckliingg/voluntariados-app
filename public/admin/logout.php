<?php
// public/admin/logout.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/Controllers/AuthController.php';

$controller = new AuthController();
$controller->logoutAdmin();
