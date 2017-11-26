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

  <br/><em>If using YubiCloud, Client ID and Secret Key can be requested at <a href="https://upgrade.yubico.com/getapikey/" >https://upgrade.yubico.com/getapikey/</a>. Use the following for Authentication Server Address: <b> api.yubico.com/wsapi/2.0/verify </b></em>
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
      <input id="twofactor_yubikey-auth-server-url" type="text" value="<?php p($_['authServerURL']) ?>" placeholder="api.yubico.com/wsapi/2.0/verify" />
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

  <p>
    <label>
      <em>It is very important to test the configuration. Users may not be able to log in if configuration is invalid.</em><br/>
      <span><?php p($l->t('Verify Configuration ')) ?></span>
      <input id="twofactor_yubikey-test-otp" type="text" value="" placeholder="<?php p($l->t('Insert Yubikey OTP')); ?>" />
    </label>
    <div id="twofactor_yubikey-settings-otpresults" class="msg success inlineblock" style="display: none;"></div>


  </p>
</div>
