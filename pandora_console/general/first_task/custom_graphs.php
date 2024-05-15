<?php

// Pandora FMS - https://pandorafms.com
// ==================================================
// Copyright (c) 2005-2023 Pandora FMS
// Please see https://pandorafms.com/community/ for full contribution list
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; version 2
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
global $config;
check_login();
ui_require_css_file('first_task');
$msg = __(
    'Graphs are designed to show the data collected by %s in a temporary scale defined by the user. %s Graphs display data in real time. They are generated every time the operator requires any of them and display the up-to-date state.',
    get_product_name(),
    get_product_name()
);

$msg .= '<br><br>'.__("There are two types of graphs: The agent's automated graphs and the graphs the user customizes by using one or more modules to do so.");

$button = '';

if (check_acl($config['id_user'], 0, 'RW') || check_acl($config['id_user'], 0, 'RM')) {
    $url_new = 'index.php?sec=reporting&sec2=godmode/reporting/graph_builder';
    $button = '<form action="'.$url_new.'" method="post">
            <input type="submit" class="button_task button_task_mini mrgn_0px_imp" value="'.__('Create custom graphs').'" />
        </form>';
}

echo ui_print_empty_view(
    __('Create custom graphs'),
    $msg,
    'custom-graph.svg',
    $button
);
