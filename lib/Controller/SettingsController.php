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

namespace OCA\TwoFactorYubikey\Controller;

use OCA\TwoFactorYubikey\Service\IYubiotp;
use OCP\Defaults;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\IUserSession;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;



class SettingsController extends Controller {
        /** @var IYubiotp */
        private $yubiotp;

        /** @var IUserSession */
        private $userSession;

        /** @var Defaults */
        private $defaults;

        /**
         * @param string $appName
         * @param IRequest $request
         * @param IUserSession $userSession
         * @param IYubiotp $yubiotp
         * @param Defaults $defaults
         */
        public function __construct($appName, IRequest $request, IUserSession $userSession, IYubiotp $yubiotp, Defaults $defaults) {
                parent::__construct($appName, $request);
                $this->userSession = $userSession;
                $this->yubiotp = $yubiotp;
                $this->defaults = $defaults;
        }

        /**
         * @NoAdminRequired
         * @param string $otp
         * @param string $name
         * @return JSONResponse
         */
        public function addkey($otp, $name) {
          $user = $this->userSession->getUser();
          if( $this->yubiotp->addKey($user, $otp, $name) )
          {
            return ['success' => true ];
          }
          else
          {
            return ['success' => false];
          }
        }

        /**
         * @NoAdminRequired
         * @param string $keyId Ybikey ID
         * @return JSONResponse
         */
        public function deletekey($keyId) {
          $user = $this->userSession->getUser();
          if( $this->yubiotp->deleteKeyId($user, $keyId) ){
             return ['success' => true ];
          }
          else {
             return ['success' => false ];
          }

        }

        /**
         * @NoAdminRequired
         * @return JSONResponse
         */
        public function getkeys() {
          $user = $this->userSession->getUser();
          $keys = $this->yubiotp->getYubikeys($user);
          $out = array();

          foreach ($keys as $key) {
                  $out[] = $key->outputArray();
          }

          return array('keys' => $out);
        }


        /**
         * @NoAdminRequired
         * @param string $otp
         * @return JSONResponse
         */
        public function testotp($otp) {

          if( $this->yubiotp->validateTestOTP($otp) ){
             return ['success' => true ];
          }
          else {
             return ['success' => false ];
          }

        }
}
