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
 * Language strings for theme_clickpoint (English).
 *
 * This file contains all the language strings used throughout the ClickPoint theme.
 * The theme extends RemUI with corporate features and IOMAD integration.
 *
 * @package    theme_clickpoint
 * @category   string
 * @copyright  2024 Soporte ClickPoint <soporte@clickpoint.co>
 * @author     Pedro Alonso Arias Balcucho
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Basic plugin information.
$string['pluginname'] = 'ClickPoint';
$string['choosereadme'] = 'ClickPoint is a theme created by Pedro Arias exclusively for our customers. It enhances the Remui and IOMAD themes with additional features for corporate learning environments.';

// Configuration titles.
$string['configtitle'] = 'ClickPoint Configuration';
$string['logo'] = 'Logo';

// Theme settings main title.
$string['themesettings'] = 'ClickPoint Theme Settings';
$string['themesettingsgeneral'] = 'General Settings for ClickPoint';

// Contact information section.
$string['contactinfo'] = 'Contact Information';
$string['abouttitle'] = 'About Title';
$string['abouttitledesc'] = 'Title for the "About" section that appears in the footer area of your ClickPoint theme.';
$string['abouttitle_default'] = 'About Us';

$string['abouttext'] = 'About Text';
$string['abouttextdesc'] = 'Content text for the "About" section in the footer area. This can include HTML for formatting.';

$string['loginfootertext'] = 'Login Footer';
$string['loginfootertextdesc'] = 'Custom content to display in the footer area of the login page.';

// General alert settings.
$string['generalalert'] = 'General Alert';
$string['generalalertdesc'] = 'Display an alert message on the frontpage to notify users of important events or information.';

// Footer sections configuration.
$string['footertitle'] = 'Middle Footer Title';
$string['footertitledesc'] = 'The title for the middle section in the footer area.';

$string['footertext'] = 'Middle Footer Text';
$string['footertextdesc'] = 'The content to display in the middle section of the footer area.';

$string['footertitle2'] = 'Right Footer Title';
$string['footertitledesc2'] = 'The title for the right section in the footer area.';

$string['footertext2'] = 'Right Footer Text';
$string['footertextdesc2'] = 'The content to display in the right section of the footer area.';

// Copyright and customization.
$string['copyright'] = 'Copyright';
$string['copyrightdesc'] = 'Organization name or copyright statement for your site.';

$string['customcss'] = 'Custom CSS';
$string['customcssdesc'] = 'Add custom CSS rules to fine-tune the appearance of your ClickPoint theme. These rules will be applied across all pages.';

$string['themeinfotext'] = 'This theme was created for <strong>ClickPoint</strong> by <a target="_blank" href="https://orioncloud.com.co/">OrionCloud Solutions</a>.';
$string['credit'] = 'Copyright © 2025 - All rights reserved';

// Course and category display.
$string['showingonlycategorieswithenrolledcourses'] = 'Showing categories containing enrolled courses only';
$string['nocoursestoshow'] = 'No courses to show were found';

// Region names.
$string['region-side-pre'] = 'Right Sidebar';

// Copy/Paste functionality.
$string['copypaste'] = 'Copy and Paste Protection';
$string['copypaste_desc'] = 'Control the copy and paste functionality for specific user roles.';
$string['config_copypaste'] = 'Enable or disable copy & paste';
$string['config_copypaste_desc'] = 'When enabled, prevents users with selected roles from copying or pasting content.';
$string['disable'] = 'Disable';
$string['enable'] = 'Enable';

// Visibility options.
$string['show'] = 'Show';
$string['hide'] = 'Hide';

// Carousel settings.
$string['carouselsettings'] = 'ClickPoint Login Carousel Settings';
$string['carouselsettings_desc'] = 'Configure the image carousel displayed on the login page, including images, timing, and other options.';

$string['enablecarousel'] = 'Enable login page carousel';
$string['enablecarousel_desc'] = 'Turn on the image carousel feature on the login page.';
$string['numberofslides'] = 'Number of slides';
$string['numberofslides_desc'] = 'Select how many slides to include in the login page carousel (maximum 5).';
$string['slideimage'] = 'Slide {$a} image';
$string['slideimage_desc'] = 'Upload an image for slide {$a} in the login carousel. Recommended size: 1920x1080px.';
$string['carouselinterval'] = 'Carousel Interval';
$string['carouselintervaldesc'] = 'Set the time (in milliseconds) between slide transitions. Default: 5000 (5 seconds).';

// Carousel navigation.
$string['carousel'] = 'Image carousel';
$string['loginsection'] = 'Login section';
$string['previous'] = 'Go to the previous slide';
$string['next'] = 'Go to the next slide';

// Authorization messages - Enhanced with bifurcated messaging.
$string['unauthorized_access'] = 'Unauthorized Access';
$string['unauthorized_access_msg'] = 'This site is not authorized to operate at this URL.';

// Admin-specific unauthorized access messages.
$string['unauthorized_access_admin'] = 'Development Environment Access Restriction';
$string['unauthorized_access_admin_msg'] = 'This ClickPoint installation is currently restricted to authorized development and production domains. Please verify the deployment configuration and ensure the site is accessed through an approved URL. Contact the development team if you need to add additional authorized domains to the whitelist.';

