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
 * Company-specific style generator.
 *
 * @package     theme_clickpoint
 * @copyright   2025 Soporte clickpoint <soporte@clickpoint.co>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_clickpoint\util;

defined('MOODLE_INTERNAL') || die();

/**
 * Clase para generar estilos CSS específicos por compañía
 * 
 * Esta clase genera una hoja de estilos completa basada en los colores
 * primario y secundario de una compañía, derivando una paleta completa
 * y aplicando los estilos a todos los elementos del tema.
 */
class company_styles {
    /** @var string Color primario (azul oscuro) */
    private $primary_color;
    
    /** @var string Color secundario (naranja) */
    private $secondary_color;
    
    /** @var string Color terciario (naranja claro) - derivado */
    private $tertiary_color;
    
    /** @var string Color complementario (azul medio) - derivado */
    private $medium_blue_color;
    
    /** @var string Color de texto */
    private $text_color;
    
    /** @var string Color de fondo */
    private $background_color;
    
    /** @var array Paleta completa de colores */
    private $color_palette;
    
    /** @var bool Indica si se ha inicializado la paleta */
    private $is_initialized = false;

    /**
     * Constructor
     * 
     * @param string $primary Color primario en formato hexadecimal (ej. #1D4356)
     * @param string $secondary Color secundario en formato hexadecimal (ej. #FF5E01)
     * @param string $text Color del texto en formato hexadecimal (opcional)
     * @param string $background Color de fondo en formato hexadecimal (opcional)
     */
    public function __construct($primary = '#1D4356', $secondary = '#FF5E01', $text = '#706565', $background = '#FFFFFF') {
        $this->primary_color = $this->ensure_hash($primary);
        $this->secondary_color = $this->ensure_hash($secondary);
        $this->text_color = $this->ensure_hash($text);
        $this->background_color = $this->ensure_hash($background);
        
        // Generar colores derivados
        $this->tertiary_color = $this->generate_tertiary($this->secondary_color);
        $this->medium_blue_color = $this->generate_complementary($this->primary_color);
        
        // Inicializar paleta completa
        $this->init_color_palette();
    }
    
    /**
     * Asegura que un color tenga el formato correcto con hash (#)
     * 
     * @param string $color Color en formato hexadecimal
     * @return string Color formateado
     */
    private function ensure_hash($color) {
        if (empty($color)) {
            return '';
        }
        
        $color = trim($color);
        if ($color[0] !== '#') {
            $color = '#' . $color;
        }
        
        return $color;
    }
    
    /**
     * Genera una versión más clara de un color
     * 
     * @param string $color Color en formato hexadecimal
     * @param int $percent Porcentaje de aclarado (0-100)
     * @return string Color aclarado en formato hexadecimal
     */
    private function lighten_color($color, $percent) {
        if (empty($color)) {
            return '';
        }
        
        // Eliminar el # si existe
        $color = ltrim($color, '#');
        
        // Convertir a RGB
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
        
        // Aclarar cada componente
        $r = min(255, $r + round(255 * $percent / 100));
        $g = min(255, $g + round(255 * $percent / 100));
        $b = min(255, $b + round(255 * $percent / 100));
        
        // Convertir de nuevo a hexadecimal
        return '#' . sprintf('%02x%02x%02x', $r, $g, $b);
    }
    
    /**
     * Genera una versión más oscura de un color
     * 
     * @param string $color Color en formato hexadecimal
     * @param int $percent Porcentaje de oscurecimiento (0-100)
     * @return string Color oscurecido en formato hexadecimal
     */
    private function darken_color($color, $percent) {
        if (empty($color)) {
            return '';
        }
        
        // Eliminar el # si existe
        $color = ltrim($color, '#');
        
        // Convertir a RGB
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
        
        // Oscurecer cada componente
        $r = max(0, $r - round(255 * $percent / 100));
        $g = max(0, $g - round(255 * $percent / 100));
        $b = max(0, $b - round(255 * $percent / 100));
        
        // Convertir de nuevo a hexadecimal
        return '#' . sprintf('%02x%02x%02x', $r, $g, $b);
    }
    
