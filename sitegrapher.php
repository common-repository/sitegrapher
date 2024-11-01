<?php
/*
Plugin Name: SiteGrapher
Plugin URI: http://www.sitegrapher.com/
Description: The official SiteGrapher WordPress plugin for page view analytics.
Version: 0.1.1
Author: SiteGrapher
Author URI: http://www.sitegrapher.com
*/

add_option('sitegrapher_siteKey');
add_option('sitegrapher_trackAdmin');
add_option('sitegrapher_trackPreview');
add_option('sitegrapher_trackUser');
add_action('admin_init', 'sitegrapher_init');
add_action('admin_menu', 'sitegrapher_options');
add_filter('plugin_action_links', 'sitegrapher_add_link', 10, 2);
add_action('wp_footer', 'sitegrapher_footer');
sitegrapher_print();

function sitegrapher_init()
{
	wp_register_style('sitegrapher_style',WP_PLUGIN_URL.'/sitegrapher/sitegrapher.css');
}

function sitegrapher_options() {
	$tmpStr = add_options_page('SiteGrapher Settings', 'SiteGrapher', 'manage_options', 'sitegrapher', 'sitegrapher_options_page');
	add_action('admin_print_styles-'.$tmpStr, 'sitegrapher_admin_style');
}

function sitegrapher_add_link($links,$file) {
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	if ($file == $this_plugin) {
		$settings_link = '<a href="options-general.php?page=sitegrapher">'.__("Settings", "SiteGrapher").'</a>';
	 	array_unshift($links, $settings_link);
	}
	return $links;
 }

function sitegrapher_admin_style(){
	wp_enqueue_style('sitegrapher_style');
}

function sitegrapher_footer() {
	?>
	<div style="display: none;"><a href='http://www.sitegrapher.com'>SiteGrapher</a></div>
	<?php
}

function sitegrapher_success($message){
	echo '<div class="sitegrapher_alert"><div class="sitegrapher_success">'.$message.'</div></div>';
}

function sitegrapher_fail($message){
	echo '<div class="sitegrapher_alert"><div class="sitegrapher_fail">'.$message.'</div></div>';
}

function sitegrapher_options_page() {
	if (!current_user_can('manage_options'))  {
	wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	?>
  <div class="wrap">
  <br />
  <div style="width: 100%; height: 50px; background: #004466 url('<?php echo WP_PLUGIN_URL.'/sitegrapher/sitegrapher.png';?>') no-repeat; background-position: left top; border: 1px solid #000000; -webkit-border-radius: 5px; -moz-border-radius: 5px; -o-border-radius: 5px; border-radius: 5px; -webkit-box-shadow: 0px 1px 0px #ffffff; -moz-box-shadow: 0px 1px 0px #ffffff; -o-box-shadow: 0px 1px 0px #ffffff; box-shadow: 0px 1px 0px #ffffff;" id="sitegrapherlogo">
  </div>
    <?php
	
	if(isset($_POST['sitegrapher_siteKey'])){
		// Handle submission
		$siteKey = $_POST['sitegrapher_siteKey'];
		$trackAdmin = isset($_POST['sitegrapher_trackAdmin']) ? $_POST['sitegrapher_trackAdmin'] : 'Yes';
		$trackPreview = isset($_POST['sitegrapher_trackPreview']) ? $_POST['sitegrapher_trackPreview'] : "Yes";
		$trackUser = isset($_POST['sitegrapher_trackUser']) ? $_POST['sitegrapher_trackUser'] : 'Username';
		if(preg_match('/[0-9]{8}-[0-9]{8}/', $siteKey)){
			update_option('sitegrapher_siteKey', $siteKey);
			update_option('sitegrapher_trackAdmin', $trackAdmin);
			update_option('sitegrapher_trackPreview', $trackPreview);
			update_option('sitegrapher_trackUser', $trackUser);
			echo "<br />";
			sitegrapher_success('Settings updated successfully');
		}
		else{
			echo "<br />";
			sitegrapher_fail('Site key not a valid format.');
		}
	}
	
	$siteKey = get_option('sitegrapher_siteKey');
	$trackAdmin = get_option('sitegrapher_trackAdmin');
	$trackPreview = get_option('sitegrapher_trackPreview');
	$trackUser = get_option('sitegrapher_trackUser');
	
	if(!$siteKey) $default_text = 'XXXXXXXX-XXXXXXXX';
	else $default_text = $siteKey;
	
	if(!$trackAdmin) $trackAdmin = 'Yes';
	
	if(!$trackPreview) $trackPreview = 'Yes';
	
	if(!$trackUser) $trackUser = 'Username';
	?>
		<h2>Settings</h2>
		<form name="gs-options" action="" method = "post">
			<table border="0" cellspacing="10" cellpadding="5">
				<tr><td>Site key: </td><td colspan=3><input type="text" name="sitegrapher_siteKey" value = "<?=$default_text?>" onclick="if(this.value=='<?=$default_text?>')this.value=''" onblur="if(this.value=='')this.value='<?=$default_text?>'"/></td></tr>
				<tr><td>Track admin pages: </td><td><input type="radio" name="sitegrapher_trackAdmin" value="Yes" id="trackAdmin" <?php if($trackAdmin == 'Yes') echo 'checked="checked" '; ?>/> Yes</td><td><input type="radio" name="sitegrapher_trackAdmin" value="No" id="trackAdmin" <?php if($trackAdmin == 'No') echo 'checked="checked" '; ?>/> No</td></tr>
				<tr><td>Track post preview pages: </td><td><input type="radio" name="sitegrapher_trackPreview" value="Yes" id="trackPreview" <?php if($trackPreview == 'Yes') echo 'checked="checked" '; ?>/> Yes</td><td><input type="radio" name="sitegrapher_trackPreview" value="No" id="trackPreview" <?php if($trackPreview == 'No') echo 'checked="checked" '; ?>/> No</td></tr>
				<!-- tr><td>Track users by: </td><td><input type="radio" name="sitegrapher_trackUser" value="Off" id="trackUser" <?php if($trackUser == 'Off') echo 'checked="checked" '; ?>/> Off</td><td><input type="radio" name="sitegrapher_trackUser" value="UserID" id="trackUser" <?php if($trackUser == 'UserID') echo 'checked="checked" '; ?>/> User ID</td><td><input type="radio" name="sitegrapher_trackUser" value="Username" id="trackUser" <?php if($trackUser == 'Username') echo 'checked="checked" '; ?> /> Username</td><td><input type="radio" name="sitegrapher_trackUser" value="DisplayName" id="trackUser" <?php if($trackUser == 'DisplayName') echo 'checked="checked" '; ?>/> Display Name</td></tr -->
			</table>
			<input type="submit" value="Save" />
		</form>
	</div>
	
	<?php
	

}

function sitegrapher_print(){
	$siteKey = get_option('sitegrapher_siteKey');
	if (!$siteKey) {
		return;
	}
	$trackAdmin = get_option('sitegrapher_trackAdmin');
	$trackPreview = get_option('sitegrapher_trackPreview');
	if ($trackAdmin == 'No' && is_admin()) {
		return;
	}
	if (isset($_GET['preview']) && $_GET['preview'] == 'true' && $trackPreview == 'No') {
		return;
	}
	$organizationKey = substr($siteKey,0,8);
	$params = array("organizationKey" => $organizationKey,"siteKey" => $siteKey);
	wp_enqueue_script('sitegrapher', WP_PLUGIN_URL .'/sitegrapher/sitegrapher.js', '', false, true);
	wp_localize_script('sitegrapher', 'SiteGrapherParams', $params);
}

?>
