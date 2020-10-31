<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://passwordprotectwp.com
 * @since      1.0.0
 *
 * @package    Password_Protect_Page
 * @subpackage Password_Protect_Page/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Password_Protect_Page
 * @subpackage Password_Protect_Page/includes
 * @author     BWPS <hello@preventdirectaccess.com>
 */
class PPW_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		PPW_Options_Services::get_instance()->delete_flag( PPW_Constants::MIGRATED_DEFAULT_PW );
		PPW_Options_Services::get_instance()->delete_flag( PPW_Constants::MIGRATED_FREE_FLAG );
	}

}
