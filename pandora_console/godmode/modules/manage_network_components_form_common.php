<?php

// Pandora FMS - https://pandorafms.com
// ==================================================
// Copyright (c) 2005-2023 Pandora FMS
// Please see https://pandorafms.com/community/ for full contribution list
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation for version 2.
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
global $config;
require_once $config['homedir'].'/include/graphs/functions_d3.php';
include_javascript_d3();


if (! check_acl($config['id_user'], 0, 'PM') && ! check_acl($config['id_user'], 0, 'AW')) {
    db_pandora_audit(
        AUDIT_LOG_ACL_VIOLATION,
        'Trying to access Agent Management'
    );
    include 'general/noaccess.php';
    return;
}

$table = new stdClass();
$table->id = 'network_component';
$table->width = '100%';
$table->class = 'databox filters filter-table-adv';
$table->style = [];
$table->style[0] = 'width: 50%';
$table->style[2] = 'width: 50%';
$table->colspan = [];
if (!enterprise_installed()) {
    $table->colspan[0][1] = 3;
}

$table_simple = new stdClass();
$table_simple->colspan[7][1] = 4;
$table_simple->colspan[8][1] = 4;
$table_simple->colspan[9][1] = 4;
$table->data = [];

$table->data[0][0] = html_print_label_input_block(
    __('Name'),
    html_print_input_text('name', $name, '', 55, 255, true)
);
if (enterprise_installed()) {
    if (defined('METACONSOLE')) {
        $table->data[0][2] = __('Wizard level');
        $wizard_levels = [
            'basic'    => __('Basic'),
            'advanced' => __('Advanced'),
        ];
        // TODO review help tips on meta.
        $table->data[0][1] = html_print_label_input_block(
            __('Wizard level'),
            html_print_select($wizard_levels, 'wizard_level', $wizard_level, '', '', -1, true, false, false).' '
        );
        // .ui_print_help_icon('meta_access', true)
    } else {
        $table->data[0][1] = html_print_label_input_block(
            '',
            html_print_input_hidden('wizard_level', $wizard_level, true)
        );
    }
}

$sql = sprintf(
    'SELECT id_tipo, descripcion
	FROM ttipo_modulo
	WHERE categoria IN (%s)
	ORDER BY id_tipo ASC',
    implode(',', $categories)
);
$table->data[1][0] = html_print_label_input_block(
    __('Type').' '.ui_print_help_icon($help_type, true, '', 'images/help_green.png', '', 'module_type_help'),
    html_print_select_from_sql(
        $sql,
        'type',
        $type,
        'javascript: type_change();',
        '',
        '',
        true,
        false,
        false,
        false,
        true,
        false,
        false,
        false,
        0
    )
);

// Store the relation between id and name of the types on a hidden field
$sql = sprintf(
    'SELECT id_tipo, nombre
		FROM ttipo_modulo
		WHERE categoria IN (%s)
		ORDER BY descripcion',
    implode(',', $categories)
);
$type_names = db_get_all_rows_sql($sql);

$type_names_hash = [];
foreach ($type_names as $tn) {
    $type_names_hash[$tn['id_tipo']] = $tn['nombre'];
}

$table->data[1][1] = html_print_label_input_block(
    __('Module group'),
    html_print_input_hidden(
        'type_names',
        base64_encode(json_encode($type_names_hash)),
        true
    ).html_print_select_from_sql(
        'SELECT id_mg, name
        FROM tmodule_group ORDER BY name',
        'id_module_group',
        $id_module_group,
        '',
        '',
        '',
        true,
        false,
        false,
        false,
        true,
        false,
        false,
        false,
        0
    )
);

$table->data[2][0] = html_print_label_input_block(
    __('Group'),
    html_print_select(
        network_components_get_groups(),
        'id_group',
        $id_group,
        '',
        '',
        '',
        true,
        false,
        false
    )
);

$table->data[2][1] = html_print_label_input_block(
    __('Interval'),
    html_print_select_agentmodule_interval('module_interval', $module_interval, '', '', '0', false, true)
);

