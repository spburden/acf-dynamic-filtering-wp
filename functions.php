<?php
//https://www.bobz.co/dynamically-populate-select-fields-choice-in-advanced-custom-fields/
// ADD THIS TO FUNCTIONS FILE
function acf_load_make_field_choices( $field ) {
    // reset choices
    $field['choices'] = array();

    $choices = ['hello', 'bye'];

    $years = get_field('vehicle_year', 'option' );
    foreach ($years as $key => $value) {
        $year[] = $value['vehicle_year'];
     }
    echo "HELLOOOO".$years;
    if ($year == 2000) {
        foreach( $choices as $choice )
    	{
    		$field['choices'][ $choice ] = $choice;
    	}
    }
    // return the field
    return $field;
}

add_filter('acf/load_field/name=vehicle_make', 'acf_load_make_field_choices');

function acf_admin_enqueue( $hook ) {

  $type = get_post_type(); // Check current post type
  $types = array( 'gallery' ); // Allowed post types

  if( !in_array( $type, $types ) )
      return; // Only applies to post types in array

  wp_enqueue_script( 'populate-area', get_stylesheet_directory_uri() . '/autopopulate.js' );

  wp_localize_script( 'populate-area', 'pa_vars', array(
        'pa_nonce' => wp_create_nonce( 'pa_nonce' ), // Create nonce which we later will use to verify AJAX request
      )
  );
}

add_action( 'admin_enqueue_scripts', 'acf_admin_enqueue' );
add_action( 'wp_enqueue_scripts', 'acf_admin_enqueue');

// Return areas by country
function makes_by_year($selected_vehicle_year) {

  // Verify nonce
  if( !isset( $_POST['pa_nonce'] ) || !wp_verify_nonce( $_POST['pa_nonce'], 'pa_nonce' ) )
    die('Permission denied');

  // Get country var
  $selected_vehicle_year = $_POST['vehicle_year'];

  $makes = array(
      "2000" => array("Two Thousand", "Douze Thow"),
      "2001" => array("Two Thousand ONE", "1", "Two Thousand 1")
  );

  // Returns Area by Country selected if selected country exists in array
  if (array_key_exists($selected_vehicle_year, $makes)) {

    // Convert areas to array
    $arr_data = $makes[$selected_vehicle_year];
    return wp_send_json($arr_data);

  } else {

    $arr_data = array("2000","no", "maybe");
    return wp_send_json($arr_data);
  }

  die();
}
add_action('wp_ajax_pa_add_areas', 'makes_by_year');
add_action('wp_ajax_nopriv_pa_add_areas', 'makes_by_year');
?>
