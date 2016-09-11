<?php
/**
 * Nextcloud - twofactor_yubikey
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Jack <site-nextcloud@jack.org.uk>
 * @copyright Jack 2016
 */

namespace OCA\TwoFactor_Yubikey\AppInfo;

use OCP\AppFramework\App;

$app = new Application();


\OCP\App::registerAdmin('twofactor_yubikey', 'settings/settings-admin');
\OCP\App::registerPersonal('twofactor_yubikey', 'settings/settings-personal');

$TwoFactor_YubikeyConfig = new \OCA\TwoFactor_Yubikey\TwoFactor_YubikeyConfig(\OC::$server->getConfig());
