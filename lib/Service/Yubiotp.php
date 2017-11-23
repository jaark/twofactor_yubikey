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


namespace OCA\TwoFactor_Yubikey\Service;

use OCP\IUser;
use OCA\TwoFactor_Yubikey\Db\KeyID;
use OCA\TwoFactor_Yubikey\Db\KeyIDMapper;
use OCP\AppFramework\Db\DoesNotExistException;

require_once __DIR__ . '/../../vendor/auth_yubico/Yubico.php';


class Yubiotp implements IYubiotp {

 /** @var KeyIDMapper */
 private $keyIDMapper;

 public function __construct(KeyIDMapper $keyIDMapper) {
   $this->keyIDMapper = $keyIDMapper;
 }
  /**
 * @param IUser $user
 */
 public function hasKeyId(IUser $user) {
   try {
     $this->keyIDMapper->getYubikeyId($user);
   } catch (DoesNotExistException $ex) {
     return false;
   }
   return true;
 }

 /**
* @param IUser $user
*/
public function getKeyId(IUser $user) {
  try {
    $keyId = $this->keyIDMapper->getYubikeyId($user);
  } catch (DoesNotExistException $ex) {
    return "";
  }
  return $keyId->getYubikeyId();
}

/**
 * @param IUser $user
 * @param string $kkeyID
 */
 public function setKeyId(IUser $user, $keyID) {
   $this->deleteKeyId($user);
   if( !empty($keyID) )
   {
      $dbKeyID = new KeyID();

      $dbKeyID->setUserId($user->getUID());
      $dbKeyID->setYubikeyId($keyID);

      $this->keyIDMapper->insert($dbKeyID);
   }
 }

/**
 * @param IUser $user
 */
 public function deleteKeyId(IUser $user) {
  try {
    $dbKeyID = $this->keyIDMapper->getYubikeyId($user);
    $this->keyIDMapper->delete($dbKeyID);
  }  catch (DoesNotExistException $ex) {
  }
 }

/**
 * @param IUser $user
 * @param string $otp
 */
 public function validateOTP(IUser $user, $otp) {


   $config = new \OCA\TwoFactor_Yubikey\TwoFactor_YubikeyConfig(\OC::$server->getConfig());

   $keyID = substr($otp,0,12);

   try {
     $userKey = $this->keyIDMapper->getYubikeyId($user);
   } catch (DoesNotExistException $ex) {
     return false;
   }

   if ($keyID <> $userKey->getYubikeyId()) {
     return false;
   }

   $clientID = $config->getClientID();
   $secretKey = $config->getSecretKey();

   $yubi = new \Auth_Yubico($clientID,$secretKey,$config->getUseHttps(), $config->getValidateHttps());
   $yubi->addURLpart($config->getAuthServerURL()); 
   $auth = $yubi->verify($otp);

   if (\PEAR::isError($auth)) {
     return false;
   } else {
     return true;
   }
 }
}
