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

namespace OCA\TwoFactorYubikey\Db;

use OCP\AppFramework\Db\Entity;

/**
 * @method string getUserId()
 * @method void setUserId(string $userId)
 * @method string getYubikeyId()
 * @method void setYubikeyId(string $yubikeyId)
 * @method string getYubikeyName()
 * @method void setYubikeyName(string $yubikeyName)
 */
class YubiKey extends Entity {

    /** @var string */
    protected $userId;

    /** @var string */
    protected $yubikeyId;

    /** @var string */
    protected $yubikeyName;

    public function outputArray() {
        return array(
            'id' => $this->id,
            'userId' => $this->userId,
            'yubikeyId' => $this->yubikeyId,
            'yubikeyName' => $this->yubikeyName
            );    
    }

}
