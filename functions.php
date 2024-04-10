<?php 
/* Functions

@package	Camborne School of Mines Trust
@author		Seadog IT
@link		https://www.seadog.it
@copyright	Copyright (c) 2024, Seadog IT LTD
@license	GPL-2.0+ */

/* # Table of Contents
  - Enqueue Custom Scripts and Styles
  - Builder Elements
    - Register Custom Elements
    - Add Text Strings to Builder
	- WordPress Admin Customisations
    - Disable Theme and Plugin Editor
    - Theme Support
    - Remove User Profile Fields
	- Custom Login
    - Style Login Page
    - Change Login Logo URL
    - Change Login Logo Title
    - Remove Langage Dropdown
*/


/* # Enqueue Custom Scripts and Styles
---------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', function() {
	if ( ! bricks_is_builder_main() ) {
		wp_enqueue_style( 'bricks-child', get_stylesheet_uri(), ['bricks-frontend'], filemtime( get_stylesheet_directory() . '/style.css' ) );
	}
} );


/* # Builder Elements
---------------------------------------------------------------------- */
/* *** Register Custom Elements *** */
add_action( 'init', function() {
  $element_files = [
    __DIR__ . '/elements/title.php',
  ];

  foreach ( $element_files as $file ) {
    \Bricks\Elements::register_element( $file );
  }
}, 11 );  

/* ## Add Text Strings to Builder ## */
add_filter( 'bricks/builder/i18n', function( $i18n ) {
  // For element category 'custom'
  $i18n['custom'] = esc_html__( 'Custom', 'bricks' );

  return $i18n;
} );


/* # WordPress Admin Customisations
---------------------------------------------------------------------- */
/* ## Disable Theme and Plugin Editor ## */
define( 'DISALLOW_FILE_EDIT', true );

/* ## Theme Support ## */
add_theme_support( 'custom-logo' );


/* ## Remove User Profile Fields ( ## */
$user = wp_get_current_user();
$allowed_roles = array( 'editor', 'author', 'contributor', 'subscriber' );
if ( array_intersect( $allowed_roles, $user->roles ) ) {
  add_action( 'personal_options', 'remove_user_options' );
  function remove_user_options() { ?>
    <script type="text/javascript">
      jQuery( document ).ready(function( $ ){
        $( '.user-nickname-wrap, .user-display-name-wrap, h2:contains("About Yourself"), .user-description-wrap, .user-profile-picture, .application-passwords' ).remove(); /* remove optional fields */
      } );
    </script> <?php
  }
}


/* # Custom Login
---------------------------------------------------------------------- */
/* ## Style Login Page ## */
add_action( 'login_head', function() {
	echo '<link rel="stylesheet" type="text/css" href="'.get_stylesheet_directory_uri().'/lib/css/login.css" />';
} );

/* ## Change Login Logo URL ## */
add_filter( 'login_headerurl', function() {
	return get_bloginfo( 'url' );
} );

/* ## Change Login Logo Title ## */
add_filter( 'login_headertitle', function() {
	return get_bloginfo( 'name' );
} );

/* ## Remove Langage Dropdown ## */
add_filter( 'login_display_language_dropdown', '__return_false' );