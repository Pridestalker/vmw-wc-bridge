<?php

class Vmw_Wc_Autoloader {
	protected static $helpers = [
		'class-vmw-wc-notices.php'
	];
	
	public static function init() {
		static::helpers();
	}
	
	public static function helpers() {
		foreach (static::$helpers as $helper) {
			/** @noinspection PhpIncludeInspection */
			require_once __DIR__ . '/helpers/' . $helper;
		}
	}
}

