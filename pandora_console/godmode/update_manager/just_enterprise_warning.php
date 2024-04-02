<?php
/**
 * Modal LTS versions update manager.
 *
 * @category   Update Manager
 * @package    Pandora FMS
 * @subpackage Community
 * @version    1.0.0
 * @license    See below
 *
 *    ______                 ___                    _______ _______ ________
 * |   __ \.-----.--.--.--|  |.-----.----.-----. |    ___|   |   |     __|
 * |    __/|  _  |     |  _  ||  _  |   _|  _  | |    ___|       |__     |
 * |___|   |___._|__|__|_____||_____|__| |___._| |___|   |__|_|__|_______|
 *
 * ============================================================================
 * Copyright (c) 2005-2023 Pandora FMS
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
// The ajax is in include/ajax/update_manager.php.
if (! check_acl($config['id_user'], 0, 'PM') && ! is_user_admin($config['id_user'])) {
    db_pandora_audit(
        AUDIT_LOG_ACL_VIOLATION,
        'Trying to access Setup Management'
    );
    include 'general/noaccess.php';
    return;
}

require_once $config['homedir'].'/vendor/autoload.php';

$php_version = phpversion();
$php_version_array = explode('.', $php_version);
if ($php_version_array[0] < 7) {
    include_once 'general/php_message.php';
}

?>

<!-- Lts Updates. -->
<div id="lts-updates" title="
    <?php echo __('PandoraFMS'); ?>
    " class="invisible">
    <div style="display: flex; justify-content: space-between">
        <div style="width: 250px; padding: 36px">
            <?php
            echo html_print_image(
                'images/custom_logo/logo-default-pandorafms-collapsed.svg',
                true,
                ['class' => 'w100p mrgn_top_50px']
            );
            ?>
        </div>
        <div style="padding: 5px 90px 5px 5px;">
            <p class="lato font_15px bolder">
                <?php
                echo __('From the 777 LTS version onwards, product updates will be differentiated in the Enterprise version and the Open Source version. ');
                ?>
            </p>
            <p class="lato font_15px bolder">
                <?php
                echo __('Pandora FMS Community code will always be free and open, available with no charge of use and no need to register in').'<a href="https://github.com/pandorafms/pandorafms" target="_blank" class="font_15px" style="color: #82b92e;"> Github </a>'.__('and this will be the access way for future upgrades.');
                ?>
            </p>
            <p class="lato font_15px bolder">
                <?php
                echo __('Our Enterprise version customers, both paid and Free Edition, will continue to enjoy automatic updates with the new Warp 2.0 system that will also include the new synchronized plugin library, and other improvements.');
                ?>
            </p>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        // Lts Updates.
        $("#lts-updates").dialog({
            resizable: true,
            draggable: true,
            modal: true,
            width: 740,
            overlay: {
                opacity: 0.5,
                background: "black"
            },
            closeOnEscape: true,
            buttons: [{
                text: "<?php echo __('Close'); ?>",
                click: function() {
                    $(this).dialog("close");
                }
            }],
            open: function(event, ui) {
                $(".ui-dialog-titlebar-close").hide();
            }
        });
    });
</script>