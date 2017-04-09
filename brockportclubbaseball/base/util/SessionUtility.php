<?php
namespace base\util;

use base\SessionRegistry;

class SessionUtility {
	public static function isLoggedIn() {
		return SessionRegistry::instance()->getLoggedInStatus();
	}
}
?>