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
     $this->keyIDMapper->getYubikeyIds($user);
   } catch (DoesNotExistException $ex) {
     return false;
   }
   return true;
 }


/**
* @param IUser $user
*/
public function getKeyIds(IUser $user) {
   $ret_val = array();
  try {
    $keyIds = $this->keyIDMapper->getYubikeyIds($user);
    foreach($keyIds as $value)
    {
      array_push($ret_val, $value->getYubikeyId());
    }

  } catch (DoesNotExistException $ex) {
    return $ret_val;
  }

  return $ret_val;
}
/**
 * @param IUser $user
 * @param string $keyID
 */
 public function setKeyId(IUser $user, $otp) {

   if( !empty($otp) )
   {
     //Extract the $keyID
     $keyID = substr($otp,0,12);

     //First, Let's validate the otp (ensures that configuration is good)
     if(!$this->validateTestOTP($otp) )
     {
        return false;
     }

     //Second, let's make sure we're not adding duplicates
     try
     {
          //Get all the entities
          $UserKeys = $this->keyIDMapper->getYubikeyIds($user);

          foreach ($UserKeys as $keyid)
          {
            if( $keyID === $keyid->getYubikeyId() )
            {
              //The key is already in the database, no need to add
              return true;
            }
          }

     } catch (DoesNotExistException $ex) {
        //we're good here, go through
      }

      //Add the key to the database
     $dbKeyID = new KeyID();

     $dbKeyID->setUserId($user->getUID());
     $dbKeyID->setYubikeyId($keyID);

     $this->keyIDMapper->insert($dbKeyID);
  }

  return true;

 }

/**
 * @param IUser $user
 * @param string $kkeyID
 */
 public function deleteKeyId(IUser $user, $keyID) {

    if( !empty($keyID))
    {
      try
      {
          //First, findout if the user has the key
          $UserKeys = $this->keyIDMapper->getYubikeyIds($user);

          foreach ($UserKeys as $keyid)
          {
            if( $keyID === $keyid->getYubikeyId() )
            {
              //Delete the entity
              $this->keyIDMapper->delete($keyid);
              return true;
            }
          }
      } catch (DoesNotExistException $ex) {
      return false;
      }

    }

    return false;
 }

/**
 * @param IUser $user
 * @param string $otp
 */
public function validateTestOTP($otp)
{
   $config = new \OCA\TwoFactor_Yubikey\TwoFactor_YubikeyConfig(\OC::$server->getConfig());
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


/**
 * @param IUser $user
 * @param string $otp
 */
 public function validateOTP(IUser $user, $otp) {


   $config = new \OCA\TwoFactor_Yubikey\TwoFactor_YubikeyConfig(\OC::$server->getConfig());

   $keyID = substr($otp,0,12);

   try {
     //returns an array of values
     $userKeys = $this->keyIDMapper->getYubikeyIds($user);
   } catch (DoesNotExistException $ex) {
     return false;
   }

   $iskeyinlist = false;
   //Verify that the user key is in the database for the given user
   foreach($userKeys as $userKey)
   {
      if ($keyID !== $userKey->getYubikeyId()) {
        continue;
      }
      else
      {
        $iskeyinlist = true;
      }
   }

   //This key is not the database, return false
   if(!$iskeyinlist)
   {
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
