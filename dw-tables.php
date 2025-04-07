<?php

/**
 * Plugin Name: DW Engenharia Tables
 * Description: Crie tabelas de Indicadores Financeiros.
 * Version: 1.0
 * Author: G2 Digital
 */

if (! defined('ABSPATH')) exit;

define('DW_TABLES_PATH', plugin_dir_path(__FILE__));
define('DW_TABLES_URL', plugin_dir_url(__FILE__));

require_once DW_TABLES_PATH . 'includes/admin-page.php';
require_once DW_TABLES_PATH . 'includes/shortcode.php';

function dw_register_table_post_type()
{
    register_post_type('dw_table', [
        'label' => 'Indicadores',
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-editor-table',
        'supports' => ['title'],
    ]);
}
add_action('init', 'dw_register_table_post_type');

function dw_enqueue_plugin_styles()
{
    wp_enqueue_style(
        'dw-table-style',
        plugin_dir_url(__FILE__) . 'dw-table-style.css',
        [],
        '1.0'
    );
}
add_action('wp_enqueue_scripts', 'dw_enqueue_plugin_styles');