    /**
     * Genera un color con opacidad
     * 
     * @param string $color Color en formato hexadecimal
     * @param float $opacity Valor de opacidad (0.0 - 1.0)
     * @return string Color con opacidad en formato rgba()
     */
    private function add_opacity($color, $opacity) {
        if (empty($color)) {
            return '';
        }
        
        // Eliminar el # si existe
        $color = ltrim($color, '#');
        
        // Convertir a RGB
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
        
        // Retornar como rgba
        return "rgba({$r}, {$g}, {$b}, {$opacity})";
    }
    
    /**
     * Genera un color terciario (naranja claro) a partir del color secundario
     * 
     * @param string $secondary_color Color secundario en formato hexadecimal
     * @return string Color terciario en formato hexadecimal
     */
    private function generate_tertiary($secondary_color) {
        if (empty($secondary_color)) {
            return '#FF9A00'; // Color terciario por defecto
        }
        
        // Aclarar y ajustar ligeramente el tono
        return $this->lighten_color($secondary_color, 10);
    }
    
    /**
     * Genera un color complementario (azul medio) a partir del color primario
     * 
     * @param string $primary_color Color primario en formato hexadecimal
     * @return string Color complementario en formato hexadecimal
     */
    private function generate_complementary($primary_color) {
        if (empty($primary_color)) {
            return '#3D91C0'; // Color complementario por defecto
        }
        
        // Aclarar y saturar ligeramente
        return $this->lighten_color($primary_color, 25);
    }
    
    /**
     * Inicializa la paleta completa de colores
     */
    private function init_color_palette() {
        if ($this->is_initialized) {
            return;
        }
        
        // Colores base
        $this->color_palette = [
            // Colores principales
            'cp-primary' => $this->primary_color,
            'cp-primary-light' => $this->lighten_color($this->primary_color, 10),
            'cp-primary-dark' => $this->darken_color($this->primary_color, 10),
            
            'cp-secondary' => $this->secondary_color,
            'cp-secondary-light' => $this->lighten_color($this->secondary_color, 10),
            'cp-secondary-dark' => $this->darken_color($this->secondary_color, 10),
            
            'cp-tertiary' => $this->tertiary_color,
            'cp-tertiary-light' => $this->lighten_color($this->tertiary_color, 10),
            'cp-tertiary-dark' => $this->darken_color($this->tertiary_color, 10),
            
            'cp-medium-blue' => $this->medium_blue_color,
            
            // Colores con opacidad
            'cp-primary-a90' => $this->add_opacity($this->primary_color, 0.9),
            'cp-primary-a75' => $this->add_opacity($this->primary_color, 0.75),
            'cp-primary-a50' => $this->add_opacity($this->primary_color, 0.5),
            'cp-primary-a25' => $this->add_opacity($this->primary_color, 0.25),
            'cp-primary-a10' => $this->add_opacity($this->primary_color, 0.1),
            
            'cp-secondary-a90' => $this->add_opacity($this->secondary_color, 0.9),
            'cp-secondary-a75' => $this->add_opacity($this->secondary_color, 0.75),
            'cp-secondary-a50' => $this->add_opacity($this->secondary_color, 0.5),
            'cp-secondary-a25' => $this->add_opacity($this->secondary_color, 0.25),
            'cp-secondary-a10' => $this->add_opacity($this->secondary_color, 0.1),
            
            // Colores de estado
            'cp-success' => '#7AAA18',
            'cp-info' => $this->medium_blue_color,
            'cp-warning' => $this->tertiary_color,
            'cp-danger' => $this->secondary_color,
            
            // Colores de texto y fondo
            'cp-text' => $this->text_color,
            'cp-background' => $this->background_color,
            'cp-white' => '#FFFFFF',
            
            // Colores para modo oscuro
            'cp-dm-primary' => $this->darken_color($this->primary_color, 20),
            'cp-dm-secondary' => $this->darken_color($this->secondary_color, 5),
            'cp-dm-tertiary' => $this->darken_color($this->tertiary_color, 10),
            'cp-dm-medium-blue' => $this->darken_color($this->medium_blue_color, 20),
            'cp-dm-text' => '#E0E0E0',
            'cp-dm-card-bg' => '#1E1E1E',
            'cp-dm-bg' => '#121212',
            'cp-dm-border' => '#383838',
        ];
        
        $this->is_initialized = true;
    }
    
