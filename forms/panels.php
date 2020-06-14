<?php

$current = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'attachments';

$tabs = array(
    'attachments' => '<span class="dashicons dashicons-admin-settings" title="Settings"></span><span class="tab-title"> Settings</span>',
    'images' => '<span class="dashicons dashicons-images-alt" title="Images"></span><span class="tab-title"> Images</span>',
    'advanced' => '<span class="dashicons dashicons-admin-tools" title="Advanced"></span><span class="tab-title"> Advanced</span>'
);

if (!isset($tabs[$current])) {
    $current = 'attachments';
}

?>
<div class="wrap">
    <h2>bbPress Attachments</h2>
    <div id="icon-upload" class="icon32"><br></div>
    <h2 class="nav-tab-wrapper d4p-tabber-ctrl">
        <?php

        foreach ($tabs as $tab => $name) {
            $class = ($tab == $current) ? ' nav-tab-active' : '';
            echo '<a class="nav-tab'.$class.'" href="edit.php?post_type=forum&page=gdbbpress_attachments&tab='.$tab.'">'.$name.'</a>';
        }

        ?>
    </h2>
    <div id="d4p-panel" class="d4p-panel-<?php echo $current; ?>">
        <?php include(BBPATTACHMENTS_PATH."forms/tabs/".$current.".php"); ?>
    </div>
</div>
