<?php
include '../src/bootstrap.php';

$page = empty($_GET['page']) ? 'home' : $_GET['page'];

if(!in_array($page, $config['allowed_pages'])) {
    header('Location: /');
    exit;
}

include PAGES_PATH . "/{$page}.php";