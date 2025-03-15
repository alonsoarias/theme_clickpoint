<?php

/**
 * Provides the core rendering functionality for the theme_clickpoint, aligning Moodle's HTML with Bootstrap expectations.
 *
 * This core renderer class extends theme_remui's core renderer, adding specific modifications to enhance and customize
 * the user interface for theme_clickpoint. Key functionalities include customized login forms, theme settings integration,
 * and dynamic handling of UI elements like carousels and notices based on theme configurations.
 *
 * @package    theme_clickpoint
 * @category   output
 * @author     Pedro Alonso Arias Balcucho
 * @copyright  2024 Soporte clickpoint <soporte@clickpoint.co>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_clickpoint\output;

use theme_config;
use moodle_url;
use context_course;
use moodle_exception;
use company_user;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../remui/classes/output/core_renderer.php');
require_once(__DIR__ . '/../util/theme_settings.php');


/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 */
class core_renderer extends \theme_remui\output\core_renderer
{
    /**
     * Cached theme config
     * @var object
     */
    protected $themeConfig = null;

    /**
     * Get theme config with caching
     * @return object
     */
    public function get_theme_config() {
        if ($this->themeConfig === null) {
            $this->themeConfig = theme_config::load('clickpoint');
        }
        return $this->themeConfig;
    }
    
    /**
     * The standard tags that should be included in the <head> tag
     * including lazysizes for optimized image loading and IOMAD styles
     * 
     * @return string HTML fragment.
     */
    public function standard_head_html()
    {
        global $SITE, $PAGE, $DB, $CFG;

        // Obtener el resultado base del padre
        $output = parent::standard_head_html();
        
        // Integrar lazysizes.min.js para carga optimizada de imágenes
        $lazysizesurl = new moodle_url('/theme/clickpoint/js/lazysizes.min.js');
        $output .= '<script src="' . $lazysizesurl . '" async defer></script>';
        
        // Configuración personalizada de lazysizes
        $output .= '
        <script>
            window.lazySizesConfig = window.lazySizesConfig || {};
            window.lazySizesConfig.loadMode = 1; // Cargar imágenes más agresivamente
        </script>';

        // Inyectar estilos de IOMAD directamente si la clase está disponible
        if (class_exists('\iomad')) {
            $css = '';
            try {
                $companyid = \iomad::get_my_companyid(\context_system::instance(), false);

                if ($companyid && ($company = $DB->get_record('company', array('id' => $companyid)))) {
                    $css .= "/* IOMAD Custom Styles with Clickpoint Enhancements */\n";

                    // Variables para uso interno
                    $primaryColor = !empty($company->headingcolor) ? $company->headingcolor : null;
                    $secondaryColor = !empty($company->linkcolor) ? $company->linkcolor : null;
                    $mainColor = !empty($company->maincolor) ? $company->maincolor : null;

                    // 1. ENCABEZADOS Y TEXTO
                    if ($primaryColor) {
                        $css .= "h1, .h1, h2, .h2, h3, .h3, h4, .h4, .h5, .h5, h6, .h6 { color: {$primaryColor} !important; }\n";
                    }

                    if ($secondaryColor) {
                        $css .= "a { color: {$secondaryColor} !important; }\n";
                        $css .= "a:hover { color: " . $this->lighten_color($secondaryColor, 15) . " !important; transform: translateY(-2px); }\n";
                        $css .= "a:active { transform: translateY(-1px); transition: transform 0.1s; }\n";
                    }

                    // 2. NAVBAR ESPECÍFICO - Implementando estilos de clickpoint.scss
                    if ($primaryColor && $secondaryColor) {
                        // Borde inferior con gradiente
                        $css .= ".navbar { 
                            border-bottom: 5px solid !important; 
                            border-image: linear-gradient(to right, {$secondaryColor}, {$primaryColor}, {$secondaryColor}) 20% !important;
                            box-shadow: 0 2px 3px rgba(255, 255, 255, 0.5) !important;
                        }\n";
                        
                        // Estilos de navegación principal
                        $css .= ".navbar .primary-navigation .nav-link, .navbar .primary-navigation .dropdown-toggle, 
                                .navbar #usernavigation .nav-link, .navbar #usernavigation .dropdown-toggle { 
                                    color: {$primaryColor} !important; 
                                    font-weight: bold !important;
                                    transition: color 0.3s, transform 0.3s, text-shadow 0.3s !important;
                                }\n";
                        
                        // Hover y focus estados
                        $css .= ".navbar .primary-navigation .nav-link:hover, .navbar .primary-navigation .dropdown-toggle:hover,
                                .navbar #usernavigation .nav-link:hover, .navbar #usernavigation .dropdown-toggle:hover,
                                .navbar .primary-navigation .nav-link:focus, .navbar .primary-navigation .dropdown-toggle:focus,
                                .navbar #usernavigation .nav-link:focus, .navbar #usernavigation .dropdown-toggle:focus { 
                                    color: {$secondaryColor} !important; 
                                    transform: translateY(-3px) !important;
                                    text-shadow: 0 2px 4px rgba({$this->hex2rgb($secondaryColor)}, 0.3) !important;
                                }\n";
                        
                        // Active state
                        $css .= ".navbar .primary-navigation .nav-link.active, .navbar .primary-navigation .dropdown-toggle.active, 
                                .navbar #usernavigation .nav-link.active, .navbar #usernavigation .dropdown-toggle.active { 
                                    color: {$secondaryColor} !important; 
                                }\n";
                        
                        $css .= ".navbar .primary-navigation .nav-link.active::before, .navbar .primary-navigation .dropdown-toggle.active::before, 
                                .navbar #usernavigation .nav-link.active::before, .navbar #usernavigation .dropdown-toggle.active::before { 
                                    content: \"\";
                                    position: absolute !important;
                                    bottom: 0 !important;
                                    left: 0 !important;
                                    width: 100% !important;
                                    height: 3px !important;
                                    background-color: {$secondaryColor} !important;
                                }\n";

