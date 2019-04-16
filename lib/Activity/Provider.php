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

namespace OCA\TwoFactorYubikey\Activity;

use InvalidArgumentException;
use OCP\Activity\IEvent;
use OCP\Activity\IProvider;
use OCP\ILogger;
use OCP\IURLGenerator;
use OCP\L10N\IFactory as L10nFactory;


class Provider implements IProvider {

        /** @var L10nFactory */
        private $l10n;

        /** @var IURLGenerator */
        private $urlGenerator;

        /** @var ILogger */
        private $logger;

        public function __construct(L10nFactory $l10n, IURLGenerator $urlGenerator, ILogger $logger) {
                $this->logger = $logger;
                $this->urlGenerator = $urlGenerator;
                $this->l10n = $l10n;
        }

        public function parse($language, IEvent $event, IEvent $previousEvent = null) {
                if ($event->getApp() !== 'twofactor_yubikey') {
                        throw new InvalidArgumentException();
                }

                $l = $this->l10n->get('twofactor_yubikey', $language);

                $event->setIcon($this->urlGenerator->getAbsoluteURL($this->urlGenerator->imagePath('core', 'actions/password.svg')));
                switch ($event->getSubject()) {
                        case 'yubikey_enabled':
                                $event->setSubject($l->t('You enabled Yubikey two-factor authentication for your account'));
                                break;
                        case 'yubikey_device_added':
                                $event->setSubject($l->t('You added a Yubikey two-factor authentication device to your account'));
                                break;
                        case 'yubikey_disabled':
                                $event->setSubject($l->t('You removed the last Yubikey device and disabled Yubikey two-factor authentication for your account'));
                                break;
                        case 'yubikey_device_removed':
                                $event->setSubject($l->t('You removed a Yubikey device from your account'));
                                break;

                }
                return $event;
        }

}