$dynamic_interval_img = '<a onclick="advanced_option_dynamic()" class="mrgn_lft_5px mrgn_top_6px">'.html_print_image(
    'images/cog.png',
    true,
    [
        'title' => __('Advanced options Dynamic Threshold'),
        'class' => 'invert_filter',
    ]
).'</a>';

$table->data[3][0] = html_print_label_input_block(
    __('Dynamic Interval'),
    '<div class="inline_flex align-center w100p">'.html_print_extended_select_for_time(
        'dynamic_interval',
        $dynamic_interval,
        '',
        'None',
        '0',
        10,
        true,
        'width: 200px;',
        false,
        'w100p'
    ).$dynamic_interval_img.'</div>'
);

$table->data[3][2] = '<span><em>'.__('Dynamic Min.').' </em>';
$table->data[3][2] .= html_print_input_text('dynamic_min', $dynamic_min, '', 10, 255, true);
$table->data[3][2] .= '<br /><em>'.__('Dynamic Max.').'</em>';
$table->data[3][2] .= html_print_input_text('dynamic_max', $dynamic_max, '', 10, 255, true);
$table->data[3][3] = '<span><em>'.__('Dynamic Two Tailed: ').'</em>';
$table->data[3][3] .= html_print_checkbox('dynamic_two_tailed', 1, $dynamic_two_tailed, true);

$table->data[3][1] = html_print_label_input_block(
    '',
    '<div class="inline_flex align-center">'.html_print_label_input_block(
        __('Dynamic Min.').' ',
        html_print_input_text('dynamic_min', $dynamic_min, '', 10, 255, true, false, false, '', 'w100p mrgn_top_10px'),
        [
            'label_class' => 'font-title-font',
            'div_class'   => 'mrgn_right_10px',
        ]
    ).html_print_label_input_block(
        __('Dynamic Max.').' ',
        html_print_input_text('dynamic_max', $dynamic_max, '', 10, 255, true, false, false, '', 'w100p mrgn_top_10px'),
        [
            'label_class' => 'font-title-font',
            'div_class'   => 'mrgn_right_10px',
        ]
    ).html_print_label_input_block(
        __('Dynamic Two Tailed: '),
        html_print_checkbox('dynamic_two_tailed', 1, $dynamic_two_tailed, true, false, '', false, 'class="mrgn_top_10px"'),
        [
            'label_class' => 'font-title-font',
            'div_class'   => 'mrgn_right_10px',
        ]
    ).'</div>',
    ['label_class' => 'mrgn_btn_0']
);

$table->data[4][0] = html_print_label_input_block(
    __('Warning status'),
    '<div class="inline_flex align-center mrgn_top_10px"><div id="minmax_warning" class="inline_flex align-center">'.html_print_label_input_block(
        __('Min.'),
        html_print_input_text(
            'min_warning',
            $min_warning,
            '',
            5,
            15,
            true
        ),
        [
            'label_class' => 'font-title-font',
            'div_class'   => 'mrgn_right_10px flex flex_column',
        ]
    ).html_print_label_input_block(
        __('Max.'),
        html_print_input_text(
            'max_warning',
            $max_warning,
            '',
            5,
            15,
            true
        ).'</div>',
        [
            'label_class' => 'font-title-font',
            'div_class'   => 'mrgn_right_10px flex flex_column',
        ]
    ).'<div id="string_warning" class="inline_flex align-center">'.html_print_label_input_block(
        __('Str.'),
        html_print_input_text(
            'str_warning',
            $str_warning,
            '',
            5,
            1024,
            true
        ).'</div>',
        [
            'label_class' => 'font-title-font',
            'div_class'   => 'mrgn_right_10px flex flex_column',
        ]
    ).'<div id="warning_inverse" class="inline_flex align-center">'.html_print_label_input_block(
        __('Inverse interval'),
        html_print_checkbox('warning_inverse', 1, $warning_inverse, true).'</div>',
        [
            'label_class' => 'font-title-font',
            'div_class'   => 'mrgn_right_10px flex flex_column',
        ]
    ).'<div id="percentage_warning" class="inline_flex align-center">'.html_print_label_input_block(
        __('Percentage').ui_print_help_tip(__('Defines threshold as a percentage of value decrease/increment'), true),
        html_print_checkbox('percentage_warning', 1, $percentage_warning, true).'</div>',
        [
            'label_class' => 'font-title-font',
            'div_class'   => 'mrgn_right_10px flex flex_column',
        ]
    ).'</div></div>',
    ['label_class' => 'mrgn_btn_0']
);

