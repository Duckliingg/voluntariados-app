<?php
// public/admin/index.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/Controllers/DashboardController.php';

$controller = new DashboardController();
$controller->dashboardAdmin();
