<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/31/19
 * Time: 14:57
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'PPW_Default_PW_Migrations' ) ) {
  class PPW_Default_PW_Migrations {

  	public static function migrate_v_2_6_0() {
  		error_log( 'Migrate Default Password' );
	    $free_service = new PPW_Password_Services();
	    $free_service->migrate_default_password();
	    PPW_Options_Services::get_instance()->add_flag( PPW_Constants::MIGRATED_DEFAULT_PW );
	    error_log( 'Migrated OK' );
    }

  }
}
