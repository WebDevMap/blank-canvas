<?php
/**
 * Blank Canvas Theme Customizer
 *
 * @package Blank_Canvas
 */

function blank_canvas_register_color_control( $wp_customize, $control_id, $section, $default_color = '#eeeeee', $priority = 10, $label = 'Unnamed', $description = '') {
    $setting_name = "$control_id"."_color_setting";
    $control_name = "$control_id"."_color_control";
    $wp_customize->add_setting( $setting_name, array(
        'default'    => $default_color,
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
        'transport'  => 'postMessage'
    ));
    if ($description == '') {
        $wp_customize->add_control(new Customize_Alpha_Color_Control($wp_customize, $control_name, array(
                'section'      => $section,
                'label'        => $label,
                'settings'     => $setting_name,
                'show_opacity' => true,
                'priority'     => $priority,
                'palette'      => array(
                    'rgb(100, 100, 100)',
                    'rgba(100,100,100,0.5)',
                    'rgba( 100, 100, 100, 0.5 )',
                    '#000000',
                ),
            ) )
        );
    } else {
        $wp_customize->add_control(new Customize_Alpha_Color_Control($wp_customize, $control_name, array(
                'section'      => $section,
                'label'        => $label,
                'description'  => $description,
                'settings'     => $setting_name,
                'show_opacity' => true,
                'priority'     => $priority,
                'palette'      => array(
                    'rgb(100, 100, 100)',
                    'rgba(100,100,100,0.5)',
                    'rgba( 100, 100, 100, 0.5 )',
                    '#000000',
                ),
            ) )
        );
    }
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function blank_canvas_customize_register( $wp_customize ) {
    
    // Inlcude the Alpha Color Picker control file.
    require_once dirname(__FILE__) . '/../vendor/BraadMartin-alpha-color-picker/alpha-color-picker.php';

    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    //$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

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
    
    //Code to edit default sections
    //$wp_customize->get_section('title_tagline')->title       = __( 'Title', 'blank-canvas' );
    //$wp_customize->get_section('title_tagline')->description = __( 'Description', 'blank-canvas' );
    $wp_customize->get_section('title_tagline')->priority      = 10;
    
    //$wp_customize->get_section('header_image')->title       = __( 'Title', 'blank-canvas' );
    //$wp_customize->get_section('header_image')->description = __( 'Description', 'blank-canvas' );
    $wp_customize->get_section('header_image')->priority      = 20;
    
    //$wp_customize->get_section('background_image')->title     = __( 'Title', 'blank-canvas' );
    $wp_customize->get_section('background_image')->description = __( 'Please Note<br><br>Background Images will be displayed behind the background HTML Canvas elemont', 'blank-canvas' );
    $wp_customize->get_section('background_image')->priority    = 30;
    
    //$wp_customize->get_section('static_front_page')->title       = __( 'Title', 'blank-canvas' );
    //$wp_customize->get_section('static_front_page')->description = __( 'Description', 'blank-canvas' );
    $wp_customize->get_section('static_front_page')->priority      = 290;
    
    /*$wp_customize->get_section('colors')->title       = __( 'Starting Point for All Styles', 'blank-canvas' );
    $wp_customize->get_section('colors')->description = __( 'Please Note<br><br>If any specific styles have been set in other places then those will be used instead of these', 'blank-canvas' );
    $wp_customize->get_section('colors')->priority    = 300;
    $wp_customize->remove_control('background_color');
    $wp_customize->remove_control('header_textcolor');*/
    $wp_customize->remove_section('colors');
    
    //$wp_customize->get_section('custom_css')->title       = __( 'Title', 'blank-canvas' );
    //$wp_customize->get_section('custom_css')->description = __( 'Description', 'blank-canvas' );
    $wp_customize->get_section('custom_css')->priority      = 310;
    
    $wp_customize->add_section( 'content' , array(
        'title'    => 'Content',
        'priority' => 40,
    ) );
    
    $wp_customize->add_section( 'base_section' , array(
        'title'       => __('Base Styles', 'blank-canvas'),
        'description' => __('Please Note<br><br>If any specific styles have been set in other places then those will be used instead of these', 'blank-canvas'),
        'priority'    => 5,
    ) );
    
    blank_canvas_register_color_control($wp_customize, "base_text", 'base_section', '#eeeeee', 10, __('Base Text Color', 'blank-canvas'));
    
    blank_canvas_register_color_control($wp_customize, "base_heading1", 'base_section', '#eeeeee', 20, __('Base Heading 1 Color', 'blank-canvas'));
    blank_canvas_register_color_control($wp_customize, "base_heading2", 'base_section', '#eeeeee', 30, __('Base Heading 2 Color', 'blank-canvas'));
    blank_canvas_register_color_control($wp_customize, "base_heading3", 'base_section', '#eeeeee', 40, __('Base Heading 3 Color', 'blank-canvas'));
    blank_canvas_register_color_control($wp_customize, "base_heading4", 'base_section', '#eeeeee', 50, __('Base Heading 4 Color', 'blank-canvas'));
    blank_canvas_register_color_control($wp_customize, "base_heading5", 'base_section', '#eeeeee', 60, __('Base Heading 5 Color', 'blank-canvas'));
    blank_canvas_register_color_control($wp_customize, "base_heading6", 'base_section', '#eeeeee', 70, __('Base Heading 6 Color', 'blank-canvas'));
    
    blank_canvas_register_color_control($wp_customize, "base_background", 'base_section', '#eeeeee', 80, __('Base Background Color', 'blank-canvas'));
    blank_canvas_register_color_control($wp_customize, "base_border", 'base_section', '#eeeeee', 90, __('Base Border Color', 'blank-canvas'));
    
    
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
            #page { background: <?php echo blank_canvas_get_rgba_theme_mod('base_background_color_setting', '#43C6E4'); ?>; }
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
