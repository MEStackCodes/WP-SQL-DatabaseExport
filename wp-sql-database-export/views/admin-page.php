<div class="wrap">
    <h1><?php echo esc_html__(WP_SQL_EXPORT_NAME, 'wpsqle-main-title'); ?></h1>
    <form method="post" action="">
        <?php wp_nonce_field('wp_sql_export_action', 'wp_sql_export_nonce'); ?>
        <table class="form-table">
            <tr></tr>
        </table>
        <p class="submit">
            <button type="submit" name="export_database" class="button button-primary">
                <?php echo esc_html__(WP_SQL_EXPORT_OPTION_NAME, 'wpsqle-export-button'); ?>
            </button>
        </p>
    </form>
</div>