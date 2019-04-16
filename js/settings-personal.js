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

    save: function(name, otp) {
        //if the length is less than 12 don't save
        if( otp.length < 12 )
        {
            return;
        }

        OC.msg.startSaving('#twofactor_yubikey-settings-msg');

        var url = OC.generateUrl('/apps/twofactor_yubikey/settings/addkey');

        $('#twofactor_yubikey-yubikey-name').val("");
        $('#twofactor_yubikey-yubikey-id').val("");
        //we're sending the entire string
        var updating = $.ajax(url, {
            method: 'POST',
            data: {
                otp: otp,
                name: name
            }
        });

         $.when(updating).done(function(data) {

            if( data.success )
            {
                OC.msg.finishedSaving('#twofactor_yubikey-settings-msg', {
                    'status': 'success',
                    'data': {
                        'message': OC.L10N.translate('twofactor_yubikey', 'Saved')
                    }
                });

                 loadKeys();
            }

            else
            {
                OC.msg.finishedSaving('#twofactor_yubikey-settings-msg', {
                    'status': 'failure',
                    'data': {
                        'message': OC.L10N.translate('twofactor_yubikey', 'Key Registration failed. Try again or contact your administrator.')
                    }
                });
            }

        }) ;

       
    }
};


               

function displayYubiKey(item, index)
{
    var template        = '<tr class="yubikey-device" >' + 
                          '<td><span class="icon-yubikey-device"></span></td>'+
                          '<td class="yubikey-name">' +  item.yubikeyName + '</td>' +
                          '<td class="yubikey-id">' +  item.yubikeyId + '</td>' +
                          '<td><a class="icon icon-delete" id=\"'+item.yubikeyId+'\"></a></td>'+
                          '</tr>';

    $('#twofactor_yubikey-table-body').append(template);
    handle = '#'+item.yubikeyId;
    $('#twofactor_yubikey-table-body').one('click', handle, function() { removeYubiKey(item)});
}

function removeYubiKey(key)
{
        OC.msg.startSaving('#twofactor_yubikey-settings-msg');

        var url = OC.generateUrl('/apps/twofactor_yubikey/settings/deletekey');

        var updating = $.ajax(url, {
            method: 'POST',
            data: {
                keyId: key.yubikeyId
            }
        });
         $.when(updating).done(function(data) {

            OC.msg.finishedSaving('#twofactor_yubikey-settings-msg', {
                'status': 'success',
                'data': {
                    'message': OC.L10N.translate('twofactor_yubikey', 'Saved')
                }
            });

            loadKeys();
    });
}

function loadKeys()
{
    $('#twofactor_yubikey-loading').show();
    var url = OC.generateUrl('/apps/twofactor_yubikey/settings/getkeys');
    var loading = $.ajax(url, {
        method: 'GET',
    });
 
    $.when(loading).done(function(data) {

        $('#twofactor_yubikey-table-body').empty();
        $('#twofactor_yubikey-loading').hide();
  
        if( data.keys.length !== 0 )
        {
            $('#twofactor_yubikey-list').show();
            data.keys.forEach(displayYubiKey);
        }
        else
        {
            $('#twofactor_yubikey-list').hide();
        }

    });
}


$(document).ready(function() {

    loadKeys();
    $('#twofactor_yubikey-yubikey-id').keyup(function(e) {
        if (e.keyCode == 13) {
            twofactor_yubikeyid.save($('#twofactor_yubikey-yubikey-name').val(), $(this).val());
           
        }
    }).focusout(function(e) {
        twofactor_yubikeyid.save($('#twofactor_yubikey-yubikey-name').val(), $(this).val());
           
    });

});
