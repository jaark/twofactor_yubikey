/**
 * Nextcloud - twofactor_yubikey
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Jack <site-nextcloud@jack.org.uk>
 * @copyright Jack 2016
 */

 var twofactor_yubikeySetting = {

        save: function(parameter, value) {
          OC.msg.startSaving('#twofactor_yubikey-settings-msg');

          OC.AppConfig.setValue('twofactor_yubikey', parameter, value);
          OC.msg.finishedSaving('#twofactor_yubikey-settings-msg',
            {
              'status': 'success',
              'data': {
                'message': OC.L10N.translate('twofactor_yubikey', 'Saved')
              }
            }
          );
        }
};

function testYubiKeyOTP(otp)
{
        
        var url = OC.generateUrl('/apps/twofactor_yubikey/settings/testotp');

       
        var testing = $.ajax(url, {
            method: 'POST',
            data: {
                otp: otp
            }
        });
         $.when(testing).done(function(data) {
         if( data.success)
         {
            OC.msg.finishedSaving('#twofactor_yubikey-settings-otpresults', {
                'status': 'success',
                'data': {
                    'message':'Success! OTP Verified! Configuration is good.'
                }
            });
         } 
         else
         {
             OC.msg.finishedSaving('#twofactor_yubikey-settings-otpresults', {
                'status': 'failure',
                'data': {
                    'message':'OTP Failed Validation! Verify Configuration and try again.'
                }
            });
         }
              
    });
}

$(document).ready(function(){
  $('#twofactor_yubikey-client-id').keyup(function (e) {
    if (e.keyCode == 13) {
      twofactor_yubikeySetting.save('clientID', $(this).val());
    }
  }).focusout(function (e) {
    twofactor_yubikeySetting.save('clientID', $(this).val());
  });

  $('#twofactor_yubikey-secret-key').keyup(function (e) {
    if (e.keyCode == 13) {
      twofactor_yubikeySetting.save('secretKey', $(this).val());
    }
  }).focusout(function (e) {
    twofactor_yubikeySetting.save('secretKey', $(this).val());
  });

  $('#twofactor_yubikey-auth-server-url').keyup(function (e) {
    if (e.keyCode == 13) {
      twofactor_yubikeySetting.save('authServerURL', $(this).val());
    }
  }).focusout(function (e) {
    twofactor_yubikeySetting.save('authServerURL', $(this).val());
  });

  $('#twofactor_yubikey-use-https').change(function (e) {
    twofactor_yubikeySetting.save('useHttps', this.checked);
  });

  $('#twofactor_yubikey-validate-https').change(function (e) {
    twofactor_yubikeySetting.save('validateHttps', this.checked);
  });

 $('#twofactor_yubikey-test-otp').keyup(function (e) {
    if (e.keyCode == 13) {
      testYubiKeyOTP($(this).val());
      $(this).val("");
    }
  }); 


});
