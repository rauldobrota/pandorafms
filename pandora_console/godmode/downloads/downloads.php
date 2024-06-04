<?php
/**
 *  ______                 ___                    _______ _______ ________
 * |   __ \.-----.--.--.--|  |.-----.----.-----. |    ___|   |   |     __|
 * |    __/|  _  |     |  _  ||  _  |   _|  _  | |    ___|       |__     |
 * |___|   |___._|__|__|_____||_____|__| |___._| |___|   |__|_|__|_______|
 *
 * Pandora FMS Copyright (c) 2004-2024 Pandora FMS SLU
 *
 * Por la simple instalación o aceptación de la instalación
 * y por el simple uso de la aplicación,
 * el cliente declara haber leído, entendido y aceptado
 * todas las condiciones contenidas en la Licencia de Pandora FMS.
 * Para más detalles consulte: https://pandorafms.com/es/informacion-legal/
 *
 * By the simple installation or acceptance of the installation
 * and by the simple use of the application,
 * the client declares to have read, understood and accepted
 * all the conditions contained in the Pandora FMS License.
 * For more details see: https://pandorafms.com/en/legal-information-licenses/
 */

global $config;

require_once $config['homedir'].'/include/functions_downloads.php';

check_login();

if (! check_acl($config['id_user'], 0, 'AR') || enterprise_installed() === false) {
    // Doesn't have access to this page.
    db_pandora_audit(
        AUDIT_LOG_ACL_VIOLATION,
        'Trying to access Downloads View'
    );
    include 'general/noaccess.php';
    exit;
}

$url = 'index.php?sec=gdownloads&sec2=godmode/downloads/downloads';

$tab = get_parameter('tab', 'agents');
$headerTitle = ($tab === 'satellite') ? __('Downloads satellite') : __('Downloads agent');

$buttons['satellite'] = [
    'active' => ($tab === 'satellite') ? true : false,
    'text'   => '<a href="'.$url.'&tab=satellite">'.html_print_image(
        'images/satellite@os.svg',
        true,
        [
            'title' => __('Satellite'),
            'class' => 'main_menu_icon invert_filter',
        ]
    ).'</a>',
];

$buttons['agents'] = [
    'active' => ($tab === 'satellite') ? false : true,
    'text'   => '<a href="'.$url.'">'.html_print_image(
        'images/agents@svg.svg',
        true,
        [
            'title' => __('Agent'),
            'class' => 'main_menu_icon invert_filter',
        ]
    ).'</a>',
];

// Header.
ui_print_standard_header(
    $headerTitle,
    '',
    false,
    '',
    true,
    $buttons,
    [
        [
            'link'  => '',
            'label' => __('Downloads'),
        ],
    ]
);

echo draw_msg_download($tab);
