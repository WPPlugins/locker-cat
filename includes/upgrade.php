<?php

function fca_slc_upgrade_menu() {
	$page_hook = add_submenu_page(
		'edit.php?post_type=contest',
		__('Upgrade to Premium', 'locker-cat'),
		__('Upgrade to Premium', 'locker-cat'),
		'manage_options',
		'locker-cat-upgrade',
		'fca_slc_upgrade_ob_start'
	);
	add_action('load-' . $page_hook , 'fca_slc_upgrade_page');
}
add_action( 'admin_menu', 'fca_slc_upgrade_menu' );

function fca_slc_upgrade_ob_start() {
    ob_start();
}

function fca_slc_upgrade_page() {
    wp_redirect('https://fatcatapps.com/contestcat/upgrade?utm_medium=plugin&utm_source=contest%20Cat%20Free&utm_campaign=free-plugin', 301);
    exit();
}

function fca_slc_upgrade_to_premium_menu_js() {
    ?>
    <script type="text/javascript">
    	jQuery(document).ready(function ($) {
            $('a[href="edit.php?post_type=contest&page=locker-cat-upgrade"]').on('click', function () {
        		$(this).attr('target', '_blank')
            })
        })
    </script>
    <style>
        a[href="edit.php?post_type=contest&page=locker-cat-upgrade"] {
            color: #6bbc5b !important;
        }
        a[href="edit.php?post_type=contest&page=locker-cat-upgrade"]:hover {
            color: #7ad368 !important;
        }
    </style>
    <?php 
}
add_action( 'admin_footer', 'fca_slc_upgrade_to_premium_menu_js');
