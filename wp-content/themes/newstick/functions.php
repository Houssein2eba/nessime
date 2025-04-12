<?php
/**
 * Theme functions and definitions
 *
 * @package NewsTick
 */

/**
 * After setup theme hook
 */
function newstick_theme_setup(){
    /*
     * Make child theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_child_theme_textdomain( 'newstick' );	
}
add_action( 'after_setup_theme', 'newstick_theme_setup' );

/**
 * Load assets.
 */

function newstick_theme_css() {
	wp_enqueue_style( 'newstick-parent-theme-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'newstick_theme_css', 99);

/*=========================================
 NewsTick Customize Panel from parent theme
=========================================*/
function newstick_change_parent_setting( $wp_customize ) {
	$wp_customize->get_control('slider_options_head')->label = __( 'Slider Content Middle', 'newstick' );
	$wp_customize->get_control('newsmunch_hs_slider_left')->label = __( 'Hide/Show Slider Middle?', 'newstick' );
	$wp_customize->get_control('slider_mdl_options_head')->label = __( 'Slider Content Left', 'newstick' );
	$wp_customize->get_control('newsmunch_hs_slider_mdl')->label = __( 'Hide/Show Slider Left?', 'newstick' );
}
add_action( 'customize_register', 'newstick_change_parent_setting',99 );

/**
 * Import Options From Parent Theme
 *
 */
function newstick_parent_theme_options() {
	$newsmunch_mods = get_option( 'theme_mods_newsmunch' );
	if ( ! empty( $newsmunch_mods ) ) {
		foreach ( $newsmunch_mods as $newsmunch_mod_k => $newsmunch_mod_v ) {
			set_theme_mod( $newsmunch_mod_k, $newsmunch_mod_v );
		}
	}
}
add_action( 'after_switch_theme', 'newstick_parent_theme_options' );