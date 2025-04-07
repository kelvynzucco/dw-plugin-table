<?php
function dw_render_table_shortcode($atts) {
    $atts = shortcode_atts([
        'id' => null
    ], $atts);

    if (!$atts['id']) return 'ID da tabela não especificado.';

    $post = get_post($atts['id']);
    if (!$post || $post->post_type !== 'dw_table') return 'Tabela não encontrada.';

    $csv_data = get_post_meta($post->ID, '_dw_table_data', true);
    if (!$csv_data) return 'Nenhum dado disponível.';

    $rows = explode("\n", $csv_data);
    ob_start();
    echo '<h3>' . esc_html($post->post_title) . '</h3>';
    echo '<table border="1" cellpadding="5">';
    foreach ($rows as $row) {
        $cols = explode(",", trim($row));
        echo '<tr>';
        foreach ($cols as $col) {
            echo '<td>' . esc_html($col) . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    return ob_get_clean();
}
add_shortcode('dw_table', 'dw_render_table_shortcode');
?>
