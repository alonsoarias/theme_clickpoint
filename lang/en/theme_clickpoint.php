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
 * Language strings for theme_clickpoint (English) - CLEAN VERSION
 *
 * This file contains only the language strings that are actually used in the theme.
 * Unused strings have been removed for better maintenance.
 *
 * @package    theme_clickpoint
 * @category   string
 * @copyright  2024 Soporte ClickPoint <soporte@clickpoint.co>
 * @author     Pedro Alonso Arias Balcucho
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// ========================================
// BASIC PLUGIN INFORMATION
// ========================================
$string['pluginname'] = 'ClickPoint';
$string['themesettings'] = 'ClickPoint Theme Settings';

// ========================================
// GENERAL SETTINGS
// ========================================
$string['generalsettings'] = 'ClickPoint General Settings';
$string['show'] = 'Show';
$string['hide'] = 'Hide';

// ========================================
// GENERAL NOTICE SETTINGS
// ========================================
$string['generalnoticeheading'] = 'ClickPoint General Notice Settings';
$string['generalnotice'] = 'General Notice';
$string['generalnoticedesc'] = 'Enter text for a site-wide notice that will display at the top of all pages. Use this for important announcements.';
$string['generalnotice_create'] = 'Create a Notice';
$string['generalnoticemode'] = 'Notice Display Mode';
$string['generalnoticemodedesc'] = 'Select how the general notice should be displayed: as an informational message, a critical alert, or disabled.';
$string['generalnoticemode_off'] = 'Off';
$string['generalnoticemode_info'] = 'Information';
$string['generalnoticemode_danger'] = 'Critical Alert';

// ========================================
// CHAT SETTINGS
// ========================================
$string['chatheading'] = 'ClickPoint Chat Settings';
$string['enable_chat'] = 'Enable Chat Widget';
$string['enable_chatdesc'] = 'When enabled, adds a Tawk.to chat widget to your site for logged-in users.';
$string['tawkto_embed_url'] = 'Tawk.to Embed URL';
$string['tawkto_embed_urldesc'] = 'Enter your Tawk.to widget URL obtained from your Tawk.to dashboard (e.g., https://embed.tawk.to/123456789/default).';

// ========================================
// CONTENT PROTECTION SETTINGS
// ========================================
$string['contentprotectionheading'] = 'ClickPoint Content Protection Settings';
$string['copypaste_prevention'] = 'Prevent Copy/Paste';
$string['copypaste_preventiondesc'] = 'When enabled, restricts the ability to copy, paste, or print content for selected user roles.';
$string['copypaste_roles'] = 'Protected Roles';
$string['copypaste_rolesdesc'] = 'Select which user roles will have copy/paste restrictions applied.';

// ========================================
// LOGIN SETTINGS
// ========================================
$string['loginsettings'] = 'ClickPoint Login Settings';

// Carousel Settings
$string['carouselsettings'] = 'ClickPoint Login Carousel Settings';
$string['carousel'] = 'Image carousel';
$string['numberofslides'] = 'Number of slides';
$string['numberofslides_desc'] = 'Select how many slides to include in the login page carousel (maximum 5).';
$string['slideimage'] = 'Slide {$a} image';
$string['slideimage_desc'] = 'Upload an image for slide {$a} in the login carousel. Recommended size: 1920x1080px.';
$string['carouselinterval'] = 'Carousel Interval';
$string['carouselintervaldesc'] = 'Set the time (in milliseconds) between slide transitions. Default: 5000 (5 seconds).';

// Carousel navigation
$string['previous'] = 'Go to the previous slide';
$string['next'] = 'Go to the next slide';

// ========================================
// DASHBOARD SETTINGS
// ========================================
$string['dashboardsettings'] = 'ClickPoint Dashboard Settings';

// Personal Area Header Settings
$string['personalareaheading'] = 'Personal Dashboard Settings';
$string['show_personalareaheader'] = 'Show Personal Dashboard Header';
$string['show_personalareaheaderdesc'] = 'Display a custom banner image at the top of the personal dashboard page.';
$string['personalareaheader'] = 'Personal Area Header Image';
$string['personalareaheaderdesc'] = 'Upload a banner image to display at the top of the user\'s personal dashboard page.';

// My Courses Header Settings
$string['mycoursesheading'] = 'My Courses Page Settings';
$string['show_mycoursesheader'] = 'Show My Courses Header';
$string['show_mycoursesheaderdesc'] = 'Display a custom banner image at the top of the My Courses page.';
$string['mycoursesheader'] = 'My Courses Header Image';
$string['mycoursesheaderdesc'] = 'Upload a banner image to display at the top of the My Courses page.';

$string['defaultheader'] = 'Default Header';

// ========================================
// FOOTER SETTINGS
// ========================================
$string['footersettings'] = 'ClickPoint Footer Settings';
$string['footeraboutheading'] = 'Footer About Section Configuration';
$string['hidefootersections'] = 'Hide footer sections';
$string['hidefootersections_desc'] = 'When enabled, hides the footer content sections while keeping essential links.';

// About Section
$string['abouttitle'] = 'About Title';
$string['abouttitledesc'] = 'Title for the "About" section that appears in the footer area of your ClickPoint theme.';
$string['abouttitle_default'] = 'About Us';
$string['abouttext'] = 'About Text';
$string['abouttextdesc'] = 'Content text for the "About" section in the footer area. This can include HTML for formatting.';
$string['abouttext_default'] = '<p style="text-align: center;"></p>
<p style="text-align: left;">
</p>
<p style="text-align: center;"><span style="font-family: inherit; font-size: 1rem; letter-spacing: 0rem; text-transform: inherit;"><b>OrionCloud - Hosting que impulsa tu éxito</b></span><span style="font-family: inherit; font-size: 1rem; letter-spacing: 0rem; text-transform: inherit;"><br></span><strong style="font-family: inherit; font-size: 1rem; letter-spacing: 0rem; text-transform: inherit;">www.orioncloud.com.co</strong></p>
<p></p>';

// Footer Credit
$string['credit'] = 'Copyright © 2025 - All rights reserved';

// ========================================
// SECURITY AND ACCESS CONTROL
// ========================================
// Authorization messages - Enhanced with bifurcated messaging
$string['unauthorized_access_admin'] = 'Development Environment Access Restriction';
$string['unauthorized_access_admin_msg'] = 'This ClickPoint installation is currently restricted to authorized development and production domains. Please verify the deployment configuration and ensure the site is accessed through an approved URL. Contact the development team if you need to add additional authorized domains to the whitelist.';
$string['unauthorized_access_user'] = 'Site Access Notice';
$string['unauthorized_access_user_msg'] = 'We\'re currently performing maintenance on this platform. Please try accessing the site again in a few moments, or contact your system administrator if this issue persists.';
$string['devtools_access_disabled'] = 'Access to the development tools is disabled.';