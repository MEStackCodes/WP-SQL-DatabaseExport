<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Export the database
// =============================================================
function wp_sql_export_database()
{

    if (!current_user_can('manage_options')) {
        wp_die('You do not have permission to perform this action. You do not have permission to perform this action.');
    }

    $upload_dir = wp_upload_dir();
    $folder_path = $upload_dir['basedir'] . '/' . WP_SQL_EXPORT_SLUG;

    if (!is_dir($folder_path)) {

        if (!mkdir($folder_path, 0777)) {
            wp_die('Error creating backup folder. Please check directory permision!.');
        }

        file_put_contents($folder_path . '/index.php', '');
    }

    $databaseDump = new MySQLExport(new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME));
    $databaseDump->pluginName = WP_SQL_EXPORT_NAME;
    $backupFileName = date('Y-m-d_H-i') . WP_SQL_EXPORT_BACKUP_EXT;
    $backupFileNamePath = $folder_path . '/' . $backupFileName;
    $backupFileNameURL = $upload_dir['baseurl'] . '/' . WP_SQL_EXPORT_SLUG . '/' . $backupFileName;

    if (is_ssl()) {
        $backupFileNameURL = str_replace('http://', 'https://', $backupFileNameURL);
    }

    try {
        $databaseDump->save($backupFileNamePath);
    } catch (\Throwable $th) {
        wp_die('Database export failed. Please check your server configuration.');
    }

    $downloadLink = '<a href="' . $backupFileNameURL . '">Download the backup</a>';
    wp_admin_notice('Database was exported sucessfully!. ' . $downloadLink, ['type' => 'success']);
}

// Remove Backup Files or Total Backup Directory
// =============================================================
function wp_sql_export_remove_backups($removeDirectory = false)
{
    $upload_dir = wp_upload_dir();
    $folder_path = $upload_dir['basedir'] . '/' . WP_SQL_EXPORT_SLUG;
    $files_ext = ($removeDirectory) ? '/*.*' : '/*' . WP_SQL_EXPORT_BACKUP_EXT;

    $files = glob($folder_path . $files_ext);

    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }

    if ($removeDirectory && (count(glob($folder_path . $files_ext)) == 0)) {
        rmdir($folder_path);
    }

}

// Show HTML View
// =============================================================
function wp_sql_export_show_view($view)
{
    $file = WP_SQL_EXPORT_PLUGIN_DIR . 'views/' . basename($view) . '.php';

    if (file_exists($file)) {
        include $file;
    }
}
