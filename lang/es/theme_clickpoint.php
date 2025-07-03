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
 * Language strings for theme_clickpoint (Spanish) - CLEAN VERSION
 *
 * Este archivo contiene solo las cadenas de idioma que realmente se utilizan en el tema.
 * Las cadenas no utilizadas han sido eliminadas para un mejor mantenimiento.
 *
 * @package    theme_clickpoint
 * @category   string
 * @copyright  2024 Soporte ClickPoint <soporte@clickpoint.co>
 * @author     Pedro Alonso Arias Balcucho
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// ========================================
// INFORMACIÓN BÁSICA DEL PLUGIN
// ========================================
$string['pluginname'] = 'ClickPoint';
$string['themesettings'] = 'Ajustes del Tema ClickPoint';

// ========================================
// CONFIGURACIÓN GENERAL
// ========================================
$string['generalsettings'] = 'Configuración General de ClickPoint';
$string['show'] = 'Mostrar';
$string['hide'] = 'Ocultar';

// ========================================
// CONFIGURACIÓN DE AVISOS GENERALES
// ========================================
$string['generalnoticeheading'] = 'Configuración de Avisos Generales de ClickPoint';
$string['generalnotice'] = 'Aviso General';
$string['generalnoticedesc'] = 'Introduzca texto para un aviso en todo el sitio que se mostrará en la parte superior de todas las páginas. Úselo para anuncios importantes.';
$string['generalnotice_create'] = 'Crear un Aviso';
$string['generalnoticemode'] = 'Modo de visualización de avisos';
$string['generalnoticemodedesc'] = 'Seleccione cómo debe mostrarse el aviso general: como un mensaje informativo, una alerta crítica o deshabilitado.';
$string['generalnoticemode_off'] = 'Desactivado';
$string['generalnoticemode_info'] = 'Información';
$string['generalnoticemode_danger'] = 'Alerta crítica';

// ========================================
// CONFIGURACIÓN DE CHAT
// ========================================
$string['chatheading'] = 'Configuración de Chat de ClickPoint';
$string['enable_chat'] = 'Habilitar Widget de Chat';
$string['enable_chatdesc'] = 'Cuando está habilitado, agrega un widget de chat Tawk.to a su sitio para usuarios conectados.';
$string['tawkto_embed_url'] = 'URL de Integración Tawk.to';
$string['tawkto_embed_urldesc'] = 'Ingrese su URL de widget Tawk.to obtenida de su panel de Tawk.to (ej., https://embed.tawk.to/123456789/default).';

// ========================================
// CONFIGURACIÓN DE PROTECCIÓN DE CONTENIDO
// ========================================
$string['contentprotectionheading'] = 'Configuración de Protección de Contenido de ClickPoint';
$string['copypaste_prevention'] = 'Prevenir Copiar/Pegar';
$string['copypaste_preventiondesc'] = 'Cuando está habilitado, restringe la capacidad de copiar, pegar o imprimir contenido para roles de usuario seleccionados.';
$string['copypaste_roles'] = 'Roles Protegidos';
$string['copypaste_rolesdesc'] = 'Seleccione qué roles de usuario tendrán restricciones de copiar/pegar aplicadas.';

// ========================================
// CONFIGURACIÓN DE INICIO DE SESIÓN
// ========================================
$string['loginsettings'] = 'Configuración de Inicio de Sesión de ClickPoint';

// Configuración del Carrusel
$string['carouselsettings'] = 'Configuración del Carrusel de Inicio de ClickPoint';
$string['carousel'] = 'Carrusel de imágenes';
$string['numberofslides'] = 'Número de diapositivas';
$string['numberofslides_desc'] = 'Seleccione cuántas diapositivas incluir en el carrusel de la página de inicio de sesión (máximo 5).';
$string['slideimage'] = 'Imagen de diapositiva {$a}';
$string['slideimage_desc'] = 'Suba una imagen para la diapositiva {$a} en el carrusel de inicio. Tamaño recomendado: 1920x1080px.';
$string['carouselinterval'] = 'Intervalo del carrusel';
$string['carouselintervaldesc'] = 'Establezca el tiempo (en milisegundos) entre transiciones de diapositivas. Predeterminado: 5000 (5 segundos).';

