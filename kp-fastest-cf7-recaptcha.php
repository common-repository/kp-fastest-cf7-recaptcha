<?php
/*
Plugin Name: KP Fastest Contact Form 7 Recaptcha V3
Description: Speeds up websites that use Contact Form 7 Recaptcha V3. Built by Kreativo Pro WordPress Speed Specialists.
Version: 1.0.0
Contributors: kreativopro
Author: Kreativo Pro
Author URI: https://www.kreativopro.com/
License: GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: kp-fastest-cf7-recaptcha
Domain Path:  /languages
*/


/* If this file is called directly, abort. */
if ( ! defined( 'WPINC' ) ) {
	die;
}


/* Define KPFCF7R_BASENAME as a PHP Constant */
define ( 'KPFCF7R_BASENAME', plugin_basename( __FILE__ ) );


/* Add Link to plugins.php Page and Rearrange Settings Links */
function kpfcf7r_setting_links( $actions, $plugin_file ) 
{
	static $plugin;

	if (!isset($plugin))
		$plugin = KPFCF7R_BASENAME;
	
	if ($plugin == $plugin_file)
	{
		$site_link = array('support' => '<b><a href="https://www.kreativopro.com/contact-us/" target="_blank" style="color:#d30c5c">Speed Up Your Website</a></b>');
		
    	$actions = array_merge($site_link, $actions);
	}
		
	return $actions;
}
add_filter( 'plugin_action_links', 'kpfcf7r_setting_links', 10, 2 );


/* Remove Enqueue Script Action Coming from Contact Form 7 */
function kpfrecap_wpcf7_manage_hooks()
{
    remove_action( 'wp_enqueue_scripts', 'wpcf7_recaptcha_enqueue_scripts', 10 );
}
add_action( 'setup_theme', 'kpfrecap_wpcf7_manage_hooks' );


/* Write JS code to Enqueue Recaptcha Script only when Needed */
function kp_fastest_cf7_recaptcha()
{
    $kprecapinstance = WPCF7_RECAPTCHA::get_instance();
    $kprecapkey = $kprecapinstance->get_sitekey();

?>
<script>
var kpdetectcf7form = document.getElementsByClassName('wpcf7');
if (kpdetectcf7form.length > 0)
{
    ['mouseover','keydown','touchmove','touchstart'].forEach(function(event){document.getElementsByClassName("wpcf7")[0].addEventListener(event,kpfastestcf7recaptcha,{passive:!0})});
}
function kpfastestcf7recaptcha()
{
    var kpcf7recaptchaScript = document.createElement("script");
    kpcf7recaptchaScript.src = "https://www.google.com/recaptcha/api.js?render=<?php echo $kprecapkey; ?>&#038;ver=3.0";
    document.body.appendChild(kpcf7recaptchaScript);
    ['mouseover','keydown','touchmove','touchstart'].forEach(function(event){document.getElementsByClassName("wpcf7")[0].removeEventListener(event,kpfastestcf7recaptcha,{passive:!0})});
}
</script>
<?php
}
add_action('wp_footer', 'kp_fastest_cf7_recaptcha');