<?php
/**
 * Nextcloud - twofactor_yubikey.
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Jack <site-nextcloud@jack.org.uk>
 * @copyright Jack 2016
 */

namespace OCA\TwoFactorYubikey\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\Mapper;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDbConnection;
use OCP\IUser;

class YubiKeyMapper extends Mapper
{
    public function __construct(IDbConnection $db)
    {
        parent::__construct($db, 'twofactor_yubikey');
    }

    /**
     * @param IUser $user
     *
     * @throws DoesNotExistException
     *
     * @return YubiKey
     */
    public function getYubikeyId(IUser $user)
    {
        /* @var $qb IQueryBuilder */
      $qb = $this->db->getQueryBuilder();

        $qb->select('id', 'user_id', 'yubikey_id')
          ->from('twofactor_yubikey')
          ->where($qb->expr()->eq('user_id', $qb->createNamedParameter($user->getUID())));
        $result = $qb->execute();

        $row = $result->fetch();
        $result->closeCursor();

        if ($row === false) {
            throw new DoesNotExistException('User has no Yubikeys');
        }

        return YubiKey::fromRow($row);
    }

     /**
     * @param IUser $user
     *
     * @throws DoesNotExistException
     *
     * @return YubiKey[]
     */
    public function getYubikeys(IUser $user,$limit=null,$offset=null)
    {
      $sql = 'SELECT * FROM `*PREFIX*twofactor_yubikey` WHERE `user_id` = ?';
      $entities = $this->findEntities($sql,[$user->getUID()],$limit,$offset); 
      if(empty($entities))
      {
        throw new DoesNotExistException('User has no Yubikeys');
      }

      return $entities;
    }

}
