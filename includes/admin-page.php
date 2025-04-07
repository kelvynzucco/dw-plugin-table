<?php
function dw_add_table_meta_box()
{
    add_meta_box('dw_table_data', 'Configuração da Tabela', 'dw_render_table_meta_box', 'dw_table', 'normal', 'default');
}
add_action('add_meta_boxes', 'dw_add_table_meta_box');

function dw_admin_enqueue_scripts($hook)
{
    if ($hook === 'post.php' || $hook === 'post-new.php') {
        wp_enqueue_script('jquery-ui-sortable');
    }
}
add_action('admin_enqueue_scripts', 'dw_admin_enqueue_scripts');

function dw_render_table_meta_box($post)
{
    $title = get_post_meta($post->ID, '_dw_table_title', true);
    $year = get_post_meta($post->ID, '_dw_table_year', true);
    $footer = get_post_meta($post->ID, '_dw_table_footer', true);
    $rows = get_post_meta($post->ID, '_dw_table_rows', true);

    if (!is_array($rows)) {
        $rows = [];
    }
?>
    <p><strong>Título (esquerda):</strong></p>
    <input type="text" name="dw_table_title" value="<?php echo esc_attr($title); ?>" style="width: 100%;" />

    <p><strong>Ano (direita):</strong></p>
    <input type="text" name="dw_table_year" value="<?php echo esc_attr($year); ?>" style="width: 100%;" />

    <p><strong>Dados da Tabela:</strong></p>
    <table id="dw-table-rows" style="width: 100%; border-spacing: 10px;">
        <thead>
            <tr>
                <th>Mês</th>
                <th>Valor Médio</th>
                <th>Variação</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><input type="text" name="dw_table_rows[<?php echo $index; ?>][mes]" value="<?php echo esc_attr($row['mes']); ?>" /></td>
                    <td><input type="text" name="dw_table_rows[<?php echo $index; ?>][valor]" value="<?php echo esc_attr($row['valor']); ?>" /></td>
                    <td><input type="text" name="dw_table_rows[<?php echo $index; ?>][variacao]" value="<?php echo esc_attr($row['variacao']); ?>" /></td>
                    <td><button type="button" class="dw-remove-row" style="background:none;border:none;font-size:18px;color:red;cursor:pointer;">–</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><button type="button" onclick="dwAddRow()">+ Adicionar linha</button></p>

    <p><strong>Acumulado do ano:</strong></p>
    <input type="text" name="dw_table_footer" value="<?php echo esc_attr($footer); ?>" style="width: 100%;" />

    <script>
        function dwAddRow() {
            const table = document.querySelector('#dw-table-rows tbody');
            const rowCount = table.rows.length;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" name="dw_table_rows[${rowCount}][mes]" /></td>
                <td><input type="text" name="dw_table_rows[${rowCount}][valor]" /></td>
                <td><input type="text" name="dw_table_rows[${rowCount}][variacao]" /></td>
                <td><button type="button" class="dw-remove-row" style="background:none;border:none;font-size:18px;color:red;cursor:pointer;">–</button></td>
            `;
            table.appendChild(row);
            updateRowIndexes();
        }

        function updateRowIndexes() {
            const rows = document.querySelectorAll('#dw-table-rows tbody tr');
            rows.forEach((row, index) => {
                const inputs = row.querySelectorAll('input');
                if (inputs.length >= 3) {
                    inputs[0].name = `dw_table_rows[${index}][mes]`;
                    inputs[1].name = `dw_table_rows[${index}][valor]`;
                    inputs[2].name = `dw_table_rows[${index}][variacao]`;
                }
            });
        }

        jQuery(function($) {
            $('#dw-table-rows tbody').sortable({
                helper: fixHelper,
                stop: updateRowIndexes
            });

            function fixHelper(e, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            }

            $(document).on('click', '.dw-remove-row', function() {
                $(this).closest('tr').remove();
                updateRowIndexes();
            });
        });
    </script>
<?php
}

function dw_save_table_meta_box($post_id)
{
    update_post_meta($post_id, '_dw_table_title', sanitize_text_field($_POST['dw_table_title'] ?? ''));
    update_post_meta($post_id, '_dw_table_year', sanitize_text_field($_POST['dw_table_year'] ?? ''));
    update_post_meta($post_id, '_dw_table_footer', sanitize_text_field($_POST['dw_table_footer'] ?? ''));

    $rows = $_POST['dw_table_rows'] ?? [];
    $clean_rows = [];

    foreach ($rows as $row) {
        if (!empty($row['mes']) || !empty($row['valor']) || !empty($row['variacao'])) {
            $clean_rows[] = [
                'mes' => sanitize_text_field($row['mes']),
                'valor' => sanitize_text_field($row['valor']),
                'variacao' => sanitize_text_field($row['variacao']),
            ];
        }
    }

    update_post_meta($post_id, '_dw_table_rows', $clean_rows);
}
add_action('save_post', 'dw_save_table_meta_box');
?>