<?php
/**
 * Map builder First Task.
 *
 * @category   Topology maps
 * @package    Pandora FMS
 * @subpackage Visual consoles
 * @version    1.0.0
 * @license    See below
 *
 *    ______                 ___                    _______ _______ ________
 * |   __ \.-----.--.--.--|  |.-----.----.-----. |    ___|   |   |     __|
 * |    __/|  _  |     |  _  ||  _  |   _|  _  | |    ___|       |__     |
 * |___|   |___._|__|__|_____||_____|__| |___._| |___|   |__|_|__|_______|
 *
 * ============================================================================
 * Copyright (c) 2007-2023 Pandora FMS
 * Please see https://pandorafms.com/community/ for full contribution list
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation for version 2.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * ============================================================================
 */

// Begin.
global $config;
global $vconsoles_write;
global $vconsoles_manage;
check_login();
ui_require_css_file('first_task');

if ($vconsoles_write || $vconsoles_manage) {
    $msg = __(
        "%s allows users to create visual maps on which each user is able to create his or her own monitoring map. The new visual console editor is much more practical, although the prior 'visual console editor had its advantages.",
        get_product_name()
    );

    $msg .= '<br><br>'.__(
        "On the new visual console, we've been successful in imitating the sensation and touch of a drawing application like GIMP. We've also simplified the editor by dividing it into several subject-divided tabs named 'Data', 'Preview', 'Wizard', 'List of Elements' and 'Editor'."
    );

    $msg .= '<br><br>'.__(
        " The items the %s Visual Map was designed to handle are 'static images', 'percentage bars', 'module graphs' and 'simple values'",
        get_product_name()
    );

    $url_new = 'index.php?sec=network&amp;sec2=godmode/reporting/visual_console_builder';
    $button = '<form action="'.$url_new.'" method="post">';
    $button .= html_print_input_hidden('edit_layout', 1);
    $button .= '<input type="submit" class="button_task button_task_mini mrgn_0px_imp" value="'.__('Create visual console').'" />';
    $button .= '</form>';

    echo ui_print_empty_view(
        __('There are no customized visual consoles'),
        $msg,
        'visual-console.svg',
        $button
    );
}