$table->data[4][1] = '<svg id="svg_dinamic" width="500" height="300"> </svg>';
$table->rowspan[4][1] = 3;

$table->data[5][0] = html_print_label_input_block(
    __('Change to critical status after'),
    '<div class="inline_flex align-center w100p">'.html_print_input_text(
        'warning_time',
        $warning_time,
        '',
        5,
        15,
        true
    ).'&nbsp;&nbsp;<b>'.__('intervals in warning status.').'</b>'.'</div>',
    ['div_id' => 'warning_time']
);

$table->data[6][0] = html_print_label_input_block(
    __('Critical status'),
    '<div class="inline_flex align-center mrgn_top_10px"><div id="minmax_critical" class="inline_flex align-center">'.html_print_label_input_block(
        __('Min.'),
        html_print_input_text(
            'min_critical',
            $min_critical,
            '',
            5,
            15,
            true
        ),
        [
            'label_class' => 'font-title-font',
            'div_class'   => 'mrgn_right_10px flex flex_column',
        ]
    ).html_print_label_input_block(
        __('Max.'),
        html_print_input_text(
            'max_critical',
            $max_critical,
            '',
            5,
            15,
            true
        ).'</div>',
        [
            'label_class' => 'font-title-font',
            'div_class'   => 'mrgn_right_10px flex flex_column',
        ]
    ).'<div id="string_critical" class="inline_flex align-center">'.html_print_label_input_block(
        __('Str.'),
        html_print_input_text(
            'str_critical',
            $str_critical,
            '',
            5,
            1024,
            true
        ).'</div>',
        [
            'label_class' => 'font-title-font',
            'div_class'   => 'mrgn_right_10px flex flex_column',
        ]
    ).'<div id="critical_inverse" class="inline_flex align-center">'.html_print_label_input_block(
        __('Inverse interval'),
        html_print_checkbox('critical_inverse', 1, $critical_inverse, true).'</div>',
        [
            'label_class' => 'font-title-font',
            'div_class'   => 'mrgn_right_10px flex flex_column',
        ]
    ).'<div id="percentage_critical" class="inline_flex align-center">'.html_print_label_input_block(
        __('Percentage'),
        html_print_checkbox('percentage_critical', 1, $percentage_warning, true).'</div>',
        [
            'label_class' => 'font-title-font',
            'div_class'   => 'mrgn_right_10px flex flex_column',
        ]
    ).'</div></div>',
    ['label_class' => 'mrgn_btn_0']
);

$table->colspan[7][0] = 2;
$table->data[7][0] = html_print_label_input_block(
    __('FF threshold'),
    '<div class="inline_flex align-center w100p">'.__('Keep counters').'&nbsp;&nbsp;'.html_print_checkbox(
        'ff_type',
        1,
        $ff_type,
        true
    ).'</div><div class="inline_flex align-center w100p">'.html_print_radio_button('each_ff', 0, '', $each_ff, true).' <b>'.__('All state changing').' :&nbsp;&nbsp;</b>'.html_print_input_text(
        'ff_event',
        $ff_event,
        '',
        5,
        15,
        true
    ).'<span class="w30px">&nbsp;</span>'.html_print_radio_button(
        'each_ff',
        1,
        '',
        $each_ff,
        true
    ).' <b>'.__('Each state changing').' :&nbsp;&nbsp;</b>'.__('To normal').html_print_input_text(
        'ff_event_normal',
        $ff_event_normal,
        '',
        5,
        15,
        true
    ).' '.__('To warning').html_print_input_text(
        'ff_event_warning',
        $ff_event_warning,
        '',
        5,
        15,
        true
    ).' '.__('To critical').html_print_input_text(
        'ff_event_critical',
        $ff_event_critical,
        '',
        5,
        15,
        true
    ).'</div>',
    ['div_id' => 'warning_time']
);

$table->data[8][0] = html_print_label_input_block(
    __('Historical data'),
    html_print_checkbox('history_data', 1, $history_data, true)
);

