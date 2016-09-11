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

namespace OCA\TwoFactor_Yubikey\Db;

use OCP\AppFramework\Db\Entity;

/**
 * @method string getUserId()
 * @method void setUserId(string $userId)
 * @method string getYubikeyId()
 * @method void setYubikeyId(string $yubikeyId)
 */
class KeyID extends Entity {

    /** @var string */
    protected $userId;

    /** @var string */
    protected $yubikeyId;

}
