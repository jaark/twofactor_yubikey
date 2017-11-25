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

    <table id="yubikey-devices">
    <tr><td>
    <label>Registered YubiKey(s) </label></td>
    <td id='yubikeys'> 
    <!-- Yubikeys will be here -->
	</td></tr>
     </table>
  <p>
    <label>
      <span><?php p($l->t('Add New Yubikey')) ?></span>
      <input id="twofactor_yubikey-yubikey-id" type="text" value="<?php p($_['yubikeyID']) ?>" placeholder="<?php p($l->t('Insert Yubikey OTP')); ?>" />
    </label>
  </p>

</div>
