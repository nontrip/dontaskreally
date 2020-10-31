<?php

/*
 * Created on Jan 5, 2013
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class GwpfSetupModel {
	
    function __construct() {

	}
	function getFaviconState() {
		global $wpdb;
		$favicon = array();
		$faviconName = get_option('gwpf_favicon');
		$apple_faviconName = get_option('gwpf_favicon_preserve_on_apple');
		$promotepage = get_option('gwpf_show_webid');
		if(isset($faviconName) && $faviconName != "") {
			$favicon['state'] = 1 ;
			$favicon['name'] = $faviconName ;
			// Adding backward compatibility for PHP 4
			// $date = new DateTime();
			// $favicon['timestamp'] = $date->getTimestamp();
			$favicon['timestamp'] = time() . 'gwpf' ;
			if(isset($apple_faviconName) && $apple_faviconName != "") {
				$favicon['apple_name'] = $apple_faviconName ;
			}
			if(isset($promotepage) && $promotepage != "") {
				$favicon['show_webid'] = $promotepage ;
			}
		}
		return $favicon ;
	}

	function savePhotoToUploadFolder($photo, $doPreserve, $doPromote) {

		$photoId = "favicon";
		$errorOccured = false; 
		$errorMsg = "" ;
		if ($photo["type"] == "image/gif") {
			$photoEXT = ".gif";
			$pType = 0;
		}
		elseif ($photo["type"] == "image/x-icon") {
			$photoEXT = ".ico";
			$pType = 1;
		}
		elseif ($photo["type"] == "image/png") {
			$photoEXT = ".png";
			$pType = 2;
		}
		list ($width, $height) = getimagesize($photo["tmp_name"]);
		if (!isset ($pType)) {
			$errorMsg .= __("Upload of profile photo failed: Image object is empty or invalid image type (Only .ico, .png, .png allowed). ", 'genie-wp-favicon') ;
			$errorOccured = true;
		} else if($photo["size"] > (100 * 2048)) {
			$errorMsg .=  __("Upload of profile photo failed: Image size greater than 200 Kb. ", 'genie-wp-favicon') ;
			$errorOccured = true;
		} else if($width > 128 || $height > 128) {
			$errorMsg .=  __("Upload of profile photo failed: Image width or height greater than 128. ", 'genie-wp-favicon') ;
			$errorOccured = true;
		} else {
			if ($photo["error"] > 0) {
				$errorMsg .= ("Upload of favicon failed. Error Code: " . $photo["error"]);
				$errorOccured = true;
			} else {
				$uploadURL = GWPF_FAVICON_DIR . URL_S ;
				$photo["name"] = $photoId . $photoEXT;
				if($doPreserve)
					$photo["apple_name"] = $photoId . '-precomposed' . $photoEXT;

				if (!is_dir(WP_CONTENT_DIR . URL_S . 'uploads')) {
					mkdir(WP_CONTENT_DIR . URL_S . 'uploads', 0755);
				}

				if (!is_dir(GWPF_FAVICON_DIR)) {
					mkdir(GWPF_FAVICON_DIR, 0755);
				}

				if (!is_dir($uploadURL)) {
					mkdir($uploadURL, 0755);
				}

				if (file_exists($uploadURL . $photoId . ".gif")) {
					unlink($uploadURL . $photoId . ".gif");
				}
				if (file_exists($uploadURL . $photoId . ".ico")) {
					unlink($uploadURL . $photoId . ".ico");
				}
				if (file_exists($uploadURL . $photoId . ".png")) {
					unlink($uploadURL . $photoId . ".png");
				}
				if (file_exists($uploadURL . $photoId . '-precomposed' . ".gif")) {
					unlink($uploadURL . $photoId . '-precomposed' . ".gif");
				}
				if (file_exists($uploadURL . $photoId . '-precomposed' . ".ico")) {
					unlink($uploadURL . $photoId . '-precomposed' . ".ico");
				}
				if (file_exists($uploadURL . $photoId . '-precomposed' . ".png")) {
					unlink($uploadURL . $photoId . '-precomposed' . ".png");
				}
				
				move_uploaded_file($photo["tmp_name"], $uploadURL . $photo["name"]);
				update_option('gwpf_favicon', $photo["name"]);
				if($doPreserve) {
					if (copy($uploadURL . $photo["name"], $uploadURL . $photo["apple_name"])) {
						update_option('gwpf_favicon_preserve_on_apple', $photo["apple_name"]);
					} else {
						$errorMsg .= __("Failed to create processed Apple Favicon.");
						$errorOccured = true;
					}
				} else {
					delete_option("gwpf_favicon_preserve_on_apple");
				}
				if($doPromote) {
					update_option('gwpf_show_webid', 1);
				} else {
					delete_option("gwpf_show_webid");
				}
				unset ($photo["tmp_name"]);
			}
		}
		
		if($errorOccured) {
			echo '<div class="error"><p>' . $errorMsg . '</p></div>' ;
		}
		
		return $photo;
	}
	
	function removeGWPFDetails() {
		delete_option("gwpf_favicon") ;
	}
}