<?php
/**
 * Services first task.
 *
 * @category   Topology maps
 * @package    Pandora FMS
 * @subpackage Services
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

check_login();
ui_require_css_file('first_task');
if ((bool) $agent_w === true) {
    $msg = __(
        'A service is a way to group your IT resources based on their functionalities. 
						A service could be e.g. your official website, your CRM system, your support application, or even your printers.
						 Services are logical groups which can include hosts, routers, switches, firewalls, CRMs, ERPs, websites and numerous other services. 
						 By the following example, you are able to see more clearly what a service is:
							A chip manufacturer sells computers by its website all around the world. 
							His company consists of three big departments: A management, an on-line shop and support.'
    );

            $url_new = 'index.php?sec=estado&sec2=enterprise/godmode/services/services.service&action=new_service';
            $button = '<form action="'.$url_new.'" method="post">
                <input type="submit" class="button_task button_task_mini mrgn_0px_imp" value="'.__('Configure services').'" />
            </form>';

    echo ui_print_empty_view(
        __('No services found'),
        $msg,
        'services.svg',
        $button
    );
}