    /**
     * Genera estilos CSS para el modo claro
     * 
     * @return string CSS para modo claro
     */
    private function generate_light_mode_css() {
        $palette = $this->color_palette;
        
        $css = "
        /* Estilos generados para compañía - Modo Claro */
        :root {
            --cp-primary: {$palette['cp-primary']};
            --cp-primary-light: {$palette['cp-primary-light']};
            --cp-primary-dark: {$palette['cp-primary-dark']};
            --cp-secondary: {$palette['cp-secondary']};
            --cp-secondary-light: {$palette['cp-secondary-light']};
            --cp-secondary-dark: {$palette['cp-secondary-dark']};
            --cp-tertiary: {$palette['cp-tertiary']};
            --cp-medium-blue: {$palette['cp-medium-blue']};
            --cp-success: {$palette['cp-success']};
            --cp-info: {$palette['cp-info']};
            --cp-warning: {$palette['cp-warning']};
            --cp-danger: {$palette['cp-danger']};
            --cp-text: {$palette['cp-text']};
            --cp-background: {$palette['cp-background']};
        }
        
        /* Encabezados */
        h1, .h1, h2, .h2, h3, .h3, h4, .h4, .h5, .h5, h6, .h6 {
            color: {$palette['cp-primary']};
        }
        
        h1.h2.header-heading {
            font-weight: bold !important;
        }
        
        /* Enlaces */
        a {
            color: {$palette['cp-medium-blue']};
        }
        
        a:hover {
            color: {$palette['cp-tertiary']};
        }
        
        /* Navegación */
        .navbar {
            border-bottom: 5px solid {$palette['cp-primary']};
            box-shadow: 0px 2px 3px rgba(255, 255, 255, 0.5);
        }
        
        .navbar .primary-navigation .nav-link,
        .navbar .primary-navigation .more-nav>.dropdown>.dropdown-toggle,
        .navbar #usernavigation .nav-link,
        .navbar #usernavigation .more-nav>.dropdown>.dropdown-toggle {
            color: {$palette['cp-primary']};
        }
        
        .navbar .primary-navigation .nav-link.active {
            color: {$palette['cp-primary']};
        }
        
        .navbar .primary-navigation .nav-link.active::before {
            border-bottom-color: {$palette['cp-primary']};
        }
        
