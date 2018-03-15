<?php
/**
 * Nextcloud - twofactor_yubikey.
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Jack <site-nextcloud@jack.org.uk>
 * @copyright Jack 2018
 */

namespace OCA\TwoFactor_Yubikey\Activity;

use OCP\Activity\ISetting;
use OCP\IL10N;

class Setting implements ISetting {

        /** @var IL10N */
        private $l10n;

        public function __construct(IL10N $l10n) {
                $this->l10n = $l10n;
        }

        public function canChangeMail() {
                return false;
        }

        public function canChangeStream() {
                return false;
        }

        public function getIdentifier() {
                return 'twofactor_totp';
        }

        public function getName() {
                return $this->l10n->t('Yubikey (Authenticator app)');
        }

        public function getPriority() {
                return 10;
        }

        public function isDefaultEnabledMail() {
                return true;
        }

        public function isDefaultEnabledStream() {
                return true;
        }

}
