<?php
require_once __DIR__ . '/db.php';

sr_admin_logout();
header('Location: login.php');
exit;