$table->data[9][0] = html_print_label_input_block(
    __('Min. Value'),
    html_print_input_text('min', $min, '', 5, 15, true)
);

$table->data[9][1] = html_print_label_input_block(
    __('Max. Value').' '.ui_print_help_tip(__('Any value over this number is discarted'), true),
    html_print_input_text('max', $max, '', 5, 15, true)
);

$table->data[10][0] = html_print_label_input_block(
    __('Unit'),
    html_print_input_text('unit', $unit, '', 12, 25, true)
);

$table->data[10][1] = html_print_label_input_block(
    __('Discard unknown events'),
    html_print_checkbox(
        'throw_unknown_events',
        1,
        network_components_is_disable_type_event(($id === 0) ? false : $id, EVENTS_GOING_UNKNOWN),
        true
    )
);

$table->data[11][0] = html_print_label_input_block(
    __('Critical instructions').ui_print_help_tip(__('Instructions when the status is critical'), true),
    html_print_textarea('critical_instructions', 2, 65, $critical_instructions, '', true)
);

$table->data[11][1] = html_print_label_input_block(
    __('Warning instructions').ui_print_help_tip(__('Instructions when the status is warning'), true),
    html_print_textarea('warning_instructions', 2, 65, $warning_instructions, '', true)
);

$table->data[12][0] = html_print_label_input_block(
    __('Unknown instructions').ui_print_help_tip(__('Instructions when the status is unknown'), true),
    html_print_textarea('unknown_instructions', 2, 65, $unknown_instructions, '', true)
);

$table->data[12][1] = html_print_label_input_block(
    __('Description'),
    html_print_textarea('description', 2, 65, $description, '', true)
);

$next_row = 13;

if (check_acl($config['id_user'], 0, 'PM')) {
    $table->data[$next_row][0] = html_print_label_input_block(
        __('Category'),
        html_print_select(categories_get_all_categories('forselect'), 'id_category', $id_category, '', __('None'), 0, true)
    );
    $table->data[$next_row][1] = '';
    $next_row++;
} else {
    // Store in a hidden field if is not visible to avoid delete the value
    $table->data[$next_row][0] = html_print_label_input_block(
        '',
        html_print_input_hidden('id_category', $id_category, true)
    );
}

if ($tags == '') {
    $tags_condition_not = '1 = 1';
    $tags_condition_in = '1 = 0';
} else {
    $tags = str_replace(',', "','", $tags);
    $tags_condition_not = "name NOT IN ('".$tags."')";
    $tags_condition_in = "name IN ('".$tags."')";
}

$table->colspan[$next_row][0] = 2;
$table->data[$next_row][0] = html_print_label_input_block(
    __('Tags'),
    '<div class="inline_flex align-center flex-space-around w100p"><div><b>'.__('Tags available').'</b><br>'.html_print_select_from_sql(
        "SELECT name AS name1, name AS name2
        FROM ttag
        WHERE $tags_condition_not
            ORDER BY name",
        'id_tag_available[]',
        '',
        '',
        '',
        '',
        true,
        true,
        false,
        false,
        'width: 200px',
        '5'
    ).'</div><div>'.html_print_image(
        'images/darrowright.png',
        true,
        [
            'id'    => 'right',
            'title' => __('Add tags to module'),
            'class' => 'invert_filter',
        ]
    ).'<br><br><br><br>'.html_print_image(
        'images/darrowleft.png',
        true,
        [
            'id'    => 'left',
            'title' => __('Delete tags to module'),
            'class' => 'invert_filter',
        ]
    ).'</div><div><b>'.__('Tags selected').'</b><br>'.html_print_select_from_sql(
        "SELECT name AS name1, name AS name2
        FROM ttag
        WHERE $tags_condition_in
            ORDER BY name",
        'id_tag_selected[]',
        '',
        '',
        '',
        '',
        true,
        true,
        false,
        false,
        'width: 200px',
        '5'
    ).'</div></div>'
);

