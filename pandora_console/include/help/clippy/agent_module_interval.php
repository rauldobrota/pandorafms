<?php

// Pandora FMS - https://pandorafms.com
// ==================================================
// Copyright (c) 2005-2023 Pandora FMS
// Please see https://pandorafms.com/community/ for full contribution list
// This program is free software; you can redistribute it and/or
// modify it under the terms of the  GNU Lesser General Public License
// as published by the Free Software Foundation; version 2
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

/**
 * @package    Include
 * @subpackage Clippy
 */


function clippy_agent_module_interval()
{
    $return_tours = [];
    $return_tours['first_step_by_default'] = true;
    $return_tours['help_context'] = true;
    $return_tours['tours'] = [];

    $return_tours['tours']['agent_module_interval'] = [];
    $return_tours['tours']['agent_module_interval']['steps'] = [];
    $return_tours['tours']['agent_module_interval']['steps'][] = [
        'init_step_context' => true,
        'intro'             => __('Pandora FMS has been designed to monitor thousands of elements. It is possible to use very low sampling intervals by using specific items for that purpose (Satellite Server), but doing so in a centralized way may negatively affect performance. That is why it is limited to 60-second intervals. Even so, 60-second interval sampling should only take place in very specific modules. The impact on your infrastructure may be severe, leading to event storms and monitoring delays. Should you use 60-second intervals, it is recommended to disable unknown detection monitoring to avoid undesired events and use the FlipFlop protection setup'),
        'title'             => __('Notice'),
        'img'               => html_print_image(
            'images/info-warning.svg',
            true,
            [
                'class' => 'main_menu_icon invert_filter',
                'style' => 'margin-left: 5px;',
            ]
        ),
    ];
    $return_tours['tours']['agent_module_interval']['conf'] = [];
    $return_tours['tours']['agent_module_interval']['conf']['autostart'] = false;
    $return_tours['tours']['agent_module_interval']['conf']['show_bullets'] = 0;
    $return_tours['tours']['agent_module_interval']['conf']['show_step_numbers'] = 0;

    return $return_tours;
}
