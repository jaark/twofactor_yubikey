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

namespace OCA\TwoFactor_Yubikey\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\Settings\ISettings;

class Admin implements ISettings {
        public function __construct() {
                $this->config = new \OCA\TwoFactor_Yubikey\TwoFactor_YubikeyConfig(\OC::$server->getConfig());
        }


        /**
         * @return TemplateResponse
         */
        public function getForm() {
          $response = new TemplateResponse('twofactor_yubikey', 'settings-admin');

          $response->setParams ([
            'clientID' => $this->config->getClientID(),
            'secretKey' => $this->config->getSecretKey(),
            'authServerURL' => $this->config->getAuthServerURL(),
            'useHttps' => $this->config->getUseHttps(),
            'validateHttps' => $this->config->getValidateHttps(),
          ]);
          return $response;
        }

        /**
         * @return string the section ID, e.g. 'sharing'
         */
        public function getSection() {
                return 'security';
        }

        /**
         * @return int whether the form should be rather on the top or bottom of
         * the admin section. The forms are arranged in ascending order of the
         * priority values. It is required to return a value between 0 and 100.
         *
         * E.g.: 70
         */
        public function getPriority() {
                return 40;
        }

}