                        // User initials style
                        $primaryDark = $this->darken_color($primaryColor, 10);
                        $css .= ".navbar .userinitials { 
                                    background-color: {$primaryDark} !important; 
                                    color: #FFFFFF !important;
                                    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), 
                                                box-shadow 0.3s ease,
                                                background-color 0.3s ease !important;
                                }\n";
                        
                        $css .= ".navbar .userinitials:hover { 
                                    transform: scale(1.1) rotate(5deg) !important;
                                    box-shadow: 0 4px 8px rgba({$this->hex2rgb($primaryDark)}, 0.4) !important;
                                    background-color: " . $this->lighten_color($primaryDark, 5) . " !important;
                                }\n";

                        // Botones nav-pills
                        $css .= ".nav-pills .nav-link.active, .nav-pills .show>.nav-link { 
                                    color: #FFFFFF !important; 
                                    background-color: {$primaryColor} !important; 
                                    transform: translateY(-1px) !important;
                                    box-shadow: 0 4px 10px rgba({$this->hex2rgb($primaryColor)}, 0.3) !important;
                                }\n";
                        
                        $css .= ".nav-pills .nav-link:hover:not(.active) {
                                    background-color: rgba({$this->hex2rgb($secondaryColor)}, 0.1) !important;
                                    transform: translateY(-2px) scale(1.02) !important;
                                }\n";
                    }

                    // 3. BOTONES
                    if ($primaryColor && $secondaryColor) {
                        // btn-primary con color secundario
                        $css .= ".btn-primary { 
                                    background-color: {$secondaryColor} !important; 
                                    border-color: {$secondaryColor} !important; 
                                    color: #FFFFFF !important; 
                                    position: relative !important;
                                    overflow: hidden !important;
                                    transition: background-color 0.3s ease, transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease !important;
                                }\n";
                        
                        $css .= ".btn-primary:hover { 
                                    background-color: {$primaryColor} !important; 
                                    border-color: {$primaryColor} !important; 
                                    color: #FFFFFF !important;
                                    transform: translateY(-3px) scale(1.03) !important;
                                    box-shadow: 0 5px 15px rgba({$this->hex2rgb($secondaryColor)}, 0.4) !important;
                                }\n";
                        
                        $css .= ".btn-primary:active {
                                    transform: translateY(-1px) scale(0.98) !important;
                                    box-shadow: 0 2px 5px rgba({$this->hex2rgb($secondaryColor)}, 0.3) !important;
                                    transition: transform 0.1s, box-shadow 0.1s !important;
                                }\n";

                        // btn-secondary con color primario
                        $css .= ".btn-secondary { 
                                    background-color: {$primaryColor} !important; 
                                    border-color: {$primaryColor} !important; 
                                    color: #FFFFFF !important;
                                    transition: background-color 0.3s ease, transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease !important;
                                }\n";
                        
                        $css .= ".btn-secondary:hover { 
                                    background-color: {$secondaryColor} !important; 
                                    border-color: {$secondaryColor} !important; 
                                    color: #FFFFFF !important;
                                    transform: translateY(-3px) scale(1.03) !important;
                                    box-shadow: 0 5px 15px rgba({$this->hex2rgb($primaryColor)}, 0.4) !important;
                                }\n";
                        
                        $css .= ".btn-secondary:active {
                                    transform: translateY(-1px) scale(0.98) !important;
                                    box-shadow: 0 2px 5px rgba({$this->hex2rgb($primaryColor)}, 0.3) !important;
                                    transition: transform 0.1s, box-shadow 0.1s !important;
                                }\n";

                        // Botones específicos con icon-no-margin
                        $css .= "button.btn.btn-primary.icon-no-margin.p-0 { 
                                    background-color: {$secondaryColor} !important; 
                                    border-color: #FFFFFF !important; 
                                    color: #FFFFFF !important;
                                    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
                                }\n";
                        
                        $css .= "button.btn.btn-primary.icon-no-margin.p-0:hover { 
                                    background-color: {$secondaryColor} !important; 
                                    border-color: #FFFFFF !important; 
                                    color: #FFFFFF !important;
                                    transform: scale(1.08) !important;
                                    box-shadow: 0 3px 6px rgba({$this->hex2rgb($secondaryColor)}, 0.3) !important;
                                }\n";
                        
                        $css .= "button.btn.btn-primary.icon-no-margin.p-0:active {
                                    transform: scale(0.95) !important;
                                    transition: transform 0.1s !important;
                                }\n";

                        // Asegurar que los iconos dentro de btn-primary sean blancos
                        $css .= ".btn-primary .fa, .btn-primary .edw-icon { color: #FFFFFF !important; }\n";
                    }

                    // 4. FOOTER
                    if ($primaryColor) {
                        $primaryDark = $this->darken_color($primaryColor, 10);
                        $css .= "#page-footer { background-color: {$primaryDark} !important; }\n";
                        $css .= "#top-footer { 
                                    background-color: {$primaryColor} !important; 
                                    color: #FFFFFF !important;
                                    position: relative !important;
                                }\n";
                        
                        $css .= "#top-footer:before {
                                    content: '';
                                    position: absolute !important;
                                    top: 0 !important;
                                    left: 0 !important;
                                    right: 0 !important;
                                    height: 4px !important;
                                    background: linear-gradient(to right, {$primaryColor}, {$secondaryColor}, {$primaryColor}) !important;
                                }\n";
                        
                        $css .= "#top-footer h3 { 
                                    color: #FFFFFF !important;
                                    margin-bottom: 15px !important;
                                    position: relative !important;
                                    display: inline-block !important;
                                    padding-bottom: 10px !important;
                                }\n";
                    }


                    // 5. COLORES DE FONDO
                    if ($mainColor) {
                        $lightBg = $this->lighten_color($mainColor, 5);
                        $css .= "body, #nav-drawer { background-color: {$mainColor} !important; }\n";
                        $css .= "#page-content { background-color: {$mainColor} !important; }\n";
                        $css .= "#block-region-side-pre > section > .card-body { background-color: {$lightBg} !important; }\n";
                    }
                    
                    // 6. ELEMENTOS DE BLOQUES (Card blocks)
                    $css .= ".block {
                                transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), 
                                            box-shadow 0.4s ease, 
                                            background-color 0.3s !important;
                            }\n";
                    
                    $css .= ".block:hover {
                                transform: translateY(-5px) scale(1.01) !important;
                                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15) !important;
                                z-index: 1 !important;
                            }\n";
                    
                    $css .= ".block:active {
                                transform: translateY(-2px) scale(0.99) !important;
                                transition: transform 0.1s, box-shadow 0.1s !important;
                            }\n";

                    // Incluir estilos personalizados de la empresa si existen
                    if (!empty($company->customcss)) {
                        $css .= "\n/* Custom Company CSS */\n";
                        $css .= $company->customcss . "\n";
                    }
                }
            } catch (\Exception $e) {
                // Silencioso en caso de error
                if (debugging()) {
                    $css .= "/* Error al obtener colores IOMAD: " . $e->getMessage() . " */\n";
                }
            }

            if (!empty($css)) {
                $output .= '<style>' . $css . '</style>';
            }
        }

        return $output;
    }

    /**
     * Renders the login form with company branding.
     *
     * @param \core_auth\output\login $form The renderable.
     * @return string
     */
    public function render_login(\core_auth\output\login $form) {
        global $SITE, $OUTPUT;

        $context = $form->export_for_template($this);

        // Prepara el mensaje de error, si lo hubiera.
        $context->errorformatted = $this->error_text($context->error);

        // Inyectamos el contexto de branding (logo y nombre de la compañía)
        $brandingContext = $this->get_branding_context();
        if ($brandingContext) {
            foreach ($brandingContext as $key => $value) {
                $context->$key = $value;
            }
        }

        // Si no se obtuvo el nombre de compañía, se usa el nombre completo del sitio.
        if (empty($context->companyname)) {
            $context->sitename = format_string($SITE->fullname, true, ['context' => \context_course::instance(SITEID)]);
        }

        // Limpieza final: eliminamos propiedades con valor null.
        foreach ($context as $key => $value) {
            if (is_null($value)) {
                unset($context->$key);
            }
        }

        // Renderiza la plantilla 'core/loginbox' con el contexto completo.
        return $this->render_from_template('core/loginbox', $context);
    }

    public function get_branding_context() {
        global $SITE, $DB;
        $context = [];

        // Primero intentamos obtener el logo de la compañía si IOMAD está disponible
        if (class_exists('\iomad') && class_exists('\company')) {
            try {
                $companyid = \iomad::get_my_companyid(\context_system::instance(), false);
                if (!empty($companyid)) {
                    // Obtener la información completa de la compañía
                    $company = $DB->get_record('company', array('id' => $companyid), '*');
                    if (!empty($company)) {
                        // Usar el método nativo de IOMAD para obtener el logo URL
                        $companyobj = new \company($company->id);
                        $logo_url = $companyobj->get_logo_url($company->id);
                        if (!empty($logo_url)) {
                            debugging("Logo de compañía encontrado: " . $logo_url, DEBUG_DEVELOPER);
                            $context['companylogourl'] = $logo_url;
                            $context['companyname'] = format_string($company->name);
                            // Si estamos en la página de login y hay un logo de compañía, devolvemos el contexto aquí
                            if ($this->page->pagelayout == 'login') {
                                $context['incontainer'] = true;
                                return $context;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                debugging('Error al obtener logo de compañía: ' . $e->getMessage(), DEBUG_DEVELOPER);
            }
        }

        // Manejo específico para la página de login (cuando no hay logo de compañía)
        if ($this->page->pagelayout == 'login') {
            $loginpanellogo = $this->get_theme_logo_url('loginpanellogo');
            if ($loginpanellogo) {
                $context['logourl'] = $loginpanellogo;
                $context['incontainer'] = true;
                return $context;
            }
        }

        // Lógica para el logo estándar del tema
        $logo = $this->get_theme_logo_url('logo');
        if (!empty($logo)) {
            $context['logourl'] = $logo;
        } else {
            // Logo por defecto si no hay ninguno configurado
            $context['logourl'] = $this->image_url('logo', 'theme');
        }

        // Agregar el logomini si está configurado
        $logomini = $this->get_theme_logo_url('logomini');
        if (!empty($logomini)) {
            $context['logominiurl'] = $logomini;
        }

        // Información del sitio como respaldo
        $context['sitename'] = format_string($SITE->shortname);

        return $context;
    }

    /**
     * Devuelve la URL del logo. Puede cambiarse para usar 'clickpoint' o 'remui' según tu preferencia.
     *
     * @param string $img
     * @return string|null
     */
    public function get_theme_logo_url($img)
    {
        $theme = theme_config::load('remui');
        return $theme->setting_file_url($img, $img);
    }

    public function standard_footer_html() {
        global $CFG, $USER;
    
        $output = parent::standard_footer_html();
        $theme = $this->get_theme_config();
    
        // Add chat widget if enabled and user is logged in
        if (!empty($this->page->theme->settings->cp_enable_chat) && isloggedin()) {
            $output .= $this->add_chat_widget();
        }
    
        // Add accessibility widget only if enabled and user is logged in
        if (isloggedin() && !empty($this->page->theme->settings->cp_accessibility_widget)) {
            $output .= '<script src="https://website-widgets.pages.dev/dist/sienna.min.js" defer></script>';
            debugging('Accessibility widget loaded for user ID: ' . $USER->id, DEBUG_DEVELOPER);
        }
    
        // Add copy paste prevention if enabled
        if (!empty($this->page->theme->settings->cp_copypaste_prevention)) {
            $this->add_copy_paste_prevention();
        }
    
        // Check if about text should be hidden
        if (isset($this->page->theme->settings->cp_hidefootersections) && 
            $this->page->theme->settings->cp_hidefootersections == 1) {
            $output .= '<style>
                body section#top-footer { 
                    display: none !important; 
                }
            </style>';
        }
    
        return $output;
    }
    
    /**
     * Sobrescribe el método full_header para mostrar avisos generales u otros estilos en el header.
     *
     * @return string
     */
    public function full_header()
    {
        global $CFG, $USER, $PAGE;
    
        $theme = theme_config::load('clickpoint');
        $output = '';
    
        // Ocultar secciones front page si está configurado
        if (!empty($theme->settings->cp_hidefrontpagesections)) {
            $output .= '<style>.frontpage-sections { display: none; }</style>';
        }
    
        // Aviso general (notice)
        if (!empty(trim($theme->settings->cp_generalnotice))) {
            $mode = $theme->settings->cp_generalnoticemode;
            // 'info' => alert-info, 'danger' => alert-danger, 'off' => sin aviso
            if ($mode === 'info') {
                $output .= '<div class="alert alert-info mt-4"><strong><i class="fa fa-info-circle"></i></strong> ' . $theme->settings->cp_generalnotice . '</div>';
            } else if ($mode === 'danger') {
                $output .= '<div class="alert alert-danger mt-4"><strong><i class="fa fa-warning"></i></strong> ' . $theme->settings->cp_generalnotice . '</div>';
            }
        }
    
        // Recordatorio para admin, si el aviso está en modo 'off'
        if (is_siteadmin() && (!empty($theme->settings->cp_generalnoticemode) && $theme->settings->cp_generalnoticemode === 'off')) {
            $output .= '<div class="alert mt-4"><a href="' . $CFG->wwwroot . '/admin/settings.php?section=themesettingclickpoint#theme_clickpoint">' .
                '<strong><i class="fa fa-edit"></i></strong> ' . get_string('generalnotice_create', 'theme_clickpoint') . '</a></div>';
        }
    
        // Validación de URL (por ejemplo, para sitios de prueba)
        if (!$this->check_allowed_urls()) {
            $popup_id = bin2hex(random_bytes(8));
            $output .= $this->show_unauthorized_access_overlay($popup_id);
        }
    
        // Continúa con el header normal.
        $output .= parent::full_header();
        return $output;
    }
    
    /**
     * Agrega el script de chat si está configurado en el tema (cp_enable_chat y cp_tawkto_embed_url).
     *
     * @return string HTML/JS del widget de chat
     */
    protected function add_chat_widget() {
        global $USER;
        
        if (empty($this->page->theme->settings->cp_tawkto_embed_url)) {
            return '';
        }
    
        // Sanitize user data
        $userData = [
            'name' => clean_param($USER->firstname . " " . $USER->lastname, PARAM_TEXT),
            'email' => clean_param($USER->email, PARAM_EMAIL),
            'username' => clean_param($USER->username, PARAM_USERNAME),
            'idnumber' => clean_param($USER->idnumber, PARAM_TEXT)
        ];
    
        return "<!--Start of Chat Script-->
        <script type=\"text/javascript\">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        Tawk_API.visitor = " . json_encode($userData) . ";
        Tawk_API.onLoad = function(){
            Tawk_API.setAttributes(" . json_encode($userData) . ", function(error){});
        };
        (function(){
            var s1=document.createElement(\"script\"),s0=document.getElementsByTagName(\"script\")[0];
            s1.async=true;
            s1.src='" . clean_param($this->page->theme->settings->cp_tawkto_embed_url, PARAM_URL) . "';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
        </script>
        <!--End of Chat Script-->";
    }
    
    /**
     * Agrega la lógica de prevención de Copy/Paste para roles específicos.
     */
    protected function add_copy_paste_prevention()
    {
        global $USER, $PAGE, $COURSE;
    
        $theme = theme_config::load('clickpoint');
        $restrictedroles = $theme->settings->cp_copypaste_roles;
    
        // Si no hay roles restringidos, no hacemos nada.
        if (empty($restrictedroles)) {
            return;
        }
    
        // Si es administrador/a, ignoramos la restricción
        if (is_siteadmin()) {
            return;
        }
    
        try {
            // Obtenemos el contexto para saber en qué curso o página estamos
            $context = null;
            if (!empty($COURSE->id) && $COURSE->id > 1) {
                // Contexto de un curso
                $context = \context_course::instance($COURSE->id);
            } else if (!empty($PAGE->context)) {
                // Si no es un curso, usamos el contexto de la página actual
                $context = $PAGE->context;
            }
    
            if (!$context) {
                return; // No hay contexto válido
            }
    
            // Convertimos a array si es string (por seguridad)
            if (!is_array($restrictedroles)) {
                $restrictedroles = explode(',', $restrictedroles);
            }
    
            // Obtenemos los roles del usuario en este contexto
            $userroles = get_user_roles($context, $USER->id);
            $hasrestrictedrole = false;
            foreach ($userroles as $role) {
                if (in_array($role->roleid, $restrictedroles)) {
                    $hasrestrictedrole = true;
                    break;
                }
            }
    
            // Si el usuario tiene algún rol restringido, aplicamos la prevención
            if (isloggedin() && $hasrestrictedrole) {
                // Llama a un módulo AMD con la lógica para bloquear copy/paste
                $PAGE->requires->js_call_amd('theme_clickpoint/prevent_copy_paste', 'init');
            }
        } catch (moodle_exception $e) {
            debugging('Error in copy/paste prevention: ' . $e->getMessage(), DEBUG_DEVELOPER);
            return;
        }
    }

    /**
     * Muestra un overlay de acceso no autorizado.
     * 
     * @param string $popup_id Un ID único para el div
     * @return string
     */
    protected function show_unauthorized_access_overlay($popup_id)
    {
        $output = '';
        $output .= '<style>
            #' . $popup_id . ' {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                background: rgba(0, 0, 0, 0.75) !important;
                z-index: 10000 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                pointer-events: auto !important;
            }
            html, body {
                overflow: hidden !important; /* Prevent scrolling on the whole page */
            }
        </style>';

        $output .= '<div id="' . $popup_id . '">';
        $output .= '<div style="padding: 20px; background: white; border: 1px solid #ccc; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">';
        $output .= '<h2 style="color: red;">' . get_string('unauthorized_access', 'theme_clickpoint') . '</h2>';
        $output .= '<p>' . get_string('unauthorized_access_msg', 'theme_clickpoint') . '</p>';
        $output .= '</div>';
        $output .= '</div>';

        // JS para bloquear devtools e interacción
        $output .= '<script type="text/javascript">
            document.addEventListener("keydown", function(event) {
                // Bloquea F12 y ctrl+shift+i / ctrl+shift+j
                if (event.keyCode == 123 || (event.ctrlKey && event.shiftKey && (event.keyCode == 73 || event.keyCode == 74))) {
                    event.preventDefault();
                    alert("' . get_string('devtools_access_disabled', 'theme_clickpoint') . '");
                    return false;
                }
            });
            setInterval(function() {
                if ((window.outerHeight - window.innerHeight) > 200 || (window.outerWidth - window.innerWidth) > 200) {
                    alert("' . get_string('devtools_access_disabled', 'theme_clickpoint') . '");
                }
            }, 1000);
            // Previene interacción en el resto de la página
            document.body.style.pointerEvents = "none";
            document.addEventListener("contextmenu", event => event.preventDefault());
            document.body.addEventListener("click", function(e) {
                e.stopPropagation();
                return false;
            }, true);
        </script>';

        return $output;
    }

    /**
     * Comprueba si la URL actual está en la lista de URLs permitidas.
     * @return bool True si la URL está permitida, False en caso contrario.
     */
    protected function check_allowed_urls()
    {
        global $CFG;
        $allowed_urls = [
            'https://clickpoint.orioncloud.com.co',
            'http://clickpoint.orioncloud.com.co',
            'https://clickpoint1.orioncloud.com.co',
            'http://clickpoint1.orioncloud.com.co',
            'https://iomad.orioncloud.com.co',
            'http://iomad.orioncloud.com.co',
            'https://iomad.localhost.com',
            'http://iomad.localhost.com'
        ];

        return in_array($CFG->wwwroot, $allowed_urls);
    }

    /**
     * Convierte un color hexadecimal a formato RGB
     *
     * @param string $hex Color en formato hexadecimal
     * @return string Valores RGB separados por comas
     */
    private function hex2rgb($hex) {
        $hex = ltrim($hex, '#');
        
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        
        return "$r, $g, $b";
    }

    /**
     * Oscurecer un color en formato hexadecimal
     *
     * @param string $hex Color en formato hexadecimal
     * @param int $percent Porcentaje de oscurecimiento
     * @return string Color oscurecido en formato hexadecimal
     */
    private function darken_color($hex, $percent) {
        return $this->adjust_brightness($hex, -$percent);
    }

    /**
     * Aclarar un color en formato hexadecimal
     *
     * @param string $hex Color en formato hexadecimal
     * @param int $percent Porcentaje de aclarado
     * @return string Color aclarado en formato hexadecimal
     */
    private function lighten_color($hex, $percent) {
        return $this->adjust_brightness($hex, $percent);
    }

    /**
     * Ajusta el brillo de un color hexadecimal
     *
     * @param string $hex Color en formato hexadecimal
     * @param int $steps Pasos de brillo (positivo para aclarar, negativo para oscurecer)
     * @return string Color ajustado en formato hexadecimal
     */
    private function adjust_brightness($hex, $steps) {
        // Elimina el # si existe
        $hex = ltrim($hex, '#');

        // Convierte a RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Ajusta el brillo
        $r = max(0, min(255, $r + $steps * 2.55));
        $g = max(0, min(255, $g + $steps * 2.55));
        $b = max(0, min(255, $b + $steps * 2.55));

        // Convierte de nuevo a hexadecimal
        return '#' . sprintf('%02x%02x%02x', $r, $g, $b);
    }
}