$next_row++;
?>
<script type="text/javascript">
    $(document).ready (function () {
        $("#type").change(function () {
            var type_selected = $(this).val();
            var type_names = jQuery.parseJSON(Base64.decode($('#hidden-type_names').val()));
            
            var type_name_selected = type_names[type_selected];
            console.log(type_name_selected);
            var element = document.getElementById("module_type_help");
            var language =  "<?php echo $config['language']; ?>" ;

            if (typeof element !== 'undefined' && element !== null) {
                element.onclick = function (event) {
                    if(type_name_selected == 'async_data' ||
                     type_name_selected == 'async_proc' ||
                     type_name_selected == 'async_string' ||
                     type_name_selected == 'generic_proc'||
                     type_name_selected == 'generic_data' ||
                     type_name_selected == 'generic_data_inc' ||
                     type_name_selected == 'generic_data_inc_abs'||
                     type_name_selected == 'generic_data_string' ||
                     type_name_selected == 'keep_alive'
                       ){
                        if (language == 'es'){
                         window.open(
                             'https://pandorafms.com/manual/es/documentation/pandorafms/monitoring/02_operations#tipos_de_modulos',
                             '_blank',
                             'width=800,height=600'
                                );
                       }
                       else{
                        window.open(
                            'https://pandorafms.com/manual/en/documentation/pandorafms/monitoring/02_operations#types_of_modules',
                             '_blank',
                             'width=800,height=600'
                             );
                       }
                      
                        
                    }
                    if(type_name_selected == 'remote_icmp' ||
                     type_name_selected == 'remote_icmp_proc'
                     ){
                         if(language == 'es'){
                            window.open(
                            'https://pandorafms.com/manual/es/documentation/pandorafms/monitoring/03_remote_monitoring#monitorizacion_icmp',
                             '_blank',
                             'width=800,height=600'
                             );
                         }
                         else{
                            window.open(
                            'https://pandorafms.com/manual/en/documentation/pandorafms/monitoring/03_remote_monitoring#icmp_monitoring',
                             '_blank',
                             'width=800,height=600'
                             );
                         }
                      
                        
                    }
                    if(type_name_selected == 'remote_snmp_string' ||
                     type_name_selected == 'remote_snmp_proc' ||
                     type_name_selected == 'remote_snmp_inc' ||
                     type_name_selected == 'remote_snmp'
                     ){
                         if(language == 'es'){
                            window.open(
                            'https://pandorafms.com/manual/es/documentation/pandorafms/monitoring/03_remote_monitoring#monitorizando_con_modulos_de_red_tipo_snmp',
                             '_blank',
                             'width=800,height=600'
                             );
                         }
                         else{
                            window.open(
                            'https://pandorafms.com/manual/en/documentation/pandorafms/monitoring/03_remote_monitoring&printable=yes#monitoring_through_network_modules_with_snmp',
                             '_blank',
                             'width=800,height=600'
                             );
                         }
                       
                        
                    }
                    if(type_name_selected == 'remote_tcp_string' ||
                     type_name_selected == 'remote_tcp_proc' ||
                     type_name_selected == 'remote_tcp_inc' ||
                     type_name_selected == 'remote_tcp'
                       ){
                           if(language == 'es'){
                            window.open(
                            'https://pandorafms.com/manual/es/documentation/pandorafms/monitoring/03_remote_monitoring#monitorizacion_tcp',
                             '_blank',
                             'width=800,height=600'
                             );
                           }
                           else{
                            window.open(
                            'https://pandorafms.com/manual/en/documentation/pandorafms/monitoring/03_remote_monitoring&printable=yes#tcp_monitoring',
                             '_blank',
                             'width=800,height=600'
                             );
                           }
                      
                        
                    }
                    if(type_name_selected == 'web_data' ||
                     type_name_selected == 'web_proc' ||
                     type_name_selected == 'web_content_data' ||
                     type_name_selected == 'web_content_string'
                       ){
                           if(language == 'es'){
                            window.open(
                            'https://pandorafms.com/manual/es/documentation/pandorafms/monitoring/06_web_monitoring#creacion_de_modulos_web',
                             '_blank',
                             'width=800,height=600'
                             );
                           }
                           else{
                            window.open(
                            'https://pandorafms.com/manual/en/documentation/pandorafms/monitoring/06_web_monitoring#creating_web_modules',
                             '_blank',
                             'width=800,height=600'
                             );
                           }
                      
                        
                    }
                }
            }
            
            if (type_name_selected.match(/_string$/) == null) {
                // Numeric types
                $('#string_critical').hide();
                $('#string_warning').hide();
                $('#minmax_critical').show();
                $('#minmax_warning').show();
                $('#percentage_warning').show();
                $('#percentage_critical').show();
                
            }
            else {
                // String types
                $('#string_critical').show();
                $('#string_warning').show();
                $('#minmax_critical').hide();
                $('#minmax_warning').hide();
                $('#percentage_warning').hide();
                $('#percentage_critical').hide();
            }
        });
        
        $("#type").trigger('change');

        //Dynamic_interval;
        disabled_status();
        $('#dynamic_interval_select').change (function() {
            disabled_status();
        });

        //Dynamic_options_advance;
        $('#network_component-3-2').hide();
        $('#network_component-3-3').hide();

        //paint graph stutus critical and warning:
        paint_graph_values();
        $('#text-min_warning').on ('input', function() {
            paint_graph_values();
            if (isNaN($('#text-min_warning').val()) && !($('#text-min_warning').val() == "-")){
                $('#text-min_warning').val(0);
            }
        });
        $('#text-max_warning').on ('input', function() {
            paint_graph_values();
            if (isNaN($('#text-max_warning').val()) && !($('#text-max_warning').val() == "-")){
                $('#text-max_warning').val(0);
            }
        });
        $('#text-min_critical').on ('input', function() {
            paint_graph_values();
            if (isNaN($('#text-min_critical').val()) && !($('#text-min_critical').val() == "-")){
                $('#text-min_critical').val(0);
            }
        });
        $('#text-max_critical').on ('input', function() {
            paint_graph_values();
            if (isNaN($('#text-max_critical').val()) && !($('#text-max_critical').val() == "-")){
                $('#text-max_critical').val(0);
            }
        });

        if ($('#checkbox-warning_inverse').prop('checked') === true) {
        $('#percentage_warning').hide();
        }

        if ($('#checkbox-critical_inverse').prop('checked') === true) {
            $('#percentage_critical').hide();
        }

        if ($('#checkbox-percentage_warning').prop('checked') === true) {
            $('#warning_inverse').hide();
        }

        if ($('#checkbox-percentage_critical').prop('checked') === true) {
            $('#critical_inverse').hide();
        }

        $('#checkbox-warning_inverse').change (function() {
            paint_graph_values();
            if ($('#checkbox-warning_inverse').prop('checked') === true){
                $('#checkbox-percentage_warning').prop('checked', false);
                $('#percentage_warning').hide();
            } else {
                $('#percentage_warning').show();
            }
        }); 

        $('#checkbox-critical_inverse').change (function() {
            paint_graph_values();

            if ($('#checkbox-critical_inverse').prop('checked') === true){
                $('#checkbox-percentage_critical').prop('checked', false);
                $('#percentage_critical').hide();
            } else {
                $('#percentage_critical').show();
            }
        });

        $('#checkbox-percentage_warning').change (function() {
            paint_graph_values();
            if ($('#checkbox-percentage_warning').prop('checked') === true){
                $('#checkbox-warning_inverse').prop('checked', false);
                $('#warning_inverse').hide();
            } else {
                $('#warning_inverse').show();
            }
        });

        $('#checkbox-percentage_critical').change (function() {
            paint_graph_values();
            if ($('#checkbox-percentage_critical').prop('checked') === true){
                $('#checkbox-critical_inverse').prop('checked', false);
                $('#critical_inverse').hide();
            }
                else {
                $('#critical_inverse').show();
            }
                
        });
    });

    //readonly and add class input
    function disabled_status () {
        if($('#dynamic_interval_select').val() != 0){
            $('#text-min_warning').prop('readonly', true);
            $('#text-min_warning').addClass('readonly');
            $('#text-max_warning').prop('readonly', true);
            $('#text-max_warning').addClass('readonly');
            $('#text-min_critical').prop('readonly', true);
            $('#text-min_critical').addClass('readonly');
            $('#text-max_critical').prop('readonly', true);
            $('#text-max_critical').addClass('readonly');
        } else {
            $('#text-min_warning').prop('readonly', false);
            $('#text-min_warning').removeClass('readonly');
            $('#text-max_warning').prop('readonly', false);
            $('#text-max_warning').removeClass('readonly');
            $('#text-min_critical').prop('readonly', false);
            $('#text-min_critical').removeClass('readonly');
            $('#text-max_critical').prop('readonly', false);
            $('#text-max_critical').removeClass('readonly');
        }
    }

    //Dynamic_options_advance;
    function advanced_option_dynamic() {
        if($('#network_component-3-1').is(":visible")){
            $('#network_component-3-1').hide();
            $('#network_component-3-1').hide();
        } else {
            $('#network_component-3-1').show();
            $('#network_component-3-1').show();
        }
    }

    //function paint graph
    function paint_graph_values(){
        //Parse integrer
        var min_w = parseFloat($('#text-min_warning').val());
            if(min_w == '0.00'){ min_w = 0; }
        var max_w = parseFloat($('#text-max_warning').val());
            if(max_w == '0.00'){ max_w = 0; }
        var min_c = parseFloat($('#text-min_critical').val());
            if(min_c =='0.00'){ min_c = 0; }
        var max_c = parseFloat($('#text-max_critical').val());
            if(max_c =='0.00'){ max_c = 0; }
        var inverse_w = $('input:checkbox[name=warning_inverse]:checked').val();
            if(!inverse_w){ inverse_w = 0; }
        var inverse_c = $('input:checkbox[name=critical_inverse]:checked').val();
            if(!inverse_c){ inverse_c = 0; }
        
        //inicialiced error
        var error_w = 0;
        var error_c = 0;
        //messages legend
        var legend_normal = '<?php echo __('Normal Status'); ?>';
        var legend_warning = '<?php echo __('Warning Status'); ?>';
        var legend_critical = '<?php echo __('Critical Status'); ?>';
        //messages error
        var message_error_warning = '<?php echo __('Please introduce a maximum warning higher than the minimun warning'); ?>';
        var message_error_critical = '<?php echo __('Please introduce a maximum critical higher than the minimun critical'); ?>';
        var message_error_percentage = '<?php echo __('Please introduce a positive percentage value'); ?>';


        //Percentage selector
        var percentage_w = $('#checkbox-percentage_warning').prop('checked');
        var percentage_c = $('#checkbox-percentage_critical').prop('checked');

        if(percentage_w == true || percentage_c == true) {
            d3.select("#svg_dinamic rect").remove();
                //create svg
                var svg = d3.select("#svg_dinamic");
                svg.selectAll("g").remove();
            if (percentage_w === true) {
                if(max_w < 0 || min_w < 0) {
                    paint_graph_status(0,0,0,0,0,0,1,0,legend_normal,legend_warning,legend_critical,message_error_percentage,message_error_percentage);
                } else {
                    $("#text-max_warning").removeClass("input_error");
                    $("#text-min_warning").removeClass("input_error");
                }
                
            }

            if(percentage_c === true) {
                if(max_c < 0 || min_c < 0) {
                    paint_graph_status(0,0,0,0,0,0,0,1,legend_normal,legend_warning,legend_critical,message_error_percentage,message_error_percentage);
                } else {
                    $("#text-min-critical").removeClass("input_error");
                    $("#text-max_critical").removeClass("input_error");

                }
                } 

            return;

} else {
    $('#svg_dinamic').show();
}
        
        //if haven't error
        if(max_w == 0 || max_w > min_w){
            if(max_c == 0 || max_c > min_c){
                paint_graph_status(min_w, max_w, min_c, max_c, inverse_w, 
                                    inverse_c, error_w, error_c,
                                    legend_normal, legend_warning, legend_critical,
                                    message_error_warning, message_error_critical);
            } else {
                error_c = 1;
                paint_graph_status(0,0,0,0,0,0, error_w, error_c,
                                legend_normal, legend_warning, legend_critical,
                                message_error_warning, message_error_critical);
            }
        } else {
            error_w = 1;
            paint_graph_status(0,0,0,0,0,0, error_w, error_c, 
                                legend_normal, legend_warning, legend_critical,
                                message_error_warning, message_error_critical);
        }
    }

</script>
