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

 /** @var array $_ */
/** @var OC_L10N $l */
script('twofactor_yubikey', 'settings-personal');
style('twofactor_yubikey', 'settings-personal');
?>

<div id="twofactor_yubikey" class="section">
  <h2 class="inlineblock"><?php p($l->t('Yubikey Configuration')); ?></h2>
  <div id="twofactor_yubikey-settings-msg" class="msg success inlineblock" style="display: none;">Saved</div>
  <span id="twofactor_yubikey-loading"><span class="icon-loading-small twofactor_yubikey-loading-spinner"></span><?php p($l->t('Loading your Yubikeys …')); ?></span>
  <div id="twofactor_yubikey-list" style="display: none;">
    <table id="twofactor_yubikey-table">
      <thead class="twofactor_yubikey-table-header">
        <tr>
            <th></th>
            <th>Name</th>
            <th>Key ID</th>
            <th></th>
        </tr>
      </thead>
      <tbody id="twofactor_yubikey-table-body">
      </tbody>
    </table>
  </div>

  <p>
    <label>
      <span><?php p($l->t('Add New Yubikey')) ?></span>
      <input id="twofactor_yubikey-yubikey-name" type="text" value="" placeholder="<?php p($l->t('Yubikey Name')); ?>" />
      <input id="twofactor_yubikey-yubikey-id" type="text" value="" placeholder="<?php p($l->t('Insert Yubikey OTP')); ?>" />
    </label>
  </p>

</div>
