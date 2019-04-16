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


namespace OCA\TwoFactorYubikey\Service;

use OC;
use OCA\TwoFactorYubikey\Provider\YubikeyProvider;
use OCP\Authentication\TwoFactorAuth\IRegistry;
use OCP\IUser;
use OCA\TwoFactorYubikey\Db\YubiKey;
use OCA\TwoFactorYubikey\Db\YubiKeyMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\Authentication\TwoFactorAuth\TwoFactorException;

use OCP\Activity\IManager;
use OCP\ILogger;
use OCP\IRequest;
use OCP\ISession;

require_once __DIR__ . '/../../vendor/auth_yubico/Yubico.php';

class Yubiotp implements IYubiotp {

	/** @var YubiKeyMapper */
	private $YubiKeyMapper;

	/** @var ISession */
	private $session;

	/** @var ILogger */
	private $logger;

	/** @var IRequest */
	private $request;

	/** @var IManager */
	private $activityManager;

	/** @var IRegistry */
	private $providerRegistry;

	public function __construct(YubiKeyMapper $yubiKeyMapper,
								ISession $session,
								ILogger $logger,
								IRequest $request,
								IManager $activityManager,
								IRegistry $providerRegistry) {
		$this->yubiKeyMapper = $yubiKeyMapper;

		$this->session = $session;
		$this->logger = $logger;
		$this->request = $request;
		$this->activityManager = $activityManager;
		$this->providerRegistry = $providerRegistry;
	}

	/**
	 * @param IUser $user
	 */
	public function hasKey(IUser $user) {
		try {
			$keys = $this->yubiKeyMapper->getYubikeys($user);
		} catch (DoesNotExistException $ex) {
			return false;
		}
		return true;
	}


	/**
	 * @param IUser $user
	 */
	public function getYubikeys(IUser $user) {
		try {
			$keys = $this->yubiKeyMapper->getYubikeys($user);
			return $keys;
		} catch (DoesNotExistException $ex) {
			return array();
		}
	}

	/**
	 * @param IUser $user
	 * @param string $keyID
	 */
	public function addKey(IUser $user, $otp, $name = "") {

		if (!empty($otp)) {
			$firstKey = false; // Flag to indicate if this is the first Yubikey for the account. Only affects the activity string.
			//Extract the $keyID
			$keyID = substr($otp, 0, 12);

			//First, Let's validate the otp (ensures that configuration is good)
			$testAuth = $this->validateTestOTP($otp);
			if (\PEAR::isError($testAuth)) {
				return $testAuth;
			}

			//Second, let's make sure we're not adding duplicates
			try {
				//Get all the entities
				$UserKeys = $this->yubiKeyMapper->getYubikeys($user);

				foreach ($UserKeys as $key) {
					if ($keyID === $key->getYubikeyId()) {
						//The key is already in the database, no need to add
						return true;
					}
				}

			} catch (DoesNotExistException $ex) {
				$firstKey = true; // No cuurect keys so this is the first one.
			}

			//Add the key to the database
			$dbYubiKey = new YubiKey();

			$dbYubiKey->setUserId($user->getUID());
			$dbYubiKey->setYubikeyId($keyID);
			$dbYubiKey->setYubikeyName($name);

			$this->yubiKeyMapper->insert($dbYubiKey);
			if ($firstKey) {
				$provider = OC::$server->query(YubikeyProvider::class);
				$this->providerRegistry->enableProviderFor($provider, $user);
				$this->publishEvent($user, 'yubikey_enabled');
			} else {
				$this->publishEvent($user, 'yubikey_device_added');
			}
		}

		return true;

	}

	/**
	 * Push an event to the user's activity stream
	 */
	private function publishEvent(IUser $user, string $event) {
		$activity = $this->activityManager->generateEvent();
		$activity->setApp('twofactor_yubikey')
			->setType('security')
			->setAuthor($user->getUID())
			->setAffectedUser($user->getUID())
			->setSubject($event);
		$this->activityManager->publish($activity);
	}

	/**
	 * Attempt to delete the pecified key for the user.
	 * Returns true on success, false otherwise.
	 * 
	 * @param IUser $user
	 * @param string $keyID
	 * 
	 * @return boolean
	 */
	public function deleteKeyId(IUser $user, $keyID) {

		if (!empty($keyID)) {
			try {
				//First, findout if the user has the key
				$UserKeys = $this->yubiKeyMapper->getYubikeys($user);

				foreach ($UserKeys as $key) {
					if ($keyID === $key->getYubikeyId()) {
						//Delete the entity
						$this->yubiKeyMapper->delete($key);

						// Provide the activity entry. Checks to see if we have deleted the last key or not.
						try {
							$this->yubiKeyMapper->getYubikeys($user);
							$this->publishEvent($user, 'yubikey_device_removed');
						} catch (DoesNotExistException $ex) {
							$provider = OC::$server->query(YubikeyProvider::class);
							$this->providerRegistry->disableProviderFor($provider, $user);
							$this->publishEvent($user, 'yubikey_disabled');
						}

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
	 * Tests if an OTP can be authenticated against the current server.
	 * Used when registering a key or testing the server configuration.
	 * 
	 * @param string $otp
	 * 
	 * @return mixed PEAR error on error, true otherwise
	 */
	public function validateTestOTP($otp) {
		$config = new \OCA\TwoFactorYubikey\TwoFactorYubikeyConfig(\OC::$server->getConfig());
		$clientID = $config->getClientID();
		$secretKey = $config->getSecretKey();

		$yubi = new \Auth_Yubico($clientID, $secretKey, null, $config->getValidateHttps());

		$serverURL = $config->getAuthServerURL();
		# Only override default URLs if one is secified in the plugin configuration
		if ($serverURL) {
			$yubi->addURLpart($serverURL);
		}
		$auth = $yubi->verify($otp);

		return $auth;

	}


	/**
	 * @param IUser $user
	 * @param string $otp
	 */
	public function validateOTP(IUser $user, $otp) {


		$config = new \OCA\TwoFactorYubikey\TwoFactorYubikeyConfig(\OC::$server->getConfig());

		$keyID = substr($otp, 0, 12);

		try {
			//returns an array of values
			$userKeys = $this->yubiKeyMapper->getYubikeys($user);
		} catch (DoesNotExistException $ex) {
			return false;
		}

		$iskeyinlist = false;
		//Verify that the user key is in the database for the given user
		foreach ($userKeys as $userKey) {
			if ($keyID !== $userKey->getYubikeyId()) {
				continue;
			} else {
				$iskeyinlist = true;
			}
		}

		//This key is not the database, return false
		if (!$iskeyinlist) {
			return false;
		}

		$clientID = $config->getClientID();
		$secretKey = $config->getSecretKey();

		$yubi = new \Auth_Yubico($clientID, $secretKey, null, $config->getValidateHttps());
		$serverURL = $config->getAuthServerURL();
		# Only override default URLs if one is secified in the plugin configuration
		if ($serverURL) {
			$yubi->addURLpart($serverURL);
		}
		$auth = $yubi->verify($otp);

		if (\PEAR::isError($auth)) {
			return false;
		} else {
			return true;
		}
	}
}
