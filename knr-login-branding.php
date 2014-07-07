<?php
/*
Plugin Name: KNR Login Branding
Description: knr-login-branding enables users to set the WordPress login page's logo, link, and link title
Author: Nitin Reddy Katkam
Author URI: http://www.nitinkatkam.com
Version: 0.1
*/

function knrLoginBranding_changeLoginImage() {
	$lLogoUrl = get_option('knrLoginBranding_logoUrl');
	
	if (!$lLogoUrl) {
		$baseAbsPath = __FILE__;
	
		//to get a relative path from the wordpress folder
		$relativeToWpPath = substr($baseAbsPath, strpos($baseAbsPath, 'wp-content'));
		$relativeToWpPath = substr($relativeToWpPath, 0, strpos($relativeToWpPath, 'knr-login-branding.php'));
		$relativeToWpPath = str_replace('\\','/',$relativeToWpPath); //for Windows hosts
		
		$lLogoUrl = $relativeToWpPath.'login-logo.png';
	}
	
	echo <<<NITINLOGINCSS
	<style type="text/css">
	div#login h1 a {		
		background:transparent url('{$lLogoUrl}') no-repeat scroll center top;
	}
	</style>
NITINLOGINCSS;
}

function knrLoginBranding_loginLogoLink() {
	return bloginfo('siteurl'); // get_option('siteurl');
}

function knrLoginBranding_loginLogoTitle() {
	return bloginfo('name');
}

function menuHandler_knrLoginBranding() {
	echo <<<KNRLOGINBRANDADMIN
	<h1>KNR Login Branding</h1>
	
	<p>The Blog Name and Site URL WordPress settings are used for the link title and link URL, respectively. The logo will be used from the URL below. The recommended image size is 326x67 (in pixels).</p>
	
	<form action="options.php" method="POST">
KNRLOGINBRANDADMIN;
	
	wp_nonce_field('update-options');
	settings_fields('knrLoginBranding');
	$lOptions = get_option('knrLoginBranding_logoUrl');
	
	$saveButtonText = __('Save Changes');
	
	echo <<<KNRLOGINBRANDADMIN
		<label>Logo URL</label>
		<input type="text" name="knrLoginBranding_logoUrl" value="{$lOptions}" size="80" />
		<br clear="all" />

		<p class="submit">
		<input type="submit" class="button-primary" value="{$saveButtonText}" />
		</p>
	</form>
	
	<p>
	For feedback or suggestions, feel free to send an email to k.nitin.r (a) gmail.com (<a href="http://www.nitinkatkam.com">Nitin Reddy Katkam</a>).
	</p>
KNRLOGINBRANDADMIN;
//Use _e('Save Changes') instead of "Save" for localization
}

function menu_knrLoginBranding() {
	add_options_page('knrLoginBranding', 'KNR Login Branding', 8, __FILE__, 'menuHandler_knrLoginBranding');
}

function knrLoginBranding_optionsInit() {
	register_setting('knrLoginBranding', 'knrLoginBranding_logoUrl');
}

add_filter('login_headerurl', 'knrLoginBranding_loginLogoLink');
add_filter('login_headertitle', 'knrLoginBranding_loginLogoTitle');
add_action('login_head', 'knrLoginBranding_changeLoginImage');

if (is_admin()) {
	add_action('admin_menu', 'menu_knrLoginBranding');
	add_action('admin_init', 'knrLoginBranding_optionsInit');
}

?>