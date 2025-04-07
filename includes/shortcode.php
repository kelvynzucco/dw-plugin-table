<?php
function dw_render_table_shortcode($atts)
{
    $atts = shortcode_atts([
        'id' => null
    ], $atts);

    if (!$atts['id']) return 'ID da tabela não especificado.';

    $post = get_post($atts['id']);
    if (!$post || $post->post_type !== 'dw_table') return 'Tabela não encontrada.';

    $title = get_post_meta($post->ID, '_dw_table_title', true);
    $year = get_post_meta($post->ID, '_dw_table_year', true);
    $rows = get_post_meta($post->ID, '_dw_table_rows', true);
    $footer = get_post_meta($post->ID, '_dw_table_footer', true);

    if (!is_array($rows) || empty($rows)) return 'Nenhum dado disponível.';

    ob_start();
?>
    <div class="dw-table-wrapper">
        <div style="display:flex;justify-content:space-between;background:#0D2C52;color:#fff;padding:10px;">
            <strong><?php echo esc_html($title); ?></strong>
            <strong><?php echo esc_html($year); ?></strong>
        </div>
        <table border="1" cellpadding="5" cellspacing="0" width="100%" style="border-collapse:collapse;width:100%;">
            <thead>
                <tr>
                    <th>Mês</th>
                    <th>Cub Médio</th>
                    <th>Variação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo esc_html($row['mes']); ?></td>
                        <td><?php echo esc_html($row['valor']); ?></td>
                        <td><?php echo esc_html($row['variacao']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <?php if (!empty($footer)): ?>
                <tfoot>
                    <tr>
                        <td colspan="2"><strong>Acumulado do ano</strong></td>
                        <td><strong><?php echo esc_html($footer); ?></strong></td>
                    </tr>
                </tfoot>
            <?php endif; ?>
        </table>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('dw_table', 'dw_render_table_shortcode');
?>