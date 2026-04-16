var nwOTPForm = {
    data:{},
    OTPKEY:"OTPData",
    countdownTime:60,// Set the initial countdown time in seconds (e.g., 60 seconds)
    init:function(){
        nwOTPForm.handleResendClick();

        var okey = 'OTPData';
        var formParams = $( 'textarea[name="formParams"]' ).val();
        formParams = formParams ? JSON.parse( formParams ) : {};

        var ud = $.fn.cProcessForm.localStore( okey, {}, {}, 'get' );
        if( ud && ud.otp ){
            formParams[ 'device_otp' ] = ud.otp;
            $( 'textarea[name="formParams"]' ).val( JSON.stringify( formParams ) );
        }

        $( 'form#usersOTPForm' ).on( 'submit', function(){
            var otp = $(this).find( 'input[name="otp"]' ).val();
            $.fn.cProcessForm.localStore( nwOTPForm.OTPKEY, { "otp" : $.fn.cProcessForm.calculateMD5( otp ) }, {}, 'put' );
        });
    },
    updateTimer: function( time ){
        $("#countdownTimer").text( "in " + time + " seconds" );
    },
    handleResendClick: function(){
        var cd = nwOTPForm.countdownTime;

        // Disable the resend button to prevent spamming
        $("#otp-click-act").prop("disabled", true).css("cursor", "not-allowed");

        // Start the countdown timer
        var timer = setInterval(function() {
            cd--;
            nwOTPForm.updateTimer( cd );

            // When the countdown reaches 0, re-enable the resend button
            if (cd <= 0) {
                clearInterval(timer);
                $("#countdownTimer").text(""); // Clear the timer text
                $("#otp-click-act").prop("disabled", false).css("cursor", "pointer");
            }
        }, 1000); // 1000 milliseconds = 1 second

    }
};

nwOTPForm.init();