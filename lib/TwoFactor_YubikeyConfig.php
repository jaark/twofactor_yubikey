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

 namespace OCA\TwoFactor_Yubikey;

 use OCP\IConfig;

/**
 * Class Config
 *
 * read/write config of the password policy
 *
 * @package OCA\TwoFactor_Yubikey
 */
class TwoFactor_YubikeyConfig {

  /** @var IConfig */
  private $config;

  /**
   * Config constructor.
   *
   * @param IConfig $config
   */
  public function __construct(IConfig $config) {
          $this->config = $config;
  }

  /**
   * get the authentication server client ID
   *
   * @return string
   */
  public function getClientID() {
          $clientID = $this->config->getAppValue('twofactor_yubikey', 'clientID', '');
          return (string)$clientID;
  }

  /**
   * set the authentication server client ID
   *
   * @param string $clientID
   */
  public function setClientID($clientID) {
          $this->config->setAppValue('twofactor_yubikey', 'clientID', $clientID);
  }

  /**
   * get the authentication server secret key
   *
   * @return string
   */
  public function getSecretKey() {
          $secretKey = $this->config->getAppValue('twofactor_yubikey', 'secretKey', '');
          return (string)$secretKey;
  }

  /**
   * set the authentication server secret key
   *
   * @param string $secretKey
   */
  public function setSecretKey($secretKey) {
          $this->config->setAppValue('twofactor_yubikey', 'secretKey', $secretKey);
  }

  /**
   * get the authentication server URL
   *
   * @return string
   */
  public function getAuthServerURL() {
          $authServerURL = $this->config->getAppValue('twofactor_yubikey', 'authServerURL', 'api2.yubico.com/wsapi/2.0/verify');
          return (string)$authServerURL;
  }

  /**
   * set the authentication server url
   *
   * @param string $authServerURL
   */
  public function setAuthServerURL($authServerURL) {
          $this->config->setAppValue('twofactor_yubikey', 'authServerURL', $authServerURL);
  }

  /**
   * get the use HTTPS flag
   *
   * @return boolean
   */
  public function getUseHttps() {
          $useHttps = $this->config->getAppValue('twofactor_yubikey', 'useHttps', 'true') === 'true';
          return (boolean)$useHttps;
  }

  /**
   * set the use HTTPS flag
   *
   * @param boolean $useHttps
   */
  public function setUseHttps($useHttps) {
          $this->config->setAppValue('twofactor_yubikey', 'useHttps', $useHttps);
  }

    /**
     * get the validate HTTPS flag
     *
     * @return boolean
     */
    public function getValidateHttps() {
            $validateHttps = $this->config->getAppValue('twofactor_yubikey', 'validateHttps', 'true') === 'true';
            return (boolean)$validateHttps;
    }

    /**
     * set the validate HTTPS flag
     *
     * @param boolean $validateHttps
     */
    public function setValidateHttps($validateHttps) {
            $this->config->setAppValue('twofactor_yubikey', 'validateHttps', $validateHttps);
    }
}
