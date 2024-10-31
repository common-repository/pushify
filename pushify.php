<?php 
/*
Plugin Name: Pushify
Plugin URI: https://pushify.com
Description: Send Unlimited Free Push Notifications to Web Browsers, Chrome, firefox and safari web push notifications, mobile browser push notifications
Version: 1.0 
Author: Intelligent Idiots
Author URI: https://pushify.com/about-us
*/

define( 'PLUGIN_PATH', plugin_dir_url( __FILE__ ) );

function pushify_add_menu()
{
	add_menu_page( 'Pushify', 'Pushify', 'manage_options', 'pushify', 'pushify_options', PLUGIN_PATH. 'logo.ico');
	add_action( 'admin_init', 'pushify_options_register' );
}

add_action('admin_menu', 'pushify_add_menu');

function pushify_options_register()
{
	register_setting( 'pushify_account_key_settings', 'pushify_account_key' );
	register_setting( 'pushify_account_key_settings', 'pushify_subdomain_key' );
	register_setting( 'pushify_account_key_settings', 'pushify_category_key' );
	register_setting( 'pushify_account_key_settings', 'pushify_confirm_key' );
}

function pushify_options()
{
	if(get_option( 'pushify_account_key'))
	{
		$response = json_decode(file_get_contents('https://pushify.com/api/v1/customer/'.get_option( 'pushify_account_key').'/default/category'), true);
		if(!$response){
			$url = 'https://pushify.com/api/v1/customer/'.get_option( 'pushify_account_key').'/default/category';
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			$contents = curl_exec($ch);
			$response = json_decode($contents, true);
			curl_close($ch);
			if(!$response){
				$response['category'] = "default";
			}
		}
	}


	

	echo '<div class="wrap">
		<div id="welcome-panel" class="welcome-panel">
			<div class="welcome-panel-content">
				<h3>Pushify.com Settings</h3>
				<p>Please give your account key in bellow input field which insert your pushify account code automatically to your wordpress site. Popup appear to your visitors once you saved your account key. At present we supports default category only.</p>
				<div class="welcome-panel-column-container">
					<form method="post" action="options.php">
					';
					settings_fields( 'pushify_account_key_settings' ); 
    				do_settings_sections( 'pushify_account_key_settings' ); 
    		if(get_option('pushify_confirm_key')==2)
    		{
    			echo '<table cellpadding=5>
    					<tr>
    						<td>
    							<label>Your Account Key: </label>
    						</td>
    						<td>
    							<input type="text" style="padding: 9px 10px 9px 10px;width: 220px;" class="input-text" id="title" autocomplete="off" placeholder="Enter Account Key" value="'.get_option( 'pushify_account_key' ).'" required disabled>
    							<input type="hidden" value="'.get_option( 'pushify_account_key' ).'" name="pushify_account_key">
    							<input type="hidden" value="'.$response['category'].'" name="pushify_category_key">
    							<input type="hidden" value="0" name="pushify_confirm_key">
    						</td>
    					</tr>
    					<tr>
							<td>
								<label>Your Subdomain URL: </label>
							</td>
							<td>
								<input type="text" style="padding: 9px 10px 9px 10px;width: 220px;"class="input-text" id="title" autocomplete="off" placeholder="https://example.pushify.com" value="'.get_option( 'pushify_subdomain_key' ).'" required disabled>
								<input type="hidden" value="'.get_option( 'pushify_subdomain_key' ).'" name="pushify_subdomain_key">
							</td>
						</tr>
    					<tr>
    						<td></td>
    						<td>';    						
    						echo '
    							<button type="submit" name="submit" id="submit" class="button button-primary button-large">Edit</button>';
    					if(get_option( 'pushify_account_key' ) && (get_option( 'pushify_category_key' ) || $response['category']==""))
    					{
    						echo '<p style="color: green;">Successfully Activated</p>';
    					}
    					echo '
    						</td>
    					</tr>						
    				</table>';
    		}

			else if(get_option('pushify_confirm_key')==0)
			{
				echo '<p>To get Account Key and Subdomain URL, Please go to <a href="https://pushify.com" target="_blank">pushify.com</a> > Login > Dashboard > Settings > Account</p>
					<table cellpadding=5>
						<tr>
							<td>
								<label>Your Account Key: </label>
							</td>
							<td>
								<input type="text" style="padding: 9px 10px 9px 10px;width: 220px;" class="input-text" name="pushify_account_key" id="title" autocomplete="off" placeholder="Enter Account Key" value="'.get_option( 'pushify_account_key' ).'" required>
								<input type="hidden" value="'.$response['category'].'" name="pushify_category_key">
								<input type="hidden" value="1" name="pushify_confirm_key">
							</td>
						</tr>
						<tr>
							<td>
								<label>Your Subdomain URL: </label>
							</td>
							<td>
								<input type="text" style="padding: 9px 10px 9px 10px;width: 220px;" class="input-text" name="pushify_subdomain_key" id="title" autocomplete="off" placeholder="https://example.pushify.com" value="'.get_option( 'pushify_subdomain_key' ).'" required>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<button type="submit" name="submit" id="submit" class="button button-large">Save</button>';
						echo '
							</td>
						</tr>						
					</table>';
			}
			else if(get_option('pushify_confirm_key')==1 && ($response['category']=="default" || $response['category']!=NULL))
			{
				$default_category = $response['category'];
				if($response['category'] == "default"){
					$default_category = "";
				}
				echo '<table cellpadding=5>
						<tr>
							<td>
								<label>Your Account Key: </label>
							</td>
							<td>
								<input type="text" style="padding: 9px 10px 9px 10px;width: 220px;" class="input-text" id="title" autocomplete="off" placeholder="Enter Account Key" value="'.get_option( 'pushify_account_key' ).'" required>
								<input type="hidden" value="'.get_option( 'pushify_account_key' ).'" name="pushify_account_key">
								<input type="hidden" value="'.$response['category'].'" name="pushify_category_key">
								<input type="hidden" value="2" name="pushify_confirm_key">
							</td>
						</tr>
						<tr>
							<td>
								<label>Your Subdomain URL: </label>
							</td>
							<td>
								<input type="text" style="padding: 9px 10px 9px 10px;width: 220px;"class="input-text" id="title" autocomplete="off" placeholder="https://example.pushify.com" value="'.get_option( 'pushify_subdomain_key' ).'" required>
								<input type="hidden" value="'.get_option( 'pushify_subdomain_key' ).'" name="pushify_subdomain_key">
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<button type="submit" name="submit" id="submit" class="button button-primary button-large">Confirm</button>
							</td>
						</tr>						
					</table>';
			}
			else
			{
				echo '<table cellpadding=5>
						<tr>
							<td>
								<label>Your Account Key: </label>
							</td>
							<td>
								<input type="text" style="padding: 9px 10px 9px 10px;width: 220px;"class="input-text" name="pushify_account_key" id="title" autocomplete="off" placeholder="Enter Account Key"  value="'.get_option( 'pushify_account_key' ).'" required>
								<input type="hidden" value="'.$response['category'].'" name="pushify_category_key">
								<input type="hidden" value="1" name="pushify_confirm_key">
							</td>
						</tr>
						<tr>
							<td>
								<label>Your Subdomain URL: </label>
							</td>
							<td>
								<input type="text" style="padding: 9px 10px 9px 10px;width: 220px;"class="input-text" name="pushify_subdomain_key" id="title" autocomplete="off" placeholder="https://example.pushify.com"  value="'.get_option( 'pushify_subdomain_key' ).'" required>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<button type="submit" name="submit" id="submit" class="button button-large">Save</button>
								<p style="color: red;">Invalid Account Details, Please check in <a href="https://pushify.com" target="_blank">pushify.com</a> > Login > Dashboard > Settings > Account</p>
							</td>
						</tr>						
					</table>';
			}
				echo '
						<br/>
					</form>
				</div>
			</div>
		</div>
		</div>';
}

function pushify_myscript() {
	if(get_option('pushify_account_key', false) && get_option('pushify_subdomain_key', false))
	{
	?>
		<script type="text/javascript" src="<?php echo get_option('pushify_subdomain_key')?>/script.js?category=<?php echo get_option('pushify_category_key')?>"></script>
	<?php
	}
}
add_action('wp_head', 'pushify_myscript');

?>