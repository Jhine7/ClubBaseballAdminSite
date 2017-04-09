<?php
namespace base\util;

use base\SessionRegistry;
use domain\Admin;
use domain\User;

class UserUtility {
	public static function getLoggedInUser() {
        $loggedInAdmin = Admin::findAdminById(SessionRegistry::instance()->getAdminId());
        return $loggedInAdmin;
	}
}
?>