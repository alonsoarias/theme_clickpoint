<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     theme_clickpoint
 * @copyright   2024 Soporte clickpoint <soporte@clickpoint.co>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'theme_clickpoint';
$plugin->version = 2025012207; // Fecha de la versión: Año, mes, día, incremento.
$plugin->requires = 2022041200; // Versión mínima de Moodle requerida.
$plugin->release   = '4.5.0';
$plugin->dependencies = [
    'theme_remui' => 2024102300, // Dependencia del tema padre.
];