// User-specific unauthorized access messages.
$string['unauthorized_access_user'] = 'Site Access Notice';
$string['unauthorized_access_user_msg'] = 'We\'re currently performing maintenance on this platform. Please try accessing the site again in a few moments, or contact your system administrator if this issue persists.';

$string['devtools_access_disabled'] = 'Access to the development tools is disabled.';

// Visibility settings.
$string['hidefrontpagesections'] = 'Hide front page top sections';
$string['hidefrontpagesections_desc'] = 'When enabled, hides the default marketing sections at the top of the front page for a cleaner appearance.';

$string['hidefootersections'] = 'Hide footer sections';
$string['hidefootersections_desc'] = 'When enabled, hides the footer content sections while keeping essential links.';

// Login and header settings.
$string['themesettingslogin'] = 'ClickPoint Login Settings';
$string['personalareaheader'] = 'Personal Area Header Image';
$string['personalareaheaderdesc'] = 'Upload a banner image to display at the top of the user\'s personal dashboard page.';
$string['mycoursesheader'] = 'My Courses Header Image';
$string['mycoursesheaderdesc'] = 'Upload a banner image to display at the top of the My Courses page.';

// Cache definition.
$string['cachedef_fontawesomeremuiiconmapping'] = 'Cache Storage for Font Awesome Icon Mapping';

// Updated strings for the settings tabs with ClickPoint prefix.
$string['generalsettings'] = 'ClickPoint General Settings';
$string['generalnotice'] = 'General Notice';
$string['generalnoticedesc'] = 'Enter text for a site-wide notice that will display at the top of all pages. Use this for important announcements.';
$string['generalnotice_create'] = 'Create a Notice';
$string['generalnoticeheading'] = 'ClickPoint General Notice Settings';
$string['generalnoticemode'] = 'Notice Display Mode';
$string['generalnoticemodedesc'] = 'Select how the general notice should be displayed: as an informational message, a critical alert, or disabled.';
$string['generalnoticemode_off'] = 'Off';
$string['generalnoticemode_info'] = 'Information';
$string['generalnoticemode_danger'] = 'Critical Alert';

// Chat settings.
$string['chatheading'] = 'ClickPoint Chat Settings';
$string['enable_chat'] = 'Enable Chat Widget';
$string['enable_chatdesc'] = 'When enabled, adds a Tawk.to chat widget to your site for logged-in users.';
$string['tawkto_embed_url'] = 'Tawk.to Embed URL';
$string['tawkto_embed_urldesc'] = 'Enter your Tawk.to widget URL obtained from your Tawk.to dashboard (e.g., https://embed.tawk.to/123456789/default).';

// Content protection settings.
$string['contentprotectionheading'] = 'ClickPoint Content Protection Settings';
$string['copypaste_prevention'] = 'Prevent Copy/Paste';
$string['copypaste_preventiondesc'] = 'When enabled, restricts the ability to copy, paste, or print content for selected user roles.';
$string['copypaste_roles'] = 'Protected Roles';
$string['copypaste_rolesdesc'] = 'Select which user roles will have copy/paste restrictions applied.';

// Login slider settings.
$string['loginsliderheading'] = 'ClickPoint Login Slider Settings';
$string['slideheading'] = 'Slide {$a} Configuration';

// Dashboard settings.
$string['dashboardsettings'] = 'ClickPoint Dashboard Settings';
$string['personalareaheading'] = 'Personal Dashboard Settings';
$string['mycoursesheading'] = 'My Courses Page Settings';
$string['show_personalareaheader'] = 'Show Personal Dashboard Header';
$string['show_personalareaheaderdesc'] = 'Display a custom banner image at the top of the personal dashboard page.';
$string['show_mycoursesheader'] = 'Show My Courses Header';
$string['show_mycoursesheaderdesc'] = 'Display a custom banner image at the top of the My Courses page.';

// Footer settings.
$string['footersettings'] = 'ClickPoint Footer Settings';
$string['footeraboutheading'] = 'Footer About Section Configuration';

// Login settings.
$string['loginsettings'] = 'ClickPoint Login Settings';

// Default about text.
$string['abouttext_default'] = '<p style="text-align: center;"></p>
<p style="text-align: left;">
</p>
<p style="text-align: center;"><span style="font-family: inherit; font-size: 1rem; letter-spacing: 0rem; text-transform: inherit;"><b>OrionCloud - Hosting que impulsa tu éxito</b></span><span style="font-family: inherit; font-size: 1rem; letter-spacing: 0rem; text-transform: inherit;"><br></span><strong style="font-family: inherit; font-size: 1rem; letter-spacing: 0rem; text-transform: inherit;">www.orioncloud.com.co</strong></p>
<p></p>';

// Support and help links.
$string['clickpointhelp'] = "Platform Help";
$string['clickpointfeedback'] = "ClickPoint Feedback";
$string['clickpointsupport'] = "ClickPoint Support";

// Enrolled users information.
$string['enrolleduserscountvisibilityhead'] = "Show 'Enrolled students' information";
$string['enrolleduserscountvisibilitydesc'] = "Toggle the visibility of the enrolled students count on course cards and pages.";

// Header default label.
$string['defaultheader'] = 'Default Header';