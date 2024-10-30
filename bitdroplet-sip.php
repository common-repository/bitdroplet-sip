<?php

/**
 * Plugin Name
 *
 * @package           Bitdroplet SIP
 * @author            Bitdroplet
 * @copyright         2020 Bitdroplet
 * @license           -----
 *
 * @wordpress-plugin
 * Plugin Name:       Bitdroplet SIP
 * Plugin URI:        https://wordpress.org/plugins/bitdroplet-sip/
 * Description:       Drive visitors to start an SIP in Bitcoin & Earn Money.
 * Version:           1.0.0
 * Requires at least: 2.8.0
 * Requires PHP:      4.3
 * Text Domain:       bitdroplet-sip
 */

// Bitdroplet SIP widget is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 2 of the License, or
// any later version.

// Bitdroplet SIP widget is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.

defined('ABSPATH') or die('No script kiddies please!');

class bitdroplet_sip extends WP_Widget
{
	// Create Multiple WordPress Widgets
	function __construct()
	{
		parent::__construct('bitdroplet_sip', __('Bitdroplet SIP', 'bitdroplet.com'), array(
			'description' => __('WordPress Plugin by bitdroplet for SIP in bitcoin', 'bitdroplet.com')
		));
	}

	public function widget($args, $instance)
	{
		$unique_id = 'bitdroplet_widget_' . uniqid();
		$widget_container =  "<div id=" . $unique_id . "></div>";

		$script = "(function(){
						let configurations = {
							anchor: '#" . $unique_id . "',
							refTag: '" . $instance['refTag'] . "',
							primaryColor: '" . $instance['color'] . "'
						};

						let anchor = document.querySelectorAll(configurations.anchor)[0];
						let iframe = document.createElement('iframe');
					
						iframe.frameBorder = 0;
						iframe.style.border = 'none';
						iframe.style['min-height'] = '270px';
						iframe.name = 'bitdroplet-sip-widget';
						iframe.height = '100%';
						iframe.width = '100%';

						iframe.src = 'https://bitdroplet.com/widget/content.html?v=".time()."&refTag='+ configurations.refTag+'&primaryColor='+configurations.primaryColor;

						anchor.appendChild(iframe);
					})();";
		echo $widget_container;
		wp_register_script('bitdroplet-script', '');
		wp_enqueue_script('bitdroplet-script');
		wp_add_inline_script('bitdroplet-script', $script);
	}

	public function form($instance)
	{
		if (isset($instance['refTag'])) {
			$refTag = $instance['refTag'];
		} else {
			$refTag = '';
		}
		if (isset($instance['color'])) {
			$color = $instance['color'];
		} else {
			$color = '574B90';
		}


?>

		<!--  This is Bitdroplet Widget customize Form -->
		<p>

			<label for="<?php echo $this->get_field_id('refTag'); ?>"> <?php _e('A unique tag for referral tracking. Get your unique referral code from <a href="https://bitdroplet.com/app/#/profile/widget" target="_blank">here</a>'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('refTag'); ?>" name="<?php echo $this->get_field_name('refTag'); ?>" type="text" value="<?php echo esc_attr($refTag); ?>" placeholder="Paste your unique referral code here" />

			<br /> <br />
			<label for="<?php echo $this->get_field_id('color'); ?>"> <?php _e('Hex code for primary color eg. 574B90'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" type="text" value="<?php echo esc_attr($color); ?>" placeholder="Enter hex code without #" />
		</p>

	<?php
	}

	// Updating widget replacing old instances with new
	function update($new_instance, $old_instance)
	{
		$instance                           = array();
		$instance['refTag'] = (!empty($new_instance['refTag'])) ? strip_tags($new_instance['refTag']) : '';
		$instance['color'] = (!empty($new_instance['color'])) ? strip_tags($new_instance['color']) : '574B90';

		return $instance;
	}
}

function bitdroplet_sip()
{
	register_widget('bitdroplet_sip');
}

// Initialize Plugin
add_action('widgets_init', 'bitdroplet_sip');

register_activation_hook(__FILE__, 'bitdroplet_plugin_activation_hook');

function bitdroplet_plugin_activation_hook()
{
	set_transient('bitdroplet-plugin-activation-notice', true, 5);
}

add_action('admin_notices', 'bitdroplet_plugin_activation');

function bitdroplet_plugin_activation()
{

	/* Check transient, if available display notice */
	if (get_transient('bitdroplet-plugin-activation-notice')) {
	?>
		<div class="updated notice is-dismissible">
			<p>Thank you for using this plugin! <strong>You are awesome</strong>.</p>
			<p>Now go to <strong>Widgets</strong> under <strong>Appearance</strong> & Drag Drop <strong><i>Bitdroplet SIP</i></strong> widget to either Sidebar section or wherever you want to show this widget</p>
		</div>
<?php
		/* Delete transient, only display this notice once. */
		delete_transient('bitdroplet-plugin-activation-notice');
	}
}

?>