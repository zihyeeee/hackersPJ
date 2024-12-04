<?php

checkAdminPageAuth();

$tab = empty($_GET['tab']) ? 'tab1' : $_GET['tab'];

$adminMenu = [
    'tab1' => ['title' => '투표설정', 'path' => '/admin/tab1.php'],
    'tab2' => ['title' => '투표내역', 'path' => '/admin/tab2.php'],
    'tab3' => ['title' => '기타', 'path' => '/admin/tab3.php'],
];

$includePath = PAGES_PATH . $adminMenu[$tab]['path'];
?>

<script type="text/javascript" src="<?=$config['js_url']?>/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?=$config['js_url']?>/hackers2024.js"></script>
<link rel="stylesheet" href="<?=$config['css_url']?>/admin.css">

<div class="admin_wrap">
    <div class="admin_menu">
        <?php foreach($adminMenu as $key => $value) { 
            if($key == 'tab3') continue;
            $active = $tab == $key ? 'active' : ''; ?>
            <div class="admin_menu_item <?=$active?>">
                <a href="/admin/?tab=<?=$key?>"><?=$value['title']?></a>
            </div>
        <?php } ?>
    </div>

    <?php
        if(!empty($includePath) && file_exists($includePath)) {
            include $includePath; 
        }
    ?>
</div>