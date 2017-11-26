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

namespace OCA\TwoFactor_Yubikey\Controller;

use OCA\TwoFactor_Yubikey\Service\IYubiotp;
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
         * @return JSONResponse
         */
        public function setid($otp) {
          $user = $this->userSession->getUser();
          if( $this->yubiotp->setKeyId($user, $otp) )
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
         * @param string $keyId
         * @return JSONResponse
         */
        public function deleteid($keyId) {
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
        public function getids() {
          $user = $this->userSession->getUser();
          $keyId = $this->yubiotp->getKeyIds($user);
         
          return ['keyId' => $keyId ];
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
