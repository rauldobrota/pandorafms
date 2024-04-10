<?php
/**
 * Json Web Token ajax
 *
 * @category   Ajax library.
 * @package    Pandora FMS
 * @subpackage Modules.
 * @version    1.0.0
 * @license    See below
 *
 *    ______                 ___                    _______ _______ ________
 *   |   __ \.-----.--.--.--|  |.-----.----.-----. |    ___|   |   |     __|
 *  |    __/|  _  |     |  _  ||  _  |   _|  _  | |    ___|       |__     |
 * |___|   |___._|__|__|_____||_____|__| |___._| |___|   |__|_|__|_______|
 *
 * ============================================================================
 * Copyright (c) 2005-2021 Artica Soluciones Tecnologicas
 * Please see http://pandorafms.org for full contribution list
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation for version 2.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * ============================================================================
 */

global $config;

if (is_ajax() === false) {
    exit;
}

// Begin.
require_once $config['homedir'].'/include/class/JWTRepository.class.php';



try {
    $class = new JWTRepository($config['JWT_signature']);
} catch (Exception $e) {
    exit;
}

// Ajax controller.
$method = get_parameter('method', '');
$only_metaconsole = (bool) get_parameter('only_metaconsole', false);

if (method_exists($class, $method) === true) {
    if ($class->ajaxMethod($method) === true) {
        if ($only_metaconsole === true) {
            if (is_metaconsole() === true) {
                $res = $class->{$method}();
                echo json_encode(['success' => true, 'data' => $res]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Environment is not a metaconsole']);
            }
        } else {
            $res = $class->{$method}();
            echo json_encode(['success' => true, 'data' => $res]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Unavailable method.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Unavailable method.']);
}

exit;
