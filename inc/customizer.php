<?php
/**
 * Blank Canvas Theme Customizer
 *
 * @package Blank_Canvas
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function blank_canvas_customize_register( $wp_customize ) {
    
    
    // Inlcude the Alpha Color Picker control file.
    require_once dirname(__FILE__) . '/../vendor/BraadMartin-alpha-color-picker/alpha-color-picker.php';
/*
    // Alpha Color Picker setting.
    $wp_customize->add_setting('alpha_color_setting', array(
        'default' => '#eeeeee',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage'
    ) );

    // Alpha Color Picker control.
    $wp_customize->add_control(new Customize_Alpha_Color_Control($wp_customize, 'alpha_color_control', array(
            'label' => __('Alpha Color Picker', 'xxx'),
            'section' => 'colors',
            'settings' => 'alpha_color_setting',
            'show_opacity' => true,
            'palette' => array(
                'rgb(150, 50, 220)',
                'rgba(50,50,50,0.8)',
                'rgba( 255, 255, 255, 0.2 )',
                '#00CC99',
            ),
        ) )
    );
*/

    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    $wp_customize->get_section('colors')->title = __( 'Overwrite All Styles', 'blank-canvas' );
    $wp_customize->get_section('title_tagline')->priority = 1;
    
    $wp_customize->get_section('colors')->title = __( 'Overwrite All Styles', 'blank-canvas' );
    $wp_customize->get_section('colors')->priority = 100;
    
    
    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial( 'blogname', array(
            'selector'        => '.site-title a',
            'render_callback' => 'blank_canvas_customize_partial_blogname',
        ) );
        $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
            'selector'        => '.site-description',
            'render_callback' => 'blank_canvas_customize_partial_blogdescription',
        ) );
    }
    
    $wp_customize->add_section( 'overwrite' , array(
        'title'      => 'Overwrite',
        'priority'   => 10,
    ) );
    
    $wp_customize->add_setting('overwrite_background_color_setting', array(
        'default' => '#eeeeee',
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage'
    ));
    
    $wp_customize->add_control(new Customize_Alpha_Color_Control($wp_customize, 'overwrite_background_color_control', array(
            'label' => __('Overwrite Background Color', 'blank-canvas'),
            'section' => 'overwrite',
            'settings' => 'overwrite_background_color_setting',
            'show_opacity' => true,
            'palette' => array(
                'rgb(100, 100, 100)',
                'rgba(100,100,100,0.5)',
                'rgba( 100, 100, 100, 0.5 )',
                '#00CC99',
            ),
        ) )
    );
}
add_action( 'customize_register', 'blank_canvas_customize_register' );

function blank_canvas_get_rgba_theme_mod( $name, $default = false ) {
    $result = get_theme_mod($name, $default);
    
    if ( strpos( $result, "#") == 0 && strlen($result) == 7) {
        list($r, $g, $b) = sscanf($result, "#%02x%02x%02x");
        $result = "rgb($r, $g, $b)";
    }
    
    return $result;
}

function blank_canvas_customizer_css() {
    ?>
         <style type="text/css">
            #page { background: <?php echo blank_canvas_get_rgba_theme_mod('overwrite_background_color_setting', '#43C6E4'); ?>; }
         </style>
    <?php
}
add_action( 'wp_head', 'blank_canvas_customizer_css');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function blank_canvas_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function blank_canvas_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function blank_canvas_customize_preview_js() {
	wp_enqueue_script( 'blank-canvas-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'blank_canvas_customize_preview_js' );
