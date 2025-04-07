<?php
function dw_render_table_shortcode($atts)
{
    $atts = shortcode_atts([
        'id' => null
    ], $atts);

    if (!$atts['id']) return 'ID da tabela não especificado.';

    $post = get_post($atts['id']);
    if (!$post || $post->post_type !== 'dw_table') return 'Tabela não encontrada.';

    $title = get_the_title($atts['id']);
    $year = get_post_meta($post->ID, '_dw_table_year', true);
    $rows = get_post_meta($post->ID, '_dw_table_rows', true);
    $footer = get_post_meta($post->ID, '_dw_table_footer', true);
    $source = get_post_meta($post->ID, '_dw_table_source', true);

    if (!is_array($rows) || empty($rows)) return 'Nenhum dado disponível.';

    $has_valor = false;
    foreach ($rows as $row) {
        if (!empty($row['valor'])) {
            $has_valor = true;
            break;
        }
    }

    ob_start();
?>
    <div class="dw-table-wrapper">
        <div class="dw-table-header">
            <span><?php echo esc_html($title); ?></span>
            <span><?php echo esc_html($year); ?></span>
        </div>
        <table>
            <thead>
                <tr class="dw-header-row">
                    <th>Mês</th>
                    <?php if ($has_valor): ?>
                        <th>Médio</th>
                    <?php endif; ?>
                    <th>Variação</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo esc_html($row['mes']); ?></td>
                        <?php if ($has_valor): ?>
                            <td><?php echo esc_html($row['valor']); ?></td>
                        <?php endif; ?>
                        <td><?php echo esc_html($row['variacao']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <?php if (!empty($footer)): ?>
                <tfoot>
                    <tr>
                        <td class="dw-acumulado-label" colspan="<?php echo $has_valor ? '2' : '1'; ?>">Acumulado do ano</td>
                        <td class="dw-acumulado-total">
                            <?php echo esc_html($footer); ?>
                        </td>
                    </tr>
                </tfoot>
            <?php endif; ?>
        </table>
        <?php if (!empty($source)): ?>
            <p class="dw-source">Fonte <?php echo esc_html($source); ?></p>
        <?php endif; ?>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('dw_table', 'dw_render_table_shortcode');