// Navegación del carrusel
$string['previous'] = 'Ir a la diapositiva anterior';
$string['next'] = 'Ir a la siguiente diapositiva';

// ========================================
// CONFIGURACIÓN DEL TABLERO
// ========================================
$string['dashboardsettings'] = 'Configuración del Tablero de ClickPoint';

// Configuración del Encabezado del Área Personal
$string['personalareaheading'] = 'Configuración del Tablero Personal';
$string['show_personalareaheader'] = 'Mostrar Cabecera del Tablero Personal';
$string['show_personalareaheaderdesc'] = 'Muestra una imagen de banner personalizada en la parte superior de la página del tablero personal.';
$string['personalareaheader'] = 'Imagen de cabecera del área personal';
$string['personalareaheaderdesc'] = 'Suba una imagen de banner para mostrar en la parte superior de la página del tablero personal del usuario.';

// Configuración del Encabezado de Mis Cursos
$string['mycoursesheading'] = 'Configuración de la Página Mis Cursos';
$string['show_mycoursesheader'] = 'Mostrar Cabecera de Mis Cursos';
$string['show_mycoursesheaderdesc'] = 'Muestra una imagen de banner personalizada en la parte superior de la página Mis Cursos.';
$string['mycoursesheader'] = 'Imagen de cabecera de Mis Cursos';
$string['mycoursesheaderdesc'] = 'Suba una imagen de banner para mostrar en la parte superior de la página Mis Cursos.';

$string['defaultheader'] = 'Cabecera predeterminada';

// ========================================
// CONFIGURACIÓN DEL PIE DE PÁGINA
// ========================================
$string['footersettings'] = 'Configuración del Pie de Página de ClickPoint';
$string['footeraboutheading'] = 'Configuración de la Sección Acerca del Pie de Página';
$string['hidefootersections'] = 'Ocultar secciones del pie de página';
$string['hidefootersections_desc'] = 'Cuando está habilitado, oculta las secciones de contenido del pie de página manteniendo los enlaces esenciales.';

// Sección Acerca de
$string['abouttitle'] = 'Título de Acerca de';
$string['abouttitledesc'] = 'Título para la sección "Acerca de" que aparece en el área del pie de página del tema ClickPoint.';
$string['abouttitle_default'] = 'Sobre Nosotros';
$string['abouttext'] = 'Texto de Acerca de';
$string['abouttextdesc'] = 'Texto de contenido para la sección "Acerca de" en el área del pie de página. Puede incluir HTML para formato.';
$string['abouttext_default'] = '<p style="text-align: center;"></p>
<p style="text-align: left;">
</p>
<p style="text-align: center;"><span style="font-family: inherit; font-size: 1rem; letter-spacing: 0rem; text-transform: inherit;"><b>OrionCloud - Hosting que impulsa tu éxito</b></span><span style="font-family: inherit; font-size: 1rem; letter-spacing: 0rem; text-transform: inherit;"><br></span><strong style="font-family: inherit; font-size: 1rem; letter-spacing: 0rem; text-transform: inherit;">www.orioncloud.com.co</strong></p>
<p></p>';

// Crédito del Pie de Página
$string['credit'] = 'Copyright © 2025 - Todos los derechos reservados';

// ========================================
// SEGURIDAD Y CONTROL DE ACCESO
// ========================================
// Mensajes de autorización - Mejorados con mensajes bifurcados
$string['unauthorized_access_admin'] = 'Restricción de Acceso del Entorno de Desarrollo';
$string['unauthorized_access_admin_msg'] = 'Esta instalación de ClickPoint está actualmente restringida a dominios de desarrollo y producción autorizados. Por favor, verifique la configuración de despliegue y asegúrese de que el sitio se acceda a través de una URL aprobada. Contacte al equipo de desarrollo si necesita agregar dominios adicionales autorizados a la lista blanca.';
$string['unauthorized_access_user'] = 'Aviso de Acceso al Sitio';
$string['unauthorized_access_user_msg'] = 'Actualmente estamos realizando mantenimiento en esta plataforma. Por favor, intente acceder al sitio nuevamente en unos momentos, o contacte a su administrador del sistema si este problema persiste.';
$string['devtools_access_disabled'] = 'El acceso a las herramientas de desarrollo está deshabilitado.';