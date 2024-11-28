<?php
include '../../src/bootstrap.php';

$page = empty($_GET['page']) ? 'home' : $_GET['page'];

if(!in_array($page, $config['admin_allowed_pages'])) {
    header('Location: /');
    exit;
}

include PAGES_PATH . "/admin/{$page}.php";