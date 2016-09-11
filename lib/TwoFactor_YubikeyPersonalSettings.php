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
 use OCA\TwoFactor_Yubikey\Service\IYubiotp;
 use OCP\Defaults;
 use OCP\IRequest;
 use OCP\IURLGenerator;
 use OCP\IUserSession;

/**
 * Class Config
 *
 * read/write config of the password policy
 *
 * @package OCA\TwoFactor_Yubikey
 */
class TwoFactor_YubikeyPersonalSettings {

  /** @var IYubiotp */
  private $yubiotp;

  /** @var IUserSession */
  private $userSession;

  /**
   * Config constructor.
   *
   * @param IConfig $config
   */
  public function __construct(IUserSession $userSession, IYubiotp $yubiotp) {
          $this->yubiotp = $yubiotp;
          $this->userSession = $userSession;
  }

  /**
   * get the authentication server client ID
   *
   * @return string
   */
  public function getYubikeyId() {
      $user = $this->userSession->getUser();
          $yubikeyId = $this->yubiotp->getKeyId($user);
          return (string)$yubikeyID;
  }
}
