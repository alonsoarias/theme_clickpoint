<?php
defined('MOODLE_INTERNAL') || die();

// Obtener atributos del body y la configuración del tema.
$bodyattributes = $OUTPUT->body_attributes();
$theme = theme_config::load('clickpoint');

// Para el manejo de archivos.
$fs = get_file_storage();
$context = context_system::instance();

// Construimos el contexto para la plantilla.
$templatecontext = [
    'sitename'         => format_string(
        $SITE->fullname,
        true,
        ['context' => context_course::instance(SITEID), 'escape' => false]
    ),
    'output'           => $OUTPUT,
    'bodyattributes'   => $bodyattributes,
    'carouselimages'   => [],
    'carouselinterval' => isset($theme->settings->carouselinterval) ? (int)$theme->settings->carouselinterval : 5000,
    'my_credit'        => get_string('credit', 'theme_clickpoint'),
    'hasgeneralnote'   => false,
    'generalnote'      => '',
    // Variables de control para la visualización
    'has_carousel'     => false,
    'multiple_slides'  => false
];

// =========================================================================
// 1) Carrusel de diapositivas
// =========================================================================
// Se obtiene el número de slides configurado con el prefijo "loging_".
$numslides = isset($theme->settings->loging_numberofslides) && is_numeric($theme->settings->loging_numberofslides)
    ? (int)$theme->settings->loging_numberofslides
    : 1;

$validSlides = 0; // Contador de slides válidos

for ($i = 1; $i <= $numslides; $i++) {
    // Obtenemos la URL de la imagen usando el identificador con prefijo "loging_"
    $imageurl = $theme->setting_file_url("loging_slideimage{$i}", "loging_slideimage{$i}");
    
    if (!empty($imageurl)) {
        // Verificamos si el archivo existe en storage.
        $files = $fs->get_area_files(
            $context->id,
            'theme_clickpoint',
            "loging_slideimage{$i}",
            0,
            'sortorder',
            false
        );
        
        if (!empty($files)) {
            $validSlides++;
            
            // Extraemos los demás parámetros del slide usando los identificadores con prefijo "loging_"
            $slidetitle = format_string(
                $theme->settings->{"loging_slidetitle{$i}"} ?? '',
                true,
                ['escape' => false]
            );
            $slideurl = $theme->settings->{"loging_slideurl{$i}"} ?? '#';
            
            // Se añade la diapositiva al array con sus settings
            $templatecontext['carouselimages'][] = [
                'url'       => $imageurl,
                'link'      => $slideurl,
                'title'     => $slidetitle,
                'has_title' => !empty($slidetitle),
                'has_link'  => ($slideurl !== '#'),
                'first'     => ($validSlides === 1),
                'index'     => ($validSlides - 1),
                'real_index'=> $i // Índice original del slide
            ];
        }
    }
}

// Actualizamos las variables de control del carrusel
$templatecontext['has_carousel'] = ($validSlides > 0);
$templatecontext['multiple_slides'] = ($validSlides > 1);

// Si no hay slides válidos, usamos una imagen por defecto
if (!$templatecontext['has_carousel']) {
    $defaultImage = $OUTPUT->image_url('slide0', 'theme_clickpoint');
    $templatecontext['carouselimages'][] = [
        'url'       => (string)$defaultImage,
        'link'      => '#',
        'title'     => get_string('default_slide_title', 'theme_clickpoint'),
        'has_title' => true,
        'has_link'  => false,
        'first'     => true,
        'index'     => 0,
        'real_index'=> 1
    ];
    $templatecontext['has_carousel'] = true;
    $templatecontext['multiple_slides'] = false;
}


// =========================================================================
// 2) Renderizar la plantilla con este contexto
// =========================================================================
echo $OUTPUT->render_from_template('theme_clickpoint/core/login-custom', $templatecontext);
