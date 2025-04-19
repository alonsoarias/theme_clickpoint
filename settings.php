<?php
// This file is part of Moodle - http://moodle.org/
// 
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Theme settings configuration for theme_clickpoint.
 *
 * @package    theme_clickpoint
 * @copyright  2024 Soporte ClickPoint <soporte@clickpoint.co>
 * @author     Pedro Alonso Arias Balcucho
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require(__DIR__ . '/../remui/settings.php');
require_once(__DIR__ . '/classes/admin_settingspage_tabs.php');
require_once($CFG->libdir . '/accesslib.php');
require_once(__DIR__ . '/lib.php');

// Capturar pestañas del tema padre (si existen).
$parent_tabs = null;
if (isset($settings) && method_exists($settings, 'get_tabs')) {
    $parent_tabs = $settings->get_tabs();
}

unset($settings);
$settings = null;

// Crear categoría en "Apariencia".
$ADMIN->add('appearance', new admin_category('theme_clickpoint', get_string('pluginname', 'theme_clickpoint')));

// Crear objeto de configuraciones con pestañas.
$asettings = new theme_clickpoint_admin_settingspage_tabs(
    'themesettingclickpoint',
    get_string('themesettings', 'theme_clickpoint'),
    'moodle/site:config'
);

if ($ADMIN->fulltree) {
    // Variables comunes.
    $a = new stdClass();
    $a->example_banner = (string)$OUTPUT->image_url('example_banner', 'theme_clickpoint');
    $a->cover_remui = (string)$OUTPUT->image_url('cover_remui', 'theme');
    $a->example_cover1 = (string)$OUTPUT->image_url('login_bg_corp', 'theme');
    $a->example_cover2 = (string)$OUTPUT->image_url('login_bg', 'theme');

    /* =========================================================================
       TAB 1: General Settings
       ========================================================================= */
    $page = new admin_settingpage('theme_clickpoint_cp_generals', get_string('generalsettings', 'theme_clickpoint'));

    // --- Notificaciones Generales ---
    $page->add(new admin_setting_heading(
        'theme_clickpoint/cp_generalnoticeheading',
        get_string('generalnoticeheading', 'theme_clickpoint'),
        ''
    ));

    $name = 'theme_clickpoint/cp_generalnoticemode';
    $title = get_string('generalnoticemode', 'theme_clickpoint');
    $description = get_string('generalnoticemodedesc', 'theme_clickpoint');
    $default = 'off';
    $choices = [
        'off' => get_string('generalnoticemode_off', 'theme_clickpoint'),
        'info' => get_string('generalnoticemode_info', 'theme_clickpoint'),
        'danger' => get_string('generalnoticemode_danger', 'theme_clickpoint')
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_clickpoint/cp_generalnotice';
    $title = get_string('generalnotice', 'theme_clickpoint');
    $description = get_string('generalnoticedesc', 'theme_clickpoint');
    $default = '<strong>Estamos trabajando</strong> para mejorar...';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // --- Chat Settings ---
    $page->add(new admin_setting_heading(
        'theme_clickpoint/cp_chatheading',
        get_string('chatheading', 'theme_clickpoint'),
        ''
    ));

    $name = 'theme_clickpoint/cp_enable_chat';
    $title = get_string('enable_chat', 'theme_clickpoint');
    $description = get_string('enable_chatdesc', 'theme_clickpoint');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_clickpoint/cp_tawkto_embed_url';
    $title = get_string('tawkto_embed_url', 'theme_clickpoint');
    $description = get_string('tawkto_embed_urldesc', 'theme_clickpoint');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // --- Content Protection Settings ---
    $page->add(new admin_setting_heading(
        'theme_clickpoint/cp_contentprotectionheading',
        get_string('contentprotectionheading', 'theme_clickpoint'),
        ''
    ));

    $name = 'theme_clickpoint/cp_copypaste_prevention';
    $title = get_string('copypaste_prevention', 'theme_clickpoint');
    $description = get_string('copypaste_preventiondesc', 'theme_clickpoint');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Roles para protección.
    $roles = role_get_names(null, ROLENAME_ORIGINAL);
    $roles_array = [];
    foreach ($roles as $role) {
        $roles_array[$role->id] = $role->localname;
    }

    $name = 'theme_clickpoint/cp_copypaste_roles';
    $title = get_string('copypaste_roles', 'theme_clickpoint');
    $description = get_string('copypaste_rolesdesc', 'theme_clickpoint');
    $default = [5]; // Rol de estudiante por defecto.
    $setting = new admin_setting_configmultiselect($name, $title, $description, $default, $roles_array);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $asettings->add_tab($page);

    /* =========================================================================
       TAB 2: Login Page Settings
       ========================================================================= */
    $page = new admin_settingpage('theme_clickpoint_cp_login', get_string('loginsettings', 'theme_clickpoint'));

    // Carousel Settings.
    $page->add(new admin_setting_heading(
        'theme_clickpoint/cp_carousel',
        get_string('carouselsettings', 'theme_clickpoint'),
        ''
    ));

    // Número de slides (se utiliza el prefijo "loging_" para evitar duplicidad).
    $name = 'theme_clickpoint/cp_loging_numberofslides';
    $title = get_string('numberofslides', 'theme_clickpoint');
    $description = get_string('numberofslides_desc', 'theme_clickpoint');
    $choices = range(1, 5);
    $page->add(new admin_setting_configselect($name, $title, $description, 1, array_combine($choices, $choices)));

    // Settings para cada slide.
    $numslides = get_config('theme_clickpoint', 'cp_loging_numberofslides') ?: 1;
    for ($i = 1; $i <= $numslides; $i++) {
        // Imagen del slide.
        $name = 'theme_clickpoint/cp_loging_slideimage' . $i;
        $title = get_string('slideimage', 'theme_clickpoint', $i);
        $description = get_string('slideimage_desc', 'theme_clickpoint', $i);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'cp_loging_slideimage' . $i, 0, [
            'subdirs' => 0,
            'accepted_types' => ['web_image']
        ]);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    }

    // Intervalo del carrusel.
    $name = 'theme_clickpoint/cp_carouselinterval';
    $title = get_string('carouselinterval', 'theme_clickpoint');
    $description = get_string('carouselintervaldesc', 'theme_clickpoint');
    $setting = new admin_setting_configtext($name, $title, $description, '5000');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $asettings->add_tab($page);

    /* =========================================================================
       TAB 3: Dashboard Settings
       ========================================================================= */
    $page = new admin_settingpage('theme_clickpoint_cp_dashboard', get_string('dashboardsettings', 'theme_clickpoint'));

    // Personal Area Header Settings.
    $page->add(new admin_setting_heading(
        'theme_clickpoint/cp_personalareaheading',
        get_string('personalareaheading', 'theme_clickpoint'),
        ''
    ));

    // Toggle de visibilidad del Personal Area Header.
    $name = 'theme_clickpoint/cp_show_personalareaheader';
    $title = get_string('show_personalareaheader', 'theme_clickpoint');
    $description = get_string('show_personalareaheaderdesc', 'theme_clickpoint');
    $default = 0;
    $choices = [
        0 => get_string('hide', 'theme_clickpoint'),
        1 => get_string('show', 'theme_clickpoint')
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Imagen del Personal Area Header.
    $name = 'theme_clickpoint/cp_personalareaheader';
    $title = get_string('personalareaheader', 'theme_clickpoint');
    $description = get_string('personalareaheaderdesc', 'theme_clickpoint', $a);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'cp_personalareaheader', 0, [
        'subdirs' => 0,
        'accepted_types' => 'web_image'
    ]);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // My Courses Header Settings.
    $page->add(new admin_setting_heading(
        'theme_clickpoint/cp_mycoursesheading',
        get_string('mycoursesheading', 'theme_clickpoint'),
        ''
    ));

    // Toggle de visibilidad del My Courses Header.
    $name = 'theme_clickpoint/cp_show_mycoursesheader';
    $title = get_string('show_mycoursesheader', 'theme_clickpoint');
    $description = get_string('show_mycoursesheaderdesc', 'theme_clickpoint');
    $default = 0;
    $choices = [
        0 => get_string('hide', 'theme_clickpoint'),
        1 => get_string('show', 'theme_clickpoint')
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Imagen del My Courses Header.
    $name = 'theme_clickpoint/cp_mycoursesheader';
    $title = get_string('mycoursesheader', 'theme_clickpoint');
    $description = get_string('mycoursesheaderdesc', 'theme_clickpoint', $a);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'cp_mycoursesheader', 0, [
        'subdirs' => 0,
        'accepted_types' => 'web_image'
    ]);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $asettings->add_tab($page);

    /* =========================================================================
       TAB 4: Footer Settings
       ========================================================================= */
    $page = new admin_settingpage('theme_clickpoint_cp_footer', get_string('footersettings', 'theme_clickpoint'));

    // Visibilidad del Footer.
    $name = 'theme_clickpoint/cp_hidefootersections';
    $title = get_string('hidefootersections', 'theme_clickpoint');
    $description = get_string('hidefootersections_desc', 'theme_clickpoint');
    $default = 0;
    $choices = [
        0 => get_string('show', 'theme_clickpoint'),
        1 => get_string('hide', 'theme_clickpoint')
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $page->add($setting);

    // About Section.
    $page->add(new admin_setting_heading(
        'theme_clickpoint/cp_footeraboutheading',
        get_string('footeraboutheading', 'theme_clickpoint'),
        ''
    ));

    $name = 'theme_clickpoint/cp_abouttitle';
    $title = get_string('abouttitle', 'theme_clickpoint');
    $description = get_string('abouttitledesc', 'theme_clickpoint');
    $default = get_string('abouttitle_default', 'theme_clickpoint');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_clickpoint/cp_abouttext';
    $title = get_string('abouttext', 'theme_clickpoint');
    $description = get_string('abouttextdesc', 'theme_clickpoint');
    $default = get_string('abouttext_default', 'theme_clickpoint');
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $asettings->add_tab($page);

    // Si existen pestañas del tema padre, combinarlas.
    if ($parent_tabs !== null) {
        $all_tabs = array_merge($asettings->get_tabs(), $parent_tabs);
        $asettings->set_tabs($all_tabs);
    }
}

// Agregar la página de configuraciones a la categoría de apariencia.
$ADMIN->add('theme_clickpoint', $asettings);