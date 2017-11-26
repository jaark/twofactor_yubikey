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
        //if the length is less than 12 don't save
        if( value.length < 12 )
        {
            return;
        }

        OC.msg.startSaving('#twofactor_yubikey-settings-msg');

        var url = OC.generateUrl('/apps/twofactor_yubikey/settings/setid');

        
        $('#twofactor_yubikey-yubikey-id').val("");
        //we're sending the entire string
        var updating = $.ajax(url, {
            method: 'POST',
            data: {
                otp: value
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
    var template        = '<div class="yubikey-device" >' + 
                          '<span class="icon-yubikey-device"></span>'+
                          '<span>'+item+ 
                          '<a class="icon icon-delete" id=\"'+item+'\"></a>'+
                          '</div>';

    $('#yubikeys').append(template);
    handle = '#'+item;
    $('#yubikeys').one('click', handle, function() { removeYubiKeyID(item)});
}

function removeYubiKeyID(id)
{
        OC.msg.startSaving('#twofactor_yubikey-settings-msg');

        var url = OC.generateUrl('/apps/twofactor_yubikey/settings/deleteid');

        var updating = $.ajax(url, {
            method: 'POST',
            data: {
                keyId: id
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
    $('#yubikeys').empty();
    $('#yubikeys').append('<h3>Loading...</h3>'); 
    var url = OC.generateUrl('/apps/twofactor_yubikey/settings/getids');
    var loading = $.ajax(url, {
        method: 'GET',
    });


    $.when(loading).done(function(data) {

        $('#yubikeys').empty();
        values = data.keyId instanceof Array ? data.keyId : [data.keyId];

        if( values.length !== 0 )
        {
            $('#yubikey-devices').show(); 
            values.forEach(displayYubiKey);
        }
        else
        {
            $('#yubikey-devices').hide(); 
        }

    });
}


$(document).ready(function() {

    loadKeys();
    $('#twofactor_yubikey-yubikey-id').keyup(function(e) {
        if (e.keyCode == 13) {
            twofactor_yubikeyid.save($(this).val());
           
        }
    }).focusout(function(e) {
        twofactor_yubikeyid.save($(this).val());
           
    });

});
