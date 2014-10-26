<?php
function setup_n_theme_admin_menus() {
    add_submenu_page('options-general.php',
        '_n Settings', '_n settings', 'manage_options',
        '_n-settings', '_n_settings');
}

function _n_settings() {
    if (!current_user_can('manage_options')) {
	    wp_die('You do not have sufficient permissions to access this page.');
	}
	
	if (isset($_POST["update_settings"])) {
    	$ga_id = sanitize_text_field($_POST["ga_id"]);
    	update_option("_n_ga_id", $ga_id);
    	?>
    	<div id="message" class="updated">Settings saved</div>
    	<?php
	}
	$ga_id = get_option("_n_ga_id");
	?>
    <div class="wrap">
        <?php screen_icon('themes'); ?> <h2>Google Analytics</h2>
 
        <form method="POST" action="">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
			 	    <label for="ga_id">
				        Google Analytics ID
				    </label>
                    </th>
                    <td>
                        <input type="text" name="ga_id" size="15" value="<?php echo $ga_id;?>" />
                        <input type="hidden" name="update_settings" value="Y" />
                    </td>
                </tr>
            </table>
            <input type="submit" value="Save settings" class="button-primary"/>
        </form>
    </div>	
	<?php

}
 
// This tells WordPress to call the function named "setup_theme_admin_menus"
// when it's time to create the menu pages.
add_action("admin_menu", "setup_n_theme_admin_menus");