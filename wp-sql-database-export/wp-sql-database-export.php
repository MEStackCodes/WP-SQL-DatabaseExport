<?php
/**
 * Plugin Name: WP SQL Database Export
 * Description: Export the WordPress database to a SQL file with one click.
 * Tags: WordPress, Maintenance, Database Export
 * Version: 1.0.0
 * Requires at least: 5.7
 * Requires PHP: 7.4
 * Author: MEStackCodes
 * License: MIT
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/mysql-export.php';
require __DIR__ . '/includes/functions.php';

// Render the plugin options page
// =============================================================
function wp_sql_export_admin_page()
{

    wp_sql_export_show_view('admin-page');

    if (isset($_POST['export_database'])) {

        if (!isset($_POST['wp_sql_export_nonce']) || !wp_verify_nonce($_POST['wp_sql_export_nonce'], 'wp_sql_export_action')) {
            wp_die('Security check failed.');
        }

        wp_sql_export_database();
    }

}

// Add the admin menu
// =============================================================
add_action('admin_menu', function () {
    add_management_page(
        WP_SQL_EXPORT_NAME,
        WP_SQL_EXPORT_NAME,
        'manage_options',
        WP_SQL_EXPORT_SLUG,
        'wp_sql_export_admin_page'
    );
});

// On Login Admin
// =============================================================
add_action('wp_login', function () {
    wp_sql_export_remove_backups();
});

// On Logout Admin
// =============================================================
add_action('wp_logout', function () {
    wp_sql_export_remove_backups();
});
