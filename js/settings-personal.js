/**
 * Nextcloud - twofactor_yubikey
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Jack <site-nextcloud@jack.org.uk>
 * @copyright Jack 2016
 */

var twofactor_yubikeyid = {

    save: function(value) {
        OC.msg.startSaving('#twofactor_yubikey-settings-msg');

        var url = OC.generateUrl('/apps/twofactor_yubikey/settings/setid');

        value = value.substring(0, 12);
        $('#twofactor_yubikey-yubikey-id').val(value);

        var updating = $.ajax(url, {
            method: 'POST',
            data: {
                keyId: value
            }
        });


        OC.msg.finishedSaving('#twofactor_yubikey-settings-msg', {
            'status': 'success',
            'data': {
                'message': OC.L10N.translate('twofactor_yubikey', 'Saved')
            }
        });
    }
};

$(document).ready(function() {

    this._loading = true;

    var url = OC.generateUrl('/apps/twofactor_yubikey/settings/getid');
    var loading = $.ajax(url, {
        method: 'GET',
    });

    var _this = this;
    $.when(loading).done(function(data) {
        $('#twofactor_yubikey-yubikey-id').val(data.keyId);
    });

    $('#twofactor_yubikey-yubikey-id').keyup(function(e) {
        if (e.keyCode == 13) {
            twofactor_yubikeyid.save($(this).val());
        }
    }).focusout(function(e) {
        twofactor_yubikeyid.save($(this).val());
    });

});
