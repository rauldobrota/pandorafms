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


 /**
  * Draw message downloads.
  *
  * @param string $type Type.
  *
  * @return string
  */
function draw_msg_download(string $type='agent'): string
{
    ui_require_css_file('downloads');

    $msg = '';
    $title = '';
    $buttons_links = '';
    $footer = '';
    $extra_class = '';
    if ($type === 'satellite') {
        $extra_class = 'container-downloads-satellite';
        $title .= __(
            '%s satellite server',
            get_product_name()
        );

        $msg .= __(
            'The satellite is used to perform discovery, and to be able to perform remote monitoring of network equipment, windows and linux machines in those environments that are far from the %s server and do not have full connectivity (only from the Satellite to the %s server). This is especially useful in client environments or remote networks where we can\'t install agents, and we don\'t have visibility from our main network. By installing a satellite server we can monitor the systems in those networks from the %s console.',
            get_product_name(),
            get_product_name(),
            get_product_name()
        );

        $msg .= '<br><br>';

        $msg .= __(
            'The satellite server can be installed in Windows64 bit and Linux, it doesn\'t need many resources (you can install it in a virtual machine) and you only need to be able to access your %s server using the Tentacle port (41121/tcp).',
            get_product_name()
        );

        $buttons_links .= '<div class="satellite-buttons-links">';
        $buttons_links .= '<a href="https://pfms.me/windows-x64-satellite">';

        $buttons_links .= html_print_button(
            __('Windows 64 bit'),
            'satellite_windows_download',
            false,
            '',
            ['icon' => 'windows'],
            true,
            false
        );

        $buttons_links .= '</a>';
        $buttons_links .= '<a href="http://pfms.me/linux-x64-satellite-tarball">';

        $buttons_links .= html_print_button(
            __('Linux 64 bit (Tarball)'),
            'satellite_windows_download',
            false,
            '',
            ['icon' => 'linux'],
            true,
            false
        );

        $buttons_links .= '</a></div>';

        $footer .= '<i>';
        $footer .= __(
            'More downloads are available in the “File releases” section of the %s support portal.',
            get_product_name()
        );

        $footer .= '</i>';
    } else {
        $title .= __(
            '%s agents',
            get_product_name()
        );

        $msg .= __(
            'The %s agent is necessary to obtain detailed information of the system you want to monitor, it allows you to obtain more information than remote monitoring (without agent). In addition, if you want to use the RMM functions of the %s agent, it is essential to install the %s agent and the Pandora RC agent. The agent will need to access your %s server using the Tentacle port (41121/tcp).',
            get_product_name(),
            get_product_name(),
            get_product_name(),
            get_product_name()
        );

        $msg .= '<br><br>';

        $link = '<a target="_blank" href="index.php?sec=gagente&sec2=godmode/agentes/modificar_agente">';
        $link .= __('Management -> Resources -> Manage agents section.');
        $link .= '</a>';

        $msg .= __(
            'You can install agents, one by one, using the Agent Deployment Wizard from the %s If you want to download the agent to make a massive deployment, with our agent deployment tool or with another tool, you can download it from these links:',
            $link,
        );

        $buttons_links .= '<div class="links-dowloads-agents">';
        $buttons_links .= '<div class="links-dowloads-agents-title">';
        $buttons_links .= '<div>';
        $buttons_links .= html_print_image(
            'images/downloads_links/logo.svg',
            true,
            ['title' => __('%s ONE agent', get_product_name())]
        );
        $buttons_links .= '</div>';

        $buttons_links .= '<div>';
        $buttons_links .= __('%s ONE agent', get_product_name());
        $buttons_links .= '</div>';

        $buttons_links .= '</div>';
        $buttons_links .= '<ul><li>';
        $buttons_links .= '<a href="https://pfms.me/windosx64-agent">';
        $buttons_links .= html_print_image(
            'images/downloads_links/windows.svg',
            true,
            ['title' => 'Windows']
        );

        $buttons_links .= '<div>';
        $buttons_links .= 'Windows 64 bit ->';
        $buttons_links .= '</div>';

        $buttons_links .= '</a></li><li>';
        $buttons_links .= '<a href="https://pfms.me/linux-x64-agent-tarball">';
        $buttons_links .= html_print_image(
            'images/downloads_links/linux.svg',
            true,
            ['title' => 'Linux']
        );
        $buttons_links .= '<div>';
        $buttons_links .= 'Linux 64 bit (Tarball) ->';
        $buttons_links .= '</div>';
        $buttons_links .= '</a></li><li>';
        $buttons_links .= '<a href="https://pfms.me/linux-x64-agent-rpm">';
        $buttons_links .= html_print_image(
            'images/downloads_links/linux.svg',
            true,
            ['title' => 'Linux']
        );
        $buttons_links .= '<div>';
        $buttons_links .= 'Linux 64 bit el7 (RPM) ->';
        $buttons_links .= '</div>';
        $buttons_links .= '</a></li><li>';
        $buttons_links .= '<a href="https://pfms.me/linux-x64-agent-el8-rpm">';
        $buttons_links .= html_print_image(
            'images/downloads_links/linux.svg',
            true,
            ['title' => 'Linux']
        );
        $buttons_links .= '<div>';
        $buttons_links .= 'Linux 64 bit el8 (RPM) ->';
        $buttons_links .= '</div>';
        $buttons_links .= '</a></li><li>';
        $buttons_links .= '<a href="https://pfms.me/linux-x64-agent-el9-rpm">';
        $buttons_links .= html_print_image(
            'images/downloads_links/linux.svg',
            true,
            ['title' => 'Linux']
        );
        $buttons_links .= '<div>';
        $buttons_links .= 'Linux 64 bit el9 (RPM) ->';
        $buttons_links .= '</div>';
        $buttons_links .= '</a></li><li>';
        $buttons_links .= '<a href="https://pfms.me/macos-x64-agent-dmg">';
        $buttons_links .= html_print_image(
            'images/downloads_links/mac.svg',
            true,
            ['title' => 'Mac']
        );
        $buttons_links .= '<div>';
        $buttons_links .= 'MacOS 64 bit ->';
        $buttons_links .= '</div>';
        $buttons_links .= '</a></li></ul>';
        $buttons_links .= '</div>';

        $buttons_links .= '<div class="links-dowloads-agents">';
        $buttons_links .= '<div class="links-dowloads-agents-title">';
        $buttons_links .= '<div>';
        $buttons_links .= html_print_image(
            'images/downloads_links/rc.svg',
            true,
            ['title' => __('Pandora RC agent')]
        );
        $buttons_links .= '</div>';

        $buttons_links .= '<div>';
        $buttons_links .= __('Pandora RC agent');
        $buttons_links .= '</div>';

        $buttons_links .= '</div>';
        $buttons_links .= '<ul><li>';
        $buttons_links .= '<a href="https://pfms.me/windows-x64-rc">';
        $buttons_links .= html_print_image(
            'images/downloads_links/windows.svg',
            true,
            ['title' => 'Windows']
        );

        $buttons_links .= '<div>';
        $buttons_links .= 'Windows 64 bit ->';
        $buttons_links .= '</div>';

        $buttons_links .= '</a></li><li>';
        $buttons_links .= '<a href="https://pfms.me/linux-x64-rc-rpm">';
        $buttons_links .= html_print_image(
            'images/downloads_links/linux.svg',
            true,
            ['title' => 'Linux']
        );
        $buttons_links .= '<div>';
        $buttons_links .= 'Linux 64 bit (RPM) ->';
        $buttons_links .= '</div>';
        $buttons_links .= '</a></li><li>';
        $buttons_links .= '<a href="https://pfms.me/macos-x64-rc-dmg">';
        $buttons_links .= html_print_image(
            'images/downloads_links/mac.svg',
            true,
            ['title' => 'Mac']
        );
        $buttons_links .= '<div>';
        $buttons_links .= 'MacOS 64 bit ->';
        $buttons_links .= '</div>';
        $buttons_links .= '</a></li></ul>';
        $buttons_links .= '</div>';

        $footer .= '<i>';
        $footer .= __(
            'More downloads are available in the “File releases” section of the %s support portal.',
            get_product_name()
        );
        $footer .= '</i>';
    }

    $output = '<div class="container-downloads '.$extra_class.' ">';
    $output .= '<div class="card-downloads">';
    $output .= '<div class="card-downloads-title">';
    $output .= $title;
    $output .= '</div>';

    $output .= '<div class="card-downloads-msg">';
    $output .= $msg;
    $output .= '</div>';

    $output .= '<div class="card-downloads-links">';
    $output .= $buttons_links;
    $output .= '</div>';

    $output .= '<div class="card-downloads-footer">';
    $output .= $footer;
    $output .= '</div>';

    $output .= '</div>';
    $output .= '</div>';

    return $output;
}
