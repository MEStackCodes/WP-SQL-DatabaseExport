<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

wp_sql_export_remove_backups(true);