        .navbar .primary-navigation .nav-link:hover,
        .navbar .primary-navigation .more-nav>.dropdown>.dropdown-toggle:hover,
        .navbar #usernavigation .nav-link:hover,
        .navbar #usernavigation .more-nav>.dropdown>.dropdown-toggle:hover {
            color: {$palette['cp-primary']};
        }
        
        .navbar .primary-navigation .nav-link.active::before,
        .navbar .primary-navigation .dropdown-toggle.active::before,
        .navbar #usernavigation .nav-link.active::before,
        .navbar #usernavigation .dropdown-toggle.active::before {
            top: 56px;
            border-bottom: 3px solid {$palette['cp-primary']};
        }
        
        .navbar #usernavigation .userinitials {
            background-color: {$palette['cp-medium-blue']};
            color: {$palette['cp-white']};
        }
        
        .navbar .edw-icon {
            color: {$palette['cp-primary']};
        }
        
        /* Nav pills */
        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            color: {$palette['cp-white']};
            background-color: {$palette['cp-primary']};
        }
        
        /* Tabs */
        .edw-tabs-navigation .nav-tabs .nav-item .nav-link.active,
        .edw-tabs-navigation .nav-tabs .nav-item .nav-link:hover,
        .edw-tabs-navigation .nav-tabs .nav-item .nav-link:focus {
            color: {$palette['cp-primary']};
        }
        
        .edw-tabs-navigation .nav-tabs .nav-item .nav-link.active::before {
            border-bottom: 3px solid {$palette['cp-primary']};
        }
        
        /* Course index */
        #theme_remui-drawers-courseindex .drawercontent #courseindex #courseindex-content .courseindex .courseindex-section .courseindex-section-title {
            padding: 10px 24px;
            color: {$palette['cp-white']};
            background-color: {$palette['cp-primary']};
            border-radius: 0;
            justify-content: space-between;
        }
        
        #theme_remui-drawers-courseindex .drawercontent #courseindex #courseindex-content .courseindex .courseindex-section .courseindex-section-title .edw-icon,
        #theme_remui-drawers-courseindex .drawercontent #courseindex #courseindex-content .courseindex .courseindex-section .courseindex-section-title .fa-lock {
            font-size: 24px;
            height: 24px;
            width: 24px;
            color: {$palette['cp-white']};
        }
        
        /* Botones */
        .btn-primary {
            background-color: {$palette['cp-secondary']};
            border-color: {$palette['cp-secondary']};
            color: {$palette['cp-white']};
        }
        
        .btn-primary:hover {
            background-color: {$palette['cp-primary']};
            border-color: {$palette['cp-primary']};
            color: {$palette['cp-white']};
        }
        
        .btn-primary:not(:disabled):not(.disabled):active,
        .btn-primary:not(:disabled):not(.disabled).active,
        .btn-primary:not(:disabled):not(.disabled):active:focus,
        .btn-primary.dropdown-toggle.show {
            background-color: {$palette['cp-secondary']};
            border-color: {$palette['cp-secondary']};
            color: {$palette['cp-white']};
        }
        
        .btn-primary:not(:disabled):not(.disabled):focus,
        .btn-primary:not(:disabled):not(.disabled).focus {
            background-color: {$palette['cp-primary']};
            border-color: {$palette['cp-primary']};
            color: {$palette['cp-white']};
        }
        
        .btn-primary .fa,
        .btn-primary .edw-icon {
            color: {$palette['cp-white']} !important;
        }
        
        .btn-secondary {
            background-color: {$palette['cp-primary']};
            border-color: {$palette['cp-primary']};
            color: {$palette['cp-white']};
        }
        
        .btn-secondary:hover {
            background-color: {$palette['cp-tertiary']};
            border-color: {$palette['cp-tertiary']};
            color: {$palette['cp-white']};
        }
        
        .btn-secondary:not(:disabled):not(.disabled):active,
        .btn-secondary:not(:disabled):not(.disabled).active,
        .btn-secondary:not(:disabled):not(.disabled):active:focus,
        .btn-secondary.dropdown-toggle.show {
            background-color: {$palette['cp-primary']};
            border-color: {$palette['cp-primary']};
            color: {$palette['cp-white']};
        }
        
        .btn-secondary:not(:disabled):not(.disabled):focus,
        .btn-secondary:not(:disabled):not(.disabled).focus {
            background-color: {$palette['cp-tertiary']};
            border-color: {$palette['cp-tertiary']};
            color: {$palette['cp-white']};
        }
        
        /* Categorías */
        .categoryname.text-truncate.small-info-semibold {
            color: {$palette['cp-white']};
            background-color: {$palette['cp-tertiary']};
            font-size: 0.8em;
        }
        
        /* Badges */
        .badge-info {
            background-color: {$palette['cp-medium-blue']};
        }
        
        .badge-info:hover {
            background-color: {$palette['cp-primary']};
            color: {$palette['cp-white']};
        }
        
        .badge-success {
            background-color: {$palette['cp-success']};
        }
        
        .badge.badge-danger {
            background-color: {$palette['cp-secondary']};
        }
        
        /* Footer */
        #page-footer {
            background-color: {$palette['cp-tertiary']};
        }
        
        #top-footer {
            background-color: {$palette['cp-primary']};
            color: {$palette['cp-white']};
        }
        
        #page-footer .copyright {
            background-color: #e8e8e8;
            color: {$palette['cp-primary']};
        }
        
        .my-credit-footer-wrapper {
            background-color: #e8e8e8;
            color: {$palette['cp-primary']};
        }
        
        a.my-credit-link,
        a.my-credit-link:hover {
            color: {$palette['cp-primary']};
        }
        
        /* Eventos de calendario */
        .block_calendar_upcoming .event.my-event-urgent {
            border-color: {$palette['cp-secondary']};
        }
        
        .block_calendar_upcoming .event.my-event-priority {
            border-color: {$palette['cp-tertiary']};
        }
        
        .maincalendar .calendarmonth td.today .day-number-circle {
            color: {$palette['cp-white']};
            background-color: {$palette['cp-primary']};
        }
        
        section#region-main .maincalendar td.today span.day-number-circle,
        aside:not(#block-region-side-pre) .maincalendar td.today span.day-number-circle {
            background-color: {$palette['cp-medium-blue']};
        }
        
        /* Paginación */
        .page-item.active .page-link {
            background-color: {$palette['cp-primary']};
            border-color: {$palette['cp-primary']};
        }
        
        /* Estadísticas */
        .edw-stats-wrapper .stat-block .inner .edw-icon {
            color: {$palette['cp-secondary']};
        }
        
        /* Gradientes */
        .position-absolute.w-100.h-100, #page-footer .footer-container hr.position-absolute.h-100 {
            background: linear-gradient(359deg, {$palette['cp-primary-dark']} -5%, transparent 120%) !important;
        }
        ";
        
        return $css;
    }
    
    /**
     * Genera estilos CSS para el modo oscuro
     * 
     * @return string CSS para modo oscuro
     */
    private function generate_dark_mode_css() {
        $palette = $this->color_palette;
        
        $css = "
        /* Estilos generados para compañía - Modo Oscuro */
        body.dark-mode {
            /* Encabezados */
            h1, .h1, h2, .h2, h3, .h3, h4, .h4, .h5, .h5, h6, .h6 {
                color: {$palette['cp-dm-text']};
            }
            
            /* Enlaces */
            a {
                color: {$palette['cp-dm-medium-blue']};
            }
            
            a:hover {
                color: {$palette['cp-dm-tertiary']};
            }
            
            /* Botones */
            .btn-primary {
                background-color: {$palette['cp-dm-secondary']};
                border-color: {$palette['cp-dm-secondary']};
            }
            
            .btn-primary:hover {
                background-color: {$palette['cp-dm-primary']};
                border-color: {$palette['cp-dm-primary']};
            }
            
            .btn-secondary {
                background-color: {$palette['cp-dm-primary']};
                border-color: {$palette['cp-dm-primary']};
            }
            
            .btn-secondary:hover {
                background-color: {$palette['cp-dm-tertiary']};
                border-color: {$palette['cp-dm-tertiary']};
            }
            
            /* Navegación */
            .navbar {
                background-color: {$palette['cp-dm-primary']};
                border-bottom-color: {$palette['cp-dm-primary']};
            }
            
            .navbar .primary-navigation .nav-link,
            .navbar .primary-navigation .more-nav>.dropdown>.dropdown-toggle,
            .navbar #usernavigation .nav-link,
            .navbar #usernavigation .more-nav>.dropdown>.dropdown-toggle {
                color: {$palette['cp-dm-text']};
            }
            
            .navbar .primary-navigation .nav-link.active,
            .navbar .primary-navigation .nav-link:hover,
            .navbar #usernavigation .nav-link:hover {
                color: {$palette['cp-dm-tertiary']};
            }
            
            .navbar .primary-navigation .nav-link.active::before {
                border-bottom-color: {$palette['cp-dm-tertiary']};
            }
            
            /* Navigation pills */
            .nav-pills .nav-link.active,
            .nav-pills .show>.nav-link {
                background-color: {$palette['cp-dm-primary']};
            }
            
            /* Course index */
            #theme_remui-drawers-courseindex .drawercontent {
                background-color: {$palette['cp-dm-card-bg']};
            }
            
            #theme_remui-drawers-courseindex .drawercontent #courseindex #courseindex-content .courseindex .courseindex-section .courseindex-section-title {
                background-color: {$palette['cp-dm-primary']};
            }
            
            /* Tarjetas */
            .dashboard-card {
                background-color: {$palette['cp-dm-card-bg']};
            }
            
            .dashboard-card .card-footer {
                background-color: " . $this->darken_color($palette['cp-dm-card-bg'], 3) . ";
            }
            
            .categoryname.text-truncate.small-info-semibold {
                background-color: {$palette['cp-dm-tertiary']};
            }
            
            /* Footer */
            #page-footer {
                background-color: " . $this->darken_color($palette['cp-dm-tertiary'], 10) . ";
            }
            
            #top-footer {
                background-color: {$palette['cp-dm-primary']};
            }
            
            .my-credit-footer-wrapper {
                background-color: {$palette['cp-dm-card-bg']};
                color: {$palette['cp-dm-text']};
            }
            
            a.my-credit-link,
            a.my-credit-link:hover {
                color: {$palette['cp-dm-text']};
            }
            
            #page-footer .copyright {
                background-color: {$palette['cp-dm-card-bg']};
                color: {$palette['cp-dm-text']};
            }
            
            /* Badges */
            .badge-info {
                background-color: {$palette['cp-dm-medium-blue']};
                color: {$palette['cp-dm-text']};
            }
            
            .badge.badge-danger {
                background-color: {$palette['cp-dm-secondary']};
            }
            
            /* Eventos */
            .block_calendar_upcoming .event.my-event-urgent {
                border-color: {$palette['cp-dm-secondary']};
            }
            
            .block_calendar_upcoming .event.my-event-priority {
                border-color: {$palette['cp-dm-tertiary']};
            }
            
            /* Paginación */
            .page-item.active .page-link {
                background-color: {$palette['cp-dm-primary']};
                border-color: {$palette['cp-dm-primary']};
            }
            
            /* Gradientes */
            .position-absolute.w-100.h-100, #page-footer .footer-container hr.position-absolute.h-100 {
                background: linear-gradient(359deg, " . $this->darken_color($palette['cp-dm-primary'], 10) . " -5%, transparent 120%) !important;
            }
        }
        ";
        
        return $css;
    }
    
    /**
     * Genera el CSS completo (modos claro y oscuro)
     * 
     * @return string CSS completo
     */
    public function get_css() {
        if (!$this->is_initialized) {
            $this->init_color_palette();
        }
        
        // Combinar CSS de modos claro y oscuro
        return $this->generate_light_mode_css() . $this->generate_dark_mode_css();
    }
    
    /**
     * Obtiene el CSS para una instancia compartida
     * 
     * @param string $primary Color primario
     * @param string $secondary Color secundario
     * @param string $text Color de texto
     * @param string $background Color de fondo
     * @return string CSS generado
     */
    public static function get_company_css($primary, $secondary, $text = '#706565', $background = '#FFFFFF') {
        $instance = new self($primary, $secondary, $text, $background);
        return $instance->get_css();
    }
}