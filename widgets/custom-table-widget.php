<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Elementor_Custom_Table_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'custom_table';
    }

    public function get_title() {
        return __('Custom Table', 'dw-tables');
    }

    public function get_icon() {
        return 'eicon-table';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {
        // Seção de conteúdo
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Table Content', 'dw-tables'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Controle para o ano
        $this->add_control(
            'table_year',
            [
                'label' => __('Year', 'dw-tables'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '2025',
            ]
        );

        // Controle para os meses (repeater para adicionar múltiplas linhas)
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'month_name',
            [
                'label' => __('Month', 'dw-tables'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'variation',
            [
                'label' => __('Variation', 'dw-tables'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'table_rows',
            [
                'label' => __('Table Rows', 'dw-tables'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'month_name' => 'Janeiro',
                        'variation' => '0,27%',
                    ],
                    [
                        'month_name' => 'Fevereiro',
                        'variation' => '1,06%',
                    ],
                    [
                        'month_name' => 'Março',
                        'variation' => '---',
                    ],
                ],
                'title_field' => '{{{ month_name }}}            ]
        );

        // Controle para o acumulado
        $this->add_control(
            'accumulated',
            [
                'label' => __('Accumulated', 'dw-tables'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '1,33%',
            ]
        );

        // Controle para a fonte
        $this->add_control(
            'source',
            [
                'label' => __('Source', 'dw-tables'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Fonte FCV',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="custom-table">
            <div class="table-header">
                <div class="header-year"><?php echo esc_html($settings['table_year']); ?></div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th><?php _e('Mês', 'dw-tables'); ?></th>
                        <th><?php _e('Variação', 'dw-tables'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($settings['table_rows'] as $row) : ?>
                        <tr>
                            <td><?php echo esc_html($row['month_name']); ?></td>
                            <td><?php echo esc_html($row['variation']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="acumulado">
                        <td><?php _e('Acumulado do ano', 'dw-tables'); ?></td>
                        <td><?php echo esc_html($settings['accumulated']); ?></td>
                    </tr>
                    <tr class="fonte">
                        <td colspan="2"><?php echo esc_html($settings['source']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }
}