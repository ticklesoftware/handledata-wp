<?php
/*
Plugin Name: Handle Data
Plugin URI: https://handledata.io/
Description: 
Author: Tickle Group
Author URI: https://tickle.group
*/

// Specify Hooks/Filters
register_activation_hook(__FILE__, 'add_defaults_fn');
add_action('admin_init', 'sampleoptions_init_fn' );
add_action('admin_menu', 'sampleoptions_add_page_fn');
// if (get_option('handledata_api_key')) {
add_action('wp_head', 'handledata_add_script', -1000);
// }        


function handledata_add_script() {
    $options = get_option('plugin_options');
    if ($options['handledata_api_key'] && $options['handledata_privacy'] && $options['handledata_cookies'] ) { ?>
    <script>  var api_key = '<?= $options['handledata_api_key'] ?>';  var server = 'https://handledata.io';  var cookie = '<?= $options['handledata_cookies'] ?>';  var domain = '<?= get_site_url() ?>';  var privacy = '<?= $options['handledata_privacy'] ?>';  var d = document['getElementsByTagName']('script')[0x0];  var e = document['createElement']('script');  e['src'] = 'https://handle.fra1.digitaloceanspaces.com/handle.js';  d['parentNode']['insertBefore'](e, d);</script><div id="handle-wrapper" class="with-cookie"></div>

<?php }
}
// Define default option settings
function add_defaults_fn() {
	$tmp = get_option('plugin_options');
    if(($tmp['chkbox1']=='on')||(!is_array($tmp))) {
		$arr = array("dropdown1"=>"Orange", "text_area" => "Space to put a lot of information here!", "text_string" => "Some sample text", "pass_string" => "123456", "chkbox1" => "", "chkbox2" => "on", "option_set1" => "Triangle");
		update_option('plugin_options', $arr);
	}
}

// Register our settings. Add the settings section, and settings fields
function sampleoptions_init_fn(){
	register_setting('plugin_options', 'plugin_options', 'plugin_options_validate' );
	add_settings_section('main_section', '  ', 'section_text_fn', __FILE__);
    add_settings_field('handledata_api_key', 'Domain API Key', 'setting_pass_fn', __FILE__, 'main_section');
    add_settings_field('handledata_dropdown_privacy', 'Privacy policy page', 'setting_dropdown_privacy_fn', __FILE__, 'main_section');
    add_settings_field('handledata_dropdown_cookies', 'Cookies policy page', 'setting_dropdown_cookies_fn', __FILE__, 'main_section');



}

// Add sub page to the Settings Menu
function sampleoptions_add_page_fn() {
// add optiont to main settings panel
 add_menu_page('Handle Data', 'HandleData', 'manage_options', __FILE__, 'options_page_fn');

}

// ************************************************************************************************************


// PASSWORD-TEXTBOX - Name: plugin_options[pass_string]
function setting_pass_fn() {
    $options = get_option('plugin_options');
	echo "<input id='handledata_api_key' name='plugin_options[handledata_api_key]' size='90' type='password' value='{$options['handledata_api_key']}' />";
}
function  setting_dropdown_privacy_fn() {
	$options = get_option('plugin_options');
    $items = get_pages();
    echo "<select id='drop_down1' name='plugin_options[handledata_privacy]'>";
	foreach($items as $item) {
		$selected = ($options['handledata_privacy']==get_page_link( $item->ID )) ? 'selected="selected"' : '';
		echo "<option value='" . get_page_link( $item->ID ) . "' $selected>$item->post_title - " . get_page_link( $item->ID ) . "</option>";
	}
	echo "</select>";
}

function  setting_dropdown_cookies_fn() {
	$options = get_option('plugin_options');
	$items = get_pages();
    echo "<select id='drop_down1' name='plugin_options[handledata_cookies]'>";
	foreach($items as $item) {
		$selected = ($options['handledata_cookies']==get_page_link( $item->ID )) ? 'selected="selected"' : '';
		echo "<option value='" . get_page_link( $item->ID ) . "' $selected>$item->post_title - " . get_page_link( $item->ID ) . "</option>";
	}
	echo "</select>";
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function wpet_validate_options($input) {
	// Sanitize textarea input (strip html tags, and escape characters)
	//$input['textarea_one'] = wp_filter_nohtml_kses($input['textarea_one']);
	//$input['textarea_two'] = wp_filter_nohtml_kses($input['textarea_two']);
	//$input['textarea_three'] = wp_filter_nohtml_kses($input['textarea_three']);
	return $input;
}
// Display the admin options page
function options_page_fn() {
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Handle Data</h2>
		If you are not already registered and set up, head over to <a href="https://handledata.io" target="_blank">https://handledata.io</a> and register in order to receive your domain key
		<form action="options.php" method="post">
					<?php
if ( function_exists('wp_nonce_field') ) 
	wp_nonce_field('plugin-name-action_' . "yep"); 
?>
		<?php settings_fields('plugin_options'); ?>
		<?php do_settings_sections(__FILE__); ?>
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
		</p>
		</form>
	</div>
<?php
}

// Validate user data for some/all of your input fields
function plugin_options_validate($input) {
	// Check our textbox option field contains no HTML tags - if so strip them out
	$input['text_string'] =  wp_filter_nohtml_kses($input['text_string']);	
	return $input; // return validated input
}