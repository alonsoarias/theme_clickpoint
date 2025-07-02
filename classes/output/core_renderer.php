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
 * Provides the core rendering functionality for the theme_clickpoint.
 *
 * This core renderer class extends theme_remui's core renderer, adding specific modifications
 * to enhance and customize the user interface for theme_clickpoint. Key functionalities include
 * customized login forms, theme settings integration, and dynamic handling of UI elements like
 * carousels and notices based on theme configurations.
 *
 * The renderer provides corporate-grade features including:
 * - IOMAD company branding integration
 * - Dynamic CSS generation based on company colors
 * - Enhanced security features with content protection
 * - Bifurcated unauthorized access messaging for different user types
 * - Chat widget integration for customer support
 * - Customizable headers and footers
 *
 * @package    theme_clickpoint
 * @category   output
 * @copyright  2024 Soporte ClickPoint <soporte@clickpoint.co>
 * @author     Pedro Alonso Arias Balcucho
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_clickpoint\output;

use theme_config;
use moodle_url;
use context_course;
use context_system;
use moodle_exception;
use company_user;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../remui/classes/output/core_renderer.php');
require_once(__DIR__ . '/../util/theme_settings.php');

/**
 * Core renderer for theme_clickpoint
 *
 * This class extends the core_renderer of the parent theme to customize
 * the rendering of various UI elements. It provides enhanced functionality
 * for corporate environments with IOMAD integration.
 *
 * @package    theme_clickpoint
 * @category   output
 * @copyright  2024 Soporte ClickPoint <soporte@clickpoint.co>
 * @author     Pedro Alonso Arias Balcucho
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_remui\output\core_renderer {
    
    /**
     * Cached theme config
     * @var object|null
     */
    protected $themeConfig = null;

    /**
     * Get theme config with caching
     * 
     * Retrieves and caches the theme configuration to avoid multiple database queries.
     * This method ensures efficient access to theme settings throughout the rendering process.
     *
     * @return object Theme configuration object
     */
    public function get_theme_config() {
        if ($this->themeConfig === null) {
            $this->themeConfig = theme_config::load('clickpoint');
        }
        return $this->themeConfig;
    }
    
    /**
     * Outputs standard head HTML to add required meta tags and CSS for IOMAD.
     *
     * This method extends the parent's standard_head_html to include IOMAD company-specific
     * styling. It dynamically generates CSS based on company colors and branding when
     * IOMAD is available and a company context is detected.
     *
     * Features:
     * - Automatic company color detection from IOMAD
     * - Dynamic CSS generation for company branding
     * - Fallback styles when company information is not available
     * - Support for custom company CSS
     *
     * @return string HTML fragment containing head elements
     */
    public function standard_head_html() {
        global $CFG, $DB;
    
        // Get base head HTML from parent method.
        $output = parent::standard_head_html();
        
        try {
            // Check if IOMAD is available.
            if (class_exists('\iomad') && class_exists('\company')) {
                $companyid = \iomad::get_my_companyid(\context_system::instance(), false);
                
                if ($companyid && $company = $DB->get_record('company', array('id' => $companyid))) {
                    // Company colors (use defaults if not defined).
                    $primaryColor = !empty($company->headingcolor) ? $company->headingcolor : '#1D4356';
                    $secondaryColor = !empty($company->linkcolor) ? $company->linkcolor : '#FF5E01';
                    $textColor = !empty($company->textcolor) ? $company->textcolor : '#706565';
                    $backgroundColor = !empty($company->maincolor) ? $company->maincolor : '#FFFFFF';
                    
                    // Verify class exists before using it.
                    $companyStylesFile = $CFG->dirroot . '/theme/clickpoint/classes/util/company_styles.php';
                    if (file_exists($companyStylesFile)) {
                        require_once($companyStylesFile);
                        
                        // Generate custom CSS for company.
                        if (class_exists('\\theme_clickpoint\\util\\company_styles')) {
                            $companyCSS = \theme_clickpoint\util\company_styles::get_company_css(
                                $primaryColor,
                                $secondaryColor,
                                $textColor,
                                $backgroundColor
                            );
                            
                            // Add company's custom CSS if available.
                            if (!empty($company->customcss)) {
                                $companyCSS .= "\n" . $company->customcss;
                            }
                            
                            // Insert generated CSS in head.
                            $output .= "\n<style id='company-styles'>\n" . $companyCSS . "\n</style>";
                            
                            debugging('Company styles applied for company ID: ' . $companyid, DEBUG_DEVELOPER);
                        } else {
                            debugging('Company styles class exists but could not be loaded', DEBUG_DEVELOPER);
                        }
                    } else {
                        // Fallback: use traditional approach if we don't have the class.
                        $css = '';
                        
                        // Company link color.
                        if ($linkcolor = $company->linkcolor) {
                            $css .= 'a {color: ' . $linkcolor . '} ';
                        }
                        
                        // Company heading color.
                        if ($headingcolor = $company->headingcolor) {
                            $css .= '.navbar {background-color: ' . $headingcolor . '!important} ';
                            $css .= 'h1, h2, h3, h4, h5, h6 {color: ' . $headingcolor . '} ';
                        }
                        
                        // Company main color.
                        if ($maincolor = $company->maincolor) {
                            $css .= 'body, #nav-drawer {background-color: ' . $maincolor . '!important} ';
                        }
                        
                        // Company custom CSS.
                        if (!empty($company->customcss)) {
                            $css .= $company->customcss;
                        }
                        
                        if (!empty($css)) {
                            $output .= "\n<style id='company-styles-fallback'>\n" . $css . "\n</style>";
                            debugging('Applied fallback company styles for company ID: ' . $companyid, DEBUG_DEVELOPER);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            debugging('Error applying company styles: ' . $e->getMessage(), DEBUG_DEVELOPER);
        }
        
        return $output;
    }

    /**
     * Renders the login form with company branding.
     *
     * This method customizes the login form to include company-specific branding
     * when available through IOMAD integration. It handles both company logos
     * and standard site logos with appropriate fallbacks.
     *
     * @param \core_auth\output\login $form The renderable login form
     * @return string HTML output for the login form
     */
    public function render_login(\core_auth\output\login $form) {
        global $SITE, $OUTPUT;

        $context = $form->export_for_template($this);

        // Prepare error message if present.
        $context->errorformatted = $this->error_text($context->error);

        // Inject branding context (logo and company name).
        $brandingContext = $this->get_branding_context();
        if ($brandingContext) {
            foreach ($brandingContext as $key => $value) {
                $context->$key = $value;
            }
        }

        // If no company name was obtained, use the site's full name.
        if (empty($context->companyname)) {
            $context->sitename = format_string($SITE->fullname, true, ['context' => \context_course::instance(SITEID)]);
        }

        // Final cleanup: remove properties with null values.
        foreach ($context as $key => $value) {
            if (is_null($value)) {
                unset($context->$key);
            }
        }

        // Render 'core/loginbox' template with the complete context.
        return $this->render_from_template('core/loginbox', $context);
    }

    /**
     * Gets branding context for templates.
     * 
     * This method determines the appropriate branding information to use for
     * the current context, prioritizing company logos from IOMAD when available,
     * then falling back to theme-configured logos.
     *
     * @return array Context containing branding information including logos and company names
     */
    public function get_branding_context() {
        global $SITE, $DB;
        $context = [];

        // First try to get company logo if IOMAD is available.
        if (class_exists('\iomad') && class_exists('\company')) {
            try {
                $companyid = \iomad::get_my_companyid(\context_system::instance(), false);
                if (!empty($companyid)) {
                    // Get complete company information.
                    $company = $DB->get_record('company', array('id' => $companyid), '*');
                    if (!empty($company)) {
                        // Use IOMAD's native method to get logo URL.
                        $companyobj = new \company($company->id);
                        $logo_url = $companyobj->get_logo_url($company->id);
                        if (!empty($logo_url)) {
                            debugging("Company logo found: " . $logo_url, DEBUG_DEVELOPER);
                            $context['companylogourl'] = $logo_url;
                            $context['companyname'] = format_string($company->name);
                            // If we're on the login page and have a company logo, return context here.
                            if ($this->page->pagelayout == 'login') {
                                $context['incontainer'] = true;
                                return $context;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                debugging('Error getting company logo: ' . $e->getMessage(), DEBUG_DEVELOPER);
            }
        }

        // Special handling for login page (when no company logo).
        if ($this->page->pagelayout == 'login') {
            $loginpanellogo = $this->get_theme_logo_url('loginpanellogo');
            if ($loginpanellogo) {
                $context['logourl'] = $loginpanellogo;
                $context['incontainer'] = true;
                return $context;
            }
        }

        // Logic for standard theme logo.
        $logo = $this->get_theme_logo_url('logo');
        if (!empty($logo)) {
            $context['logourl'] = $logo;
        } else {
            // Default logo if none configured.
            $context['logourl'] = $this->image_url('logo', 'theme');
        }

        // Add logomini if configured.
        $logomini = $this->get_theme_logo_url('logomini');
        if (!empty($logomini)) {
            $context['logominiurl'] = $logomini;
        }

        // Site info as fallback.
        $context['sitename'] = format_string($SITE->shortname);

        return $context;
    }

    /**
     * Returns the URL of the logo to use.
     *
     * This method retrieves the URL for a specific logo type from the theme settings.
     * It uses the RemUI theme's settings system to access stored logo files.
     *
     * @param string $img Logo identifier (e.g., 'logo', 'logomini', 'loginpanellogo')
     * @return string|null Logo URL or null if not found
     */
    public function get_theme_logo_url($img) {
        $theme = theme_config::load('remui');
        return $theme->setting_file_url($img, $img);
    }

    /**
     * Generates standard footer HTML with additional elements.
     *
     * This method extends the parent footer to include ClickPoint-specific features
     * such as chat widgets, copy-paste prevention, and custom footer visibility controls.
     *
     * @return string HTML footer content with enhanced features
     */
    public function standard_footer_html() {
        global $CFG, $USER;
    
        $output = parent::standard_footer_html();
        $theme = $this->get_theme_config();
    
        // Add chat widget if enabled and user is logged in.
        if (!empty($this->page->theme->settings->cp_enable_chat) && isloggedin()) {
            $output .= $this->add_chat_widget();
        }
    
        // Add copy paste prevention if enabled.
        if (!empty($this->page->theme->settings->cp_copypaste_prevention)) {
            $this->add_copy_paste_prevention();
        }
    
        // Check if about text should be hidden.
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
     * Generates full header with notices if configured.
     *
     * This method extends the parent header to include ClickPoint-specific notices,
     * front page section visibility controls, and URL validation for authorized access.
     *
     * @return string Header HTML with enhanced features
     */
    public function full_header() {
        global $CFG, $USER, $PAGE;
    
        $theme = theme_config::load('clickpoint');
        $output = '';
    
        // Hide front page sections if configured.
        if (!empty($theme->settings->cp_hidefrontpagesections)) {
            $output .= '<style>.frontpage-sections { display: none; }</style>';
        }
    
        // General notice (info or warning).
        if (!empty(trim($theme->settings->cp_generalnotice))) {
            $mode = $theme->settings->cp_generalnoticemode;
            // 'info' => alert-info, 'danger' => alert-danger, 'off' => no notice.
            if ($mode === 'info') {
                $output .= '<div class="alert alert-info mt-4"><strong><i class="fa fa-info-circle"></i></strong> ' . $theme->settings->cp_generalnotice . '</div>';
            } else if ($mode === 'danger') {
                $output .= '<div class="alert alert-danger mt-4"><strong><i class="fa fa-warning"></i></strong> ' . $theme->settings->cp_generalnotice . '</div>';
            }
        }
    
        // Reminder for admin if notice is in 'off' mode.
        if (is_siteadmin() && (!empty($theme->settings->cp_generalnoticemode) && $theme->settings->cp_generalnoticemode === 'off')) {
            $output .= '<div class="alert mt-4"><a href="' . $CFG->wwwroot . '/admin/settings.php?section=themesettingclickpoint#theme_clickpoint">' .
                '<strong><i class="fa fa-edit"></i></strong> ' . get_string('generalnotice_create', 'theme_clickpoint') . '</a></div>';
        }
    
        // URL validation (e.g., for test sites).
        if (!$this->check_allowed_urls()) {
            $popup_id = bin2hex(random_bytes(8));
            $output .= $this->show_unauthorized_access_overlay($popup_id);
        }
    
        // Continue with normal header.
        $output .= parent::full_header();
        return $output;
    }
    
    /**
     * Adds chat widget script if configured.
     *
     * This method generates the Tawk.to chat widget integration code when enabled.
     * It includes user information for a personalized chat experience.
     *
     * @return string HTML/JS for chat widget integration
     */
    protected function add_chat_widget() {
        global $USER;
        
        if (empty($this->page->theme->settings->cp_tawkto_embed_url)) {
            return '';
        }
    
        // Sanitize user data.
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
     * Adds copy/paste prevention logic for specific roles.
     *
     * This method implements content protection by preventing copy/paste operations
     * for users with specific roles. It uses JavaScript to block common keyboard
     * shortcuts and developer tools access.
     *
     * @return void
     */
    protected function add_copy_paste_prevention() {
        global $USER, $PAGE, $COURSE;
    
        $theme = theme_config::load('clickpoint');
        $restrictedroles = $theme->settings->cp_copypaste_roles;
    
        // If no restricted roles, do nothing.
        if (empty($restrictedroles)) {
            return;
        }
    
        // If site admin, ignore restriction.
        if (is_siteadmin()) {
            return;
        }
    
        try {
            // Get context to determine what course or page we're on.
            $context = null;
            if (!empty($COURSE->id) && $COURSE->id > 1) {
                // Course context.
                $context = \context_course::instance($COURSE->id);
            } else if (!empty($PAGE->context)) {
                // If not a course, use current page context.
                $context = $PAGE->context;
            }
    
            if (!$context) {
                return; // No valid context.
            }
    
            // Convert to array if string (for safety).
            if (!is_array($restrictedroles)) {
                $restrictedroles = explode(',', $restrictedroles);
            }
    
            // Get user roles in this context.
            $userroles = get_user_roles($context, $USER->id);
            $hasrestrictedrole = false;
            foreach ($userroles as $role) {
                if (in_array($role->roleid, $restrictedroles)) {
                    $hasrestrictedrole = true;
                    break;
                }
            }
    
            // If user has a restricted role, apply prevention.
            if (isloggedin() && $hasrestrictedrole) {
                // Call AMD module with logic to block copy/paste.
                $PAGE->requires->js_call_amd('theme_clickpoint/prevent_copy_paste', 'init');
            }
        } catch (moodle_exception $e) {
            debugging('Error in copy/paste prevention: ' . $e->getMessage(), DEBUG_DEVELOPER);
            return;
        }
    }

    /**
     * Displays unauthorized access overlay with bifurcated messaging.
     * 
     * This method creates a full-screen overlay that blocks access to the site
     * when the current URL is not in the authorized list. It provides different
     * messages for administrators and regular users to maintain appropriate
     * user experience while providing technical details to administrators.
     *
     * Features:
     * - Bifurcated messaging (admin vs user)
     * - Full-screen overlay with interaction blocking
     * - Developer tools prevention
     * - Context-sensitive messaging
     * 
     * @param string $popup_id Unique ID for the overlay element
     * @return string HTML for the unauthorized access overlay
     */
    protected function show_unauthorized_access_overlay($popup_id) {
        global $USER;
        
        $output = '';
        
        // CSS for the overlay.
        $output .= '<style>
            #' . $popup_id . ' {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                background: rgba(0, 0, 0, 0.85) !important;
                z-index: 10000 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                pointer-events: auto !important;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }
            html, body {
                overflow: hidden !important;
            }
            #' . $popup_id . ' .overlay-content {
                padding: 30px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.3);
                max-width: 600px;
                margin: 20px;
                text-align: center;
                border-left: 4px solid #e74c3c;
            }
            #' . $popup_id . ' h2 {
                color: #e74c3c;
                margin-bottom: 15px;
                font-size: 1.5em;
            }
            #' . $popup_id . ' p {
                color: #2c3e50;
                line-height: 1.6;
                margin-bottom: 10px;
            }
            #' . $popup_id . ' .admin-info {
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 4px;
                padding: 15px;
                margin-top: 15px;
                font-size: 0.9em;
                color: #495057;
            }
        </style>';

        // Determine if user is site administrator.
        $isAdmin = is_siteadmin($USER);
        
        // Get appropriate message strings based on user type.
        if ($isAdmin) {
            $title = get_string('unauthorized_access_admin', 'theme_clickpoint');
            $message = get_string('unauthorized_access_admin_msg', 'theme_clickpoint');
        } else {
            $title = get_string('unauthorized_access_user', 'theme_clickpoint');
            $message = get_string('unauthorized_access_user_msg', 'theme_clickpoint');
        }

        // Build the overlay HTML.
        $output .= '<div id="' . $popup_id . '">';
        $output .= '<div class="overlay-content">';
        $output .= '<h2>' . $title . '</h2>';
        $output .= '<p>' . $message . '</p>';
        
        // Add additional technical information for administrators.
        if ($isAdmin) {
            global $CFG;
            $output .= '<div class="admin-info">';
            $output .= '<strong>Technical Information:</strong><br>';
            $output .= 'Current URL: ' . htmlspecialchars($CFG->wwwroot) . '<br>';
            $output .= 'Please check the authorized URLs configuration in the theme settings.';
            $output .= '</div>';
        }
        
        $output .= '</div>';
        $output .= '</div>';

        // JavaScript to block interaction and developer tools.
        $devToolsMessage = get_string('devtools_access_disabled', 'theme_clickpoint');
        $output .= '<script type="text/javascript">
            (function() {
                "use strict";
                
                // Block common developer tools shortcuts.
                document.addEventListener("keydown", function(event) {
                    // F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+Shift+C.
                    if (event.keyCode === 123 || 
                        (event.ctrlKey && event.shiftKey && (event.keyCode === 73 || event.keyCode === 74 || event.keyCode === 67)) ||
                        (event.ctrlKey && event.keyCode === 85)) {
                        event.preventDefault();
                        alert("' . addslashes($devToolsMessage) . '");
                        return false;
                    }
                }, false);
                
                // Monitor for developer tools opening (basic detection).
                var devtools = {
                    open: false,
                    orientation: null
                };
                
                var threshold = 160;
                setInterval(function() {
                    if (window.outerHeight - window.innerHeight > threshold || 
                        window.outerWidth - window.innerWidth > threshold) {
                        if (!devtools.open) {
                            devtools.open = true;
                            alert("' . addslashes($devToolsMessage) . '");
                        }
                    } else {
                        devtools.open = false;
                    }
                }, 500);
                
                // Prevent interaction with the rest of the page.
                document.body.style.pointerEvents = "none";
                document.addEventListener("contextmenu", function(e) {
                    e.preventDefault();
                    return false;
                }, false);
                
                document.body.addEventListener("click", function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    return false;
                }, true);
                
                // Re-enable pointer events only for the overlay.
                var overlay = document.getElementById("' . $popup_id . '");
                if (overlay) {
                    overlay.style.pointerEvents = "auto";
                }
            })();
        </script>';

        return $output;
    }

    /**
     * Checks if current URL is in allowed URLs list.
     * 
     * This method validates whether the current site URL is authorized to run
     * the ClickPoint theme. This provides an additional security layer for
     * deployment control and license compliance.
     *
     * @return bool True if URL is allowed, False otherwise
     */
    protected function check_allowed_urls() {
        global $CFG;
        
        $allowed_urls = [
            'https://clickpoint.orioncloud.com.co',
            'http://clickpoint.orioncloud.com.co',
            'https://clickpoint.localhost.com',
            'http://clickpoint.localhost.com',
            'https://iomad.orioncloud.com.co',
            'http://iomad.orioncloud.com.co',
            'https://iomad.localhost.com',
            'http://iomad.localhost.com'
        ];

        return in_array($CFG->wwwroot, $allowed_urls);
    }
}