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
        'intro'             => __('Pandora FMS is designed to monitor thousands of elements. It is possible to use very low sampling intervals using specific elements for it (Satellite Server) but doing it in a centralized way can have a severe impact in the performance, that\'s why we limit it to intervals of at least 60 seconds. Even then, you should only do 60 second sampling on very specific modules. The impact on your infrastructure can be severe, leading to event storms and delays in other monitoring. If you are going to use 60-second intervals, we recommend that you disable unknown detection to avoid unwanted events and use FlipFlop\'s protection settings.'),
        'title'             => __('Data Configuration Module.'),
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
