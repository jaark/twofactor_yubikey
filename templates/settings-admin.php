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
script('twofactor_yubikey', 'settings-admin');
style('twofactor_yubikey', 'settings-admin');
?>

<div id="twofactor_yubikey" class="section">
  <h2 class="inlineblock"><?php p($l->t('Yubikey Configuration')); ?></h2>
  <div id="twofactor_yubikey-settings-msg" class="msg success inlineblock" style="display: none;">Saved</div>

  <p>
    <label>
      <span><?php p($l->t('Client ID')) ?></span>
      <input id="twofactor_yubikey-client-id" type="text" value="<?php p($_['clientID']) ?>" />
    </label>
  </p>

  <p>
    <label>
      <span><?php p($l->t('Secret Key')) ?></span>
      <input id="twofactor_yubikey-secret-key" type="text" value="<?php p($_['secretKey']) ?>" />
    </label>
  </p>

  <p>
    <label>
      <span><?php p($l->t('Authentication  Server')) ?></span>
      <input id="twofactor_yubikey-auth-server-url" type="text" value="<?php p($_['authServerURL']) ?>" />
    </label>
  </p>

  <p>
    <label>
      <span><?php p($l->t('Use HTTPS')) ?></span>
      <input id="twofactor_yubikey-use-https" type="checkbox" <?php if ($_['useHttps']) p('checked') ?> />
    </label>
  </p>

  <p>
    <label>
      <span><?php p($l->t('Validate HTTPS')) ?></span>
      <input id="twofactor_yubikey-validate-https" type="checkbox" <?php if ($_['validateHttps']) p('checked') ?> />
    </label>
  </p>
</div>
