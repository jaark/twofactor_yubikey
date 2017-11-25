<?php
/**
 * Nextcloud - twofactor_yubikey.
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Jack <site-nextcloud@jack.org.uk>
 * @copyright Jack 2016
 */

namespace OCA\TwoFactor_Yubikey\Service;

use OCP\IUser;

interface IYubiotp
{
    /**
  * @param IUser $user
  */
 public function hasKeyId(IUser $user);

 /**
  * @param IUser $user
  * @param string $kkeyID
  */
 public function setKeyId(IUser $user, $keyID);

 /**
  * @param IUser $user
  */
 public function deleteKeyId(IUser $user, $keyID);

 /**
  * @param IUser $user
  * @param string $otp
  */
 public function validateOTP(IUser $user, $otp);
}
