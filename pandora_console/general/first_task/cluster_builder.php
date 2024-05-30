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

if (! check_acl($config['id_user'], 0, 'AR') && ! check_acl($config['id_user'], 0, 'AW')) {
    db_pandora_audit(
        AUDIT_LOG_ACL_VIOLATION,
        'Trying to access Inventory'
    );
    include 'general/noaccess.php';
    return;
}

// Header.
ui_print_standard_header(
    __('Clusters'),
    'images/chart.png',
    false,
    '',
    false,
    [],
    [
        [
            'link'  => '',
            'label' => __('Monitoring'),
        ],
    ]
);

ui_require_css_file('first_task');

$msg = __('A cluster is a group of devices that provide the same service in high availability.').'<br>';

$msg .= __('Depending on how they provide that service, we can find two types:').'<br><br>';

$msg .= __('Clusters to balance the service load: these are active - active (A/A) mode clusters. It means that all the nodes (or machines that compose it) are working. They must be working because if one stops working, it will overload the others.').'<br><br>';

$msg .= __('Clusters to guarantee service: these are active - passive (A/P) mode clusters. It means that one of the nodes (or machines that make up the cluster) will be running (primary) and another will not (secondary). When the primary goes down, the secondary must take over and give the service instead. Although many of the elements of this cluster are active-passive, it will also have active elements in both of them that indicate that the passive node is "online", so that in the case of a service failure in the master, the active node collects this information.');



$button = false;
if (check_acl($config['id_user'], 0, 'AW')) {
    $button = "
    <form action='index.php?sec=estado&sec2=operation/cluster/cluster&op=new' method='post'>
        <input type='submit' class='button_task button_task_mini mrgn_0px_imp' value='".__('Create cluster')."' />
    </form>";
}

echo ui_print_empty_view(
    __('There are no defined clusters'),
    $msg,
    'clusters.svg',
    $button
);
