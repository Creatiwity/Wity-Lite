<?php 
/**
 * WTools.php
 */

defined('IN_WITY') or die('Access denied');

/**
 * WTools contains some tiny helpful functions.
 * 
 * @package System\WCore
 * @author Johan Dufau <johan.dufau@creatiwity.net>
 * @version 0.5.0-dev-09-01-2013
 */
class WTools {
	/**
	 * Removes accents from a string.
	 * 
	 * @param string $string
	 * @return string
	 */
	public static function stripAccents($string) {
		return strtr(
			utf8_decode($string), 
			utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 
			'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'
		);
	}
	
	/**
	 * Verifies whether a string is a valid email.
	 * 
	 * @param string $string
	 * @return bool
	 */
	public static function isEmail($string) {
		return (!empty($string) && preg_match('#^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$#i', $string));
	}
}

?>
