<?php
// Adiciona uma meta box para os dados da tabela
function dw_add_table_meta_box() {
    add_meta_box('dw_table_data', 'Dados da Tabela', 'dw_render_table_meta_box', 'dw_table', 'normal', 'default');
}
add_action('add_meta_boxes', 'dw_add_table_meta_box');

function dw_render_table_meta_box($post) {
    $table_data = get_post_meta($post->ID, '_dw_table_data', true);
    ?>
    <textarea name="dw_table_data" style="width:100%;height:200px;"><?php echo esc_textarea($table_data); ?></textarea>
    <p>Insira os dados da tabela em formato CSV (linha por linha, separado por vírgula)</p>
    <?php
}

function dw_save_table_meta_box($post_id) {
    if (isset($_POST['dw_table_data'])) {
        update_post_meta($post_id, '_dw_table_data', sanitize_textarea_field($_POST['dw_table_data']));
    }
}
add_action('save_post', 'dw_save_table_meta_box');
?>
