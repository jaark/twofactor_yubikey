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

$config = new \OCA\TwoFactor_Yubikey\TwoFactor_YubikeyConfig(\OC::$server->getConfig());

$template = new OCP\Template('twofactor_yubikey', 'settings-admin');

$template->assign('clientID', $config->getClientID());
$template->assign('secretKey', $config->getSecretKey());
$template->assign('authServerURL', $config->getAuthServerURL());
$template->assign('useHttps', $config->getUseHttps());
$template->assign('validateHttps', $config->getValidateHttps());

return $template->fetchPage();
