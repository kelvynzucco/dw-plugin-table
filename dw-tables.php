<?php
/**
 * Plugin Name: DW Tables
 * Description: Crie tabelas personalizadas e insira-as no site via shortcode.
 * Version: 1.0
 * Author: Seu Nome
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define('DW_TABLES_PATH', plugin_dir_path(__FILE__));
define('DW_TABLES_URL', plugin_dir_url(__FILE__));

require_once DW_TABLES_PATH . 'includes/admin-page.php';
require_once DW_TABLES_PATH . 'includes/shortcode.php';

// Registro do Custom Post Type
function dw_register_table_post_type() {
    register_post_type('dw_table', [
        'label' => 'Tabelas',
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-editor-table',
        'supports' => ['title'],
    ]);
}
add_action('init', 'dw_register_table_post_type');
?>
