<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file.
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

 /* Load custom fonts to the customiser */
 add_filter( 'generate_typography_default_fonts', function( $fonts ) {
    $fonts[] = 'Fira Sans Condensed';
    return $fonts;
} );

// Gutenberg custom stylesheet
add_theme_support('editor-styles');
add_editor_style( 'style.css' );
add_editor_style( 'editor-style.css' );

// Remove "Archive:", "Category:" etc.
add_filter( 'get_the_archive_title', function ($title) {
      if ( is_category() ) {
              $title = single_cat_title( '', false );
          } elseif ( is_tag() ) {
              $title = single_tag_title( '', false );
          } elseif ( is_author() ) {
              $title = '<span class="vcard">' . get_the_author() . '</span>' ;
          } elseif ( is_tax() ) { //for custom post types
              $title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
          } elseif (is_post_type_archive()) {
              $title = post_type_archive_title( '', false );
          }
      return $title;
  });

// Increase scroll to top speed
add_filter( 'generate_back_to_top_scroll_speed', 'tu_back_to_top_scroll_speed' );
function tu_back_to_top_scroll_speed() {
    return 50; // milliseconds
}

// Add shortcode for reply via mail link
add_shortcode( 'mailto_title', 'mailto_title' );

function mailto_title( $atts ) {
    return esc_attr( get_the_title( get_the_ID() ) );
}

// Add shortcode for inline date
function wpb_date_today($atts, $content = null) {
extract( shortcode_atts( array(
        'format' => ''
    ), $atts ) );

if ($atts['format'] == '') {
$date_time .= date(get_option('date_format'));
}  else {
$date_time .= date($atts['format']);
}
return $date_time;
}
add_shortcode('date','wpb_date_today');

// Replace menu icon with hamburger
add_filter( 'generate_svg_icon_element', function( $output, $icon ) {
    if ( 'menu-bars' === $icon ) {
        $output = '<svg viewBox="0 0 32 32"><path d="M2.453 20.571v2.449c0 2.421 2.428 4.391 5.413 4.391h16.269c2.984 0 5.411-1.97 5.411-4.391v-2.449c1.42-0.448 2.453-1.778 2.453-3.346 0-1.585-1.059-2.927-2.506-3.358-0.28-2.437-1.689-4.683-4.038-6.4-2.539-1.855-5.897-2.877-9.456-2.877s-6.917 1.022-9.456 2.877c-2.35 1.717-3.758 3.963-4.038 6.4-1.447 0.431-2.506 1.773-2.506 3.358 0 1.567 1.033 2.898 2.453 3.346zM27.444 23.020c0 1.24-1.515 2.288-3.308 2.288h-16.269c-1.794 0-3.31-1.048-3.31-2.288v-2.287h22.887v2.287zM16 6.693c5.805 0 10.61 3.068 11.343 7.028h-22.687c0.733-3.96 5.539-7.028 11.344-7.028zM3.505 15.824h24.991c0.773 0 1.401 0.628 1.401 1.401 0 0.774-0.629 1.404-1.401 1.404h-24.991c-0.773 0-1.401-0.63-1.401-1.404 0-0.773 0.629-1.401 1.401-1.401z"></path><path d="M18.838 8.578c-0.572 0-1.037 0.464-1.037 1.035 0 0.567 0.465 1.028 1.037 1.028s1.035-0.461 1.035-1.028c0-0.571-0.464-1.035-1.035-1.035z"></path><path d="M13.164 8.578c-0.571 0-1.035 0.464-1.035 1.035 0 0.567 0.464 1.028 1.035 1.028s1.035-0.461 1.035-1.028c0-0.571-0.464-1.035-1.035-1.035z"></path><path d="M21.674 10.122c-0.57 0-1.033 0.464-1.033 1.035 0 0.567 0.464 1.028 1.033 1.028s1.035-0.461 1.035-1.028c0-0.571-0.465-1.035-1.035-1.035z"></path><path d="M16 10.122c-0.57 0-1.033 0.464-1.033 1.035 0 0.567 0.463 1.028 1.033 1.028s1.034-0.461 1.034-1.028c0-0.571-0.464-1.035-1.034-1.035z"></path><path d="M10.326 10.122c-0.57 0-1.033 0.464-1.033 1.035 0 0.567 0.463 1.028 1.033 1.028 0.571 0 1.035-0.461 1.035-1.028-0-0.571-0.464-1.035-1.035-1.035z"></path></svg>';
    }
    return $output;
}, 10, 2 );

// Add reply link to RSS feed
add_filter( "the_content_feed", "feed_comment_via_email" );

function feed_comment_via_email($content)
{
   $content .= "<p><a href=\"mailto: " . get_the_author_meta('user_email') . "?subject=RE: '" . get_the_title() . "'&body=Post link: " . get_permalink() . "\">Reply via email</a></p>";
   return $content;
}
