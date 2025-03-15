<?php

namespace theme_clickpoint\util;

use theme_config;

/**
 * Utility class for theme settings specifically for handling footer settings and personal area header.
 */
class settings {
    /**
     * @var stdClass The theme configuration object.
     */
    protected $theme;

    /**
     * Constructor that loads the current theme configuration.
     */
    public function __construct() {
        $this->theme = theme_config::load('clickpoint');
    }

    /**
     * Retrieves footer settings for the theme.
     *
     * This method gathers the 'my_credit' and 'abouttext' settings from the theme configuration
     * and prepares them for use in the footer template.
     *
     * @return array Context for the footer template with settings data.
     */
    public function footer() {
        $templatecontext = [];

        // Retrieve 'my_credit' from the theme settings or use a default value if not set.
        $templatecontext['my_credit'] = get_string('credit', 'theme_clickpoint');

        // Retrieve 'abouttext' from the theme settings or use a default value if not set.
        $templatecontext['abouttitle'] = !empty($this->page->theme->settings->cp_abouttitle) ? $this->page->theme->settings->cp_abouttitle : get_string('abouttitle_default', 'theme_clickpoint');
        $templatecontext['abouttext'] = !empty($this->page->theme->settings->cp_abouttext) ? $this->page->theme->settings->cp_abouttext : get_string('abouttext_default', 'theme_clickpoint');

        return $templatecontext;
    }

    /**
     * Retrieves personal area header settings for the theme.
     *
     * This method gathers the 'personalareaheader' setting from the theme configuration
     * and prepares it for use in the personal area header template.
     *
     * @return array Context for the personal area header template with settings data.
     */
    public function personal_area_header() {
        $templatecontext = [];

        // Check if personal area header should be shown
        $showheader = !empty($this->page->theme->settings->cp_show_personalareaheader) && 
            $this->page->theme->settings->cp_show_personalareaheader == '1';
        
        if ($showheader) {
            $personalareaheader = $this->theme->setting_file_url('cp_personalareaheader', 'cp_personalareaheader');
            if (!empty($personalareaheader)) {
                $templatecontext['headerimage'] = [
                    'url' => $personalareaheader,
                    'title' => get_string('personalareaheader', 'theme_inteb'),
                    'show' => true
                ];
            } else {
                $templatecontext['headerimage'] = [
                    'url' => '',
                    'title' => get_string('defaultheader', 'theme_inteb'),
                    'show' => false
                ];
            }
        } else {
            $templatecontext['headerimage'] = [
                'show' => false
            ];
        }

        return $templatecontext;
    }

    /**
     * Retrieves my courses header settings for the theme.
     *
     * This method gathers the 'mycoursesheader' setting from the theme configuration
     * and prepares it for use in the my courses header template.
     *
     * @return array Context for the my courses header template with settings data.
     */
    public function my_courses_header() {
        $templatecontext = [];

        // Check if my courses header should be shown
        $showheader = !empty($this->page->theme->settings->cp_show_mycoursesheader) && 
            $this->page->theme->settings->cp_show_mycoursesheader == '1';
        
        if ($showheader) {
            $mycoursesheader = $this->theme->setting_file_url('cp_mycoursesheader', 'cp_mycoursesheader');
            if (!empty($mycoursesheader)) {
                $templatecontext['headerimage'] = [
                    'url' => $mycoursesheader,
                    'title' => get_string('mycoursesheader', 'theme_inteb'),
                    'show' => true
                ];
            } else {
                $templatecontext['headerimage'] = [
                    'url' => '',
                    'title' => get_string('defaultheader', 'theme_inteb'),
                    'show' => false
                ];
            }
        } else {
            $templatecontext['headerimage'] = [
                'show' => false
            ];
        }

        return $templatecontext;
    }
}