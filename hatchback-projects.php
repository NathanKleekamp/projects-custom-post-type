<?php
/*
Plugin Name: Projects Custom Post Type
Plugin URI: https://github.com/NathanKleekamp
Description: A simple Projects custom post type with a WP REST API (v2) endpoint.
Version: 1.0
License: MIT
Author: Nathan Kleekamp
Text Domain: hatchback-projects
*/

$slug = plugin_basename( __FILE__ );

function hatchback_projectscreate_cpt() {
  register_post_type( 'projects',
    array(
      'labels' => array(
        'name' => 'Projects',
        'singular_name' => 'Project',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Project',
        'edit' => 'Edit',
        'edit_item' => 'Edit Project',
        'new_item' => 'New Project',
        'view' => 'View',
        'view_item' => 'View Project',
        'search_items' => 'Search Projects',
        'not_found' => 'No Projects found',
        'not_found_in_trash' => 'No Projects found in Trash',
        'parent' => 'Parent Project'
      ),

      'public' => true,
      'menu_position' => 5,
      'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt', 'revisions' ),
      'menu_icon' => plugins_url( 'projects.png', __FILE__ ),
      'show_in_rest' => true
    )
  );
}
add_action( 'init', 'hatchback_projectscreate_cpt' );


//* Utility function to get feature image array with src and alt tags
function hatchback_projectsreturn_feature_image_url_string( $post_id=null ) {
  $thumb = get_post_thumbnail_id( $post_id );
  $alt = get_post_meta( $thumb, '_wp_attachment_image_alt', true );
  $src = wp_get_attachment_image_src($thumb, 'full-size', false)[0];

  return array('alt' => $alt, 'src' => $src);
}


//* Add featured_media_url field to 'post' get request
function hatchback_projectsregister_featured_media_url() {
  $args = array(
    'get_callback' => 'hatchback_projectsget_featured_media_url',
    'update_callback' => null,
    'schema' => null
  );

  register_rest_field( 'projects', 'featured_media_object', $args );
}


function hatchback_projectsget_featured_media_url( $object, $field_name, $request ) {
  $post_thumb = get_post_thumbnail_id( $object['id'] );
  $thumb_url_array = wp_get_attachment_image_src($post_thumb, 'large', true);

  return hatchback_projectsreturn_feature_image_url_string( $object['id'] );
}

add_action( 'rest_api_init', 'hatchback_projectsregister_featured_media_url' )

?>
