@extends('crudbooster::admin_template')
@push('head')
    <style>
        .modal-content {
            -webkit-border-radius: 10px !important;
            -moz-border-radius: 10px !important;
            border-radius: 10px !important;
        }

        .modal-header {
            -webkit-border-radius: 10px 10px 0px 0px !important;
            -moz-border-radius: 10px 10px 0px 0px !important;
            border-radius: 10px 10px 0px 0px !important;
        }

        #passwordStrengthBar {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .progress-bar {
            width: 30%;
            height: 5px;
            background-color: lightgray;
            border-radius: 10px;
            padding: 4px;
            margin-right: 5px;
            transition: background-color 0.3s;
        }

        #bar1.active {
            background-color: red;
            /* Weak */
        }

        #bar2.active {
            background-color: orange;
            /* Strong */
        }

        #bar3.active {
            background-color: #00b350;
            /* Excellent */
        }

        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 55px;
            height: 55px;
            border: 10px solid rgba(253, 43, 43, 0.2);
            border-left-color: #de0303;
            border-radius: 50%;
            animation: spin 0.5s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .accepted {
            color: #00b350;
        }

        .alert-danger-cus {
            background-color: #ffc77d8d;
            border: 0px;
            border-left: 6px solid rgba(255, 174, 0, 0.705);
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-10px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(10px);
            }

            100% {
                transform: translateX(0);
            }
        }

        /* Modal animation keyframes */
        @keyframes fadeIn {
        0% {
            background: rgba(0, 0, 0, 0);
        }
        100% {
            background: rgba(0, 0, 0, 0.7);
        }
        }

        @keyframes fadeOut {
        0% {
            background: rgba(0, 0, 0, 0.7);
        }
        100% {
            background: rgba(0, 0, 0, 0);
        }
        }

        @keyframes roadRunnerIn {
        0% {
            transform: translateX(-1500px) skewX(30deg) scaleX(1.3);
        }
        70% {
            transform: translateX(30px) skewX(0deg) scaleX(0.9);
        }
        100% {
            transform: translateX(0px) skewX(0deg) scaleX(1);
        }
        }

        @keyframes roadRunnerOut {
        0% {
            transform: translateX(0px) skewX(0deg) scaleX(1);
        }
        30% {
            transform: translateX(-30px) skewX(-5deg) scaleX(0.9);
        }
        100% {
            transform: translateX(1500px) skewX(30deg) scaleX(1.3);
        }
        }

        /* Custom modal styling */
        .custom-modal .modal-dialog {
        transform: translateX(-1500px); /* Initial position off screen */
        }

        /* Animation classes */
        .custom-modal.in .modal-dialog {
        animation: roadRunnerIn 0.3s cubic-bezier(0.165, 0.840, 0.440, 1.000) forwards;
        }

        .custom-modal.out .modal-dialog {
        animation: roadRunnerOut 0.5s cubic-bezier(0.165, 0.840, 0.440, 1.000) forwards;
        }

        .modal-backdrop.in {
        animation: fadeIn 0.5s cubic-bezier(0.165, 0.840, 0.440, 1.000) forwards;
        }

        .custom-modal.out ~ .modal-backdrop {
        animation: fadeOut 0.5s cubic-bezier(0.165, 0.840, 0.440, 1.000) forwards;
        }

        .custom-modal-bg{
            background-color: rgba(255, 0, 0, 0.296);
            /* animation: blinkBackdrop 0.5s infinite; */
        }

        .help-icon {
            position: relative; 
            cursor: pointer; 
            font-size: 12px; 
        }

        .custom-popup {
            display: none; 
            position: absolute;
            bottom: 125%; 
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 110, 255, 0.701); 
            color: #fff; 
            padding: 5px 10px; 
            border-radius: 4px; 
            font-size: 12px; 
            white-space: nowrap; 
            z-index: 1000; 
            transition: opacity 0.3s; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .help-icon:hover .custom-popup {
            display: block; 
            opacity: 1; 
        }

    </style>
@endpush
@section('content')
    @php  
        $userData = CRUDBooster::me();
        $today = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        $lastChangePass = \Carbon\Carbon::parse($userData->last_password_updated);
        $needsPasswordChange = $lastChangePass->diffInMonths($today) >= 3;
        $defaultPass = Hash::check('qwerty', $userData->password);
    @endphp

    <div class="modal fade custom-modal" id="tos-modal" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header btn-danger" style="text-center">
                    <h4 class="modal-title" id="modalTitle">
                    @if($defaultPass)
                        <span id="modalTaytol" style="font-size: 95%;"><i class="fa fa-lock"></i>  Please change your password!</span>
                    @endif
                    
                    @if($needsPasswordChange)
                        <span id="modalTaytol2" style="font-size: 95%;"><i class="fa fa-lock"></i> Your password is out of date, please change it!</span>
                    @endif

                    @if(!$needsPasswordChange && !$defaultPass)
                        <span id="modalTaytol2" style="font-size: 95%;"><i class="fa fa-lock"></i> Account change password</span>
                    @endif
                    </h4>
                </div>

                @if($defaultPass)
                <div class="alert alert-danger-cus alert-dismissible" id="warnAlert" role="alert"
                    style="margin: 15px 15px 0px 15px; display:show; padding-top: 1px;">
                    <div class="alert-content">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="media">
                            <div class="media-body">
                                <strong style="color: rgba(255, 174, 0, 0.911); font-size: 120%">
                                    <i class="glyphicon glyphicon-bell"></i> Warning!
                                </strong>
                                <span style="color: rgba(0, 0, 0, 0.801); padding-left: 5px;">
                                    The System detected that you are still using the default password.
                                    Please change it immediately. This is a mandatory change password for our security
                                    safety. Thank you!
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
  
                <form method="POST" id="changePasswordForm">
                    <input style="border-top-right-radius: 4px; border-bottom-right-radius: 4px" type="hidden"
                        name="user_id" id="user_id" value="{{ $userData->id }}">
                    <input style="border-top-right-radius: 4px; border-bottom-right-radius: 4px" type="hidden"
                        name="waive_count" id="waive_count" value="{{ $userData->waive_count }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label style="color: rgba(0, 0, 0, 0.8)" for="current_password">Current Password
                                <small id="curr_pass_used_er"></small>
                                <small id="curr_pass_used_suc"></small>
                            </label>
                            <div class="input-group">
                                <div class="input-group-addon"
                                    style="border-top-left-radius: 4px; border-bottom-left-radius: 4px;">
                                    <span class="glyphicon glyphicon-lock"></span>
                                </div>
                                <input 
                                    type="password" class="form-control inputs pass_curr" id="current_password"
                                    name="current_password" placeholder="Current password"
                                    required>
                                <div class="input-group-addon"
                                    style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;">
                                    <span class="fa fa-eye" id="toggleCurrentPassword" style="cursor: pointer;"></span>
                                </div>
                            </div>
                        </div>
  
                        <div class="form-group">
                            <label style="color: rgba(0, 0, 0, 0.8)" for="new_password">New Password 
                                <i class="fa fa-question-circle text-info help-icon" style="color: rgba(0, 110, 255, 0.701); font-size: 18px;" id="popover-button" data-toggle="popover"><div class="custom-popup"> <i class="fa fa-info-circle"></i> need help? click me.</div></i>
                                <small style="display: show; color:#de0303" id="pass_used_er"></small>
                                <small style="display: none; color:darkorange; font-weight:600;" id="generatedPassNote"> * <i class="fa fa-warning"></i> Warning: Please strictly remember/save your password for your copy.</small>
                                <small id="must_strong" style="display: none; color: #de0303;">* <i
                                    class="fa fa-exclamation-circle"></i>
                                    Password must be strong and pass all the requirements.
                                </small>
                            </label>
                            <div class="input-group" id="generateChangePassTrigger" style="margin-bottom: 12px; display: none;">
                                <div class="input-group-addon"
                                    style="border-top-left-radius: 4px; border-bottom-left-radius: 4px;">
                                    <button style="border: 1px solid lightgray; padding: 1px; padding-right: 4px; border-radius: 3px;" class="text-info"
                                        type="button" onclick="generateStrongPassword()"> <i class="fa fa-cog fa-spin fa-fw text-info"></i> <small><b>Generate</b></small></button>
                                </div>
                                <input
                                    type="text" class="form-control inputs"
                                    id="generated_password" name="generated_password"
                                    placeholder="Click 'generate' button to provide a strong password." readonly>
                                <div class="input-group-addon"
                                    style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;">
                                    <a style="cursor:pointer" onclick="copyPass()"> 
                                        <small class="accepted" id="copy"><b><i class="fa fa-clipboard accepted"></i> Copy</b></small> 
                                        <small class="accepted" id="copied" style="display: none;"><b><i class="fa fa-check accepted"></i> Copied</b></small>
                                    </a>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-addon"
                                    style="border-top-left-radius: 4px; border-bottom-left-radius: 4px;">
                                    <span class="glyphicon glyphicon-lock"></span>
                                </div>
                                <input 
                                    type="password" class="form-control inputs match_pass pass_verif"
                                    id="new_password" name="new_password" oninput="onInputNewPass()"
                                    placeholder="New password" required>
                                <div class="input-group-addon"
                                    style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;">
                                    <span class="fa fa-eye" id="toggleNewPassword" style="cursor: pointer;"></span>
                                </div>
                            </div>
  
                            <div id="passwordStrengthBar" style="margin-top: 10px;">
                                <div class="progress-bar" id="bar1" aria-valuenow="Weak"></div>
                                <div class="progress-bar" id="bar2" aria-valuenow="Medium"></div>
                                <div class="progress-bar" id="bar3" aria-valuenow="Strong"></div>
                            </div>
  
                            <!-- Password strength progress bar -->
                            <div style="margin-top: 10px; color:#de0303;">
                                <div class="progress-text" id="textUppercase"> <i class="fa fa-exclamation-circle"
                                        id="notes_icons"></i>
                                    Must include at least one uppercase letter.</div>
                                <div class="progress-text" id="textLength"> <i class="fa fa-exclamation-circle"
                                        id="notes_icons1"></i>
                                    Minimum length of 8 characters.</div>
                                <div class="progress-text" id="textNumber"> <i class="fa fa-exclamation-circle"
                                        id="notes_icons2"></i>
                                    Must contain at least one number.</div>
                                <div class="progress-text" id="textChar"> <i class="fa fa-exclamation-circle"
                                        id="notes_icons3"></i>
                                    Must include at least one special character.</div>
                            </div>
                        </div>
  
                        <div class="form-group">
                            <label style="color: rgba(0, 0, 0, 0.8)" for="confirm_password">Confirm Password
                                <small id="pass_not_match" style="display: none; color:#de0303;">* <i
                                        class="glyphicon glyphicon-exclamation-sign"></i>
                                    Password did not match!
                                </small>
                                <small id="pass_match" style="display: none; color: #00b350;">* <i
                                    class="fa fa-check"></i>
                                    Confirm password match.
                                </small>
                            </label>
                            <div class="input-group">
                                <div class="input-group-addon"
                                    style="border-top-left-radius: 4px; border-bottom-left-radius: 4px;">
                                    <span class="glyphicon glyphicon-lock"></span>
                                </div>
                                <input
                                    type="password" class="form-control inputs match_pass" id="confirm_password"
                                    name="confirm_password" oninput="onInputConfPass()"
                                    placeholder="Confirm password" required>
                                <div class="input-group-addon"
                                    style="border-top-right-radius: 4px; border-bottom-right-radius: 4px;">
                                    <span class="fa fa-eye" id="toggleConfirmPassword" style="cursor: pointer;"></span>
                                </div>
                            </div>
                        </div>
                    </div>
  
                    <div class="spinner-overlay" id="spinner" style="display: none;">
                        <div class="spinner">
                        </div>
                    </div>
  
                    <div class="modal-footer">
                        @if($needsPasswordChange)
                        <button type="button" class="btn btn-danger" id="btnWaive"><i class="fa fa-refresh"></i>
                            Waive</button>
                        @endif
                        <button type="button" class="btn btn-danger" id="btnSubmit"><i class="fa fa-key"></i>
                            Change password</button>
                        @if(!$needsPasswordChange && !$defaultPass)
                            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closeChangePass()">
                            Cancel</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('bottom')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script> 
        //on load modal pop-up
        $(window).on('load', function() {
            $('#tos-modal').modal('show');

            @if($needsPasswordChange)
                $('#tos-modal').addClass('custom-modal-bg');
            @endif

            @if($defaultPass)
                $('#tos-modal').addClass('custom-modal-bg');
            @endif
        });

        //inputs and button primary restrictions for validations & requirements
        $('#btnSubmit').attr('disabled', true);

        //toggle for current password
        $('#toggleCurrentPassword').on('click', function() {
            togglePasswordVisibility('#current_password', this);
        });

        //toggle for new password
        $('#toggleNewPassword').on('click', function() {
            togglePasswordVisibility('#new_password', this);
        });

        //toggle for confirm password
        $('#toggleConfirmPassword').on('click', function() {
            togglePasswordVisibility('#confirm_password', this);
        });

        //function for eye toggles
        function togglePasswordVisibility(passwordFieldId, toggleIcon) {
            let passwordField = $(passwordFieldId);
            let type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            $(toggleIcon).toggleClass('fa-eye fa-eye-slash');
        }

        //input new password process
        function onInputNewPass(){
            let strength = 0;
            const UserId = $('#user_id').val();
            const newPassVal = $('#new_password').val();
            const ConfPass = $('#confirm_password').val();
            const hasUppercase = /[A-Z]/.test(newPassVal);
            const passLength = newPassVal.length >= 8;
            const hasNumber = /\d/.test(newPassVal);
            const hasSpecialChar = /[^A-Za-z0-9]/.test(newPassVal);

            // Disable submit button by default
            $('#btnSubmit').attr('disabled', true);

            // Update validation messages
            if (hasUppercase) {
                $('#textUppercase').addClass('accepted');
                $('#notes_icons').addClass('fa-check-circle');
                $('#notes_icons').removeClass('fa-exclamation-circle');
            } else {
                $('#textUppercase').removeClass('accepted');
                $('#notes_icons').addClass('fa-exclamation-circle');
                $('#notes_icons').removeClass('fa-check-circle');
            }

            if (passLength) {
                $('#textLength').addClass('accepted');
                $('#notes_icons1').addClass('fa-check-circle');
                $('#notes_icons1').removeClass('fa-exclamation-circle');
            } else {
                $('#textLength').removeClass('accepted');
                $('#notes_icons1').addClass('fa-exclamation-circle');
                $('#notes_icons1').removeClass('fa-check-circle');
            }

            if (hasNumber) {
                $('#textNumber').addClass('accepted');
                $('#notes_icons2').addClass('fa-check-circle');
                $('#notes_icons2').removeClass('fa-exclamation-circle');
            } else {
                $('#textNumber').removeClass('accepted');
                $('#notes_icons2').addClass('fa-exclamation-circle');
                $('#notes_icons2').removeClass('fa-check-circle');
            }

            if (hasSpecialChar) {
                $('#textChar').addClass('accepted');
                $('#notes_icons3').addClass('fa-check-circle');
                $('#notes_icons3').removeClass('fa-exclamation-circle');
            } else {
                $('#textChar').removeClass('accepted');
                $('#notes_icons3').addClass('fa-exclamation-circle');
                $('#notes_icons3').removeClass('fa-check-circle');
            }

            // Reset strength bars
            $('#bar1, #bar2, #bar3').removeClass('active');

            // Calculate strength
            if (hasUppercase) strength++;
            if (passLength) strength++;
            if (hasNumber) strength++;
            if (hasSpecialChar) strength++;

            // Update strength bars
            if (strength === 1) {
                $('#bar1').addClass('active'); 
            } else if (strength === 2) {
                $('#bar1').addClass('active'); 
                $('#bar2').addClass('active'); 
            } else if (strength >= 3) {
                $('#bar1').addClass('active'); 
                $('#bar2').addClass('active'); 
                $('#bar3').addClass('active');
            }

            // Check if password meets all criteria and matches
            if (strength >= 3 && newPassVal === ConfPass) {
                $('#confirm_password').css('border', '1px solid #00b350');
                $('#pass_not_match').hide();
                $('#pass_match').show();
                $('#btnSubmit').attr('disabled', false);
            } else if (newPassVal === ConfPass){
                $('#confirm_password').css('border', '1px solid #00b350');
                $('#pass_not_match').hide();
                $('#pass_match').show();
                $('#btnSubmit').attr('disabled', true);
            } else {
                $('#confirm_password').css('border', '1px solid red');
                $('#pass_not_match').show();
                $('#pass_match').hide();
                $('#btnSubmit').attr('disabled', true);
            }
        }

        //input confirm password process
        function onInputConfPass() {
            const newPassVal = $('#new_password').val();
            const ConfPass = $('#confirm_password').val();

            // Check if passwords match
            if (newPassVal === ConfPass) {
                $('#confirm_password').css('border', '1px solid #00b350');
                $('#pass_not_match').hide();
                $('#pass_match').show();
                // Keep button disabled until all other criteria are met
                if (validatePasswordStrength(newPassVal)) {
                    $('#btnSubmit').attr('disabled', false);
                }
            } else {
                $('#confirm_password').css('border', '1px solid red');
                $('#pass_not_match').show();
                $('#pass_match').hide();
                $('#btnSubmit').attr('disabled', true);
            }
        }

        // password level validations
        function validatePasswordStrength(password) {
            const hasUppercase = /[A-Z]/.test(password);
            const passLength = password.length >= 8;
            const hasNumber = /\d/.test(password);
            const hasSpecialChar = /[^A-Za-z0-9]/.test(password);

            return hasUppercase && passLength && hasNumber && hasSpecialChar;
        }

        //submit change password
        $('#btnSubmit').on('click', function(event) {
            event.preventDefault();

            const UserId = $('#user_id').val();
            const currentPassword = $('#current_password').val();
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#confirm_password').val();

            if (currentPassword == "") {
                $('#spinner').hide();
                $('#curr_pass_used_er').html(
                    '*<span style="color:#de0303"> <i class="glyphicon glyphicon-exclamation-sign"></i> Current password is required!</span>'
                ).show();
                $('#current_password').css('border', '1px solid red');
            } else {
                $('#curr_pass_used_er').hide();
                $('#current_password').css('border', '');
            }

            if (newPassword == "") {
                $('#spinner').hide();
                $('#pass_used_er').html(
                    '*<span style="color:#de0303"> <i class="glyphicon glyphicon-exclamation-sign"></i> New password is required!</span>'
                ).show();
                $('#new_password').css('border', '1px solid red');
            } else {
                $('#pass_used_er').hide();
                $('#new_password').css('border', '');
            }

            if (confirmPassword == "") {
                $('#spinner').hide();
                $('#pass_not_match').html(
                    '*<span style="color:#de0303"> <i class="glyphicon glyphicon-exclamation-sign"></i> Confirm password is required!</span>'
                ).show();
                $('#confirm_password').css('border', '1px solid red');
            } else {
                $('#pass_not_match').hide();
                $('#confirm_password').css('border', '');
            }

            // Return if any of the fields are empty
            if (currentPassword == "" || newPassword == "" || confirmPassword == "") {
                return;
            }

            if (!validatePasswordStrength(newPassword)) {
                $('#must_strong').show();
                $('#new_password').css('border', '1px solid red');
                return;
            }

            $('#spinner').show();

            $.ajax({
                url: 'change-password',
                method: 'POST',
                data: {
                    user_id: UserId,
                    current_password: currentPassword,
                    password: newPassword
                },
                success: function(response) {
                    if (response.currentPassMatch === false) {
                        $('#curr_pass_used_er').html(
                            '*<span style="color:#de0303"> <i class="glyphicon glyphicon-exclamation-sign"></i> Current password did not match!</span>'
                        ).show();
                        $('#curr_pass_used_suc').hide();
                        $('#current_password').css('border', '1px solid red');
                        $('#spinner').hide();
                        $('#must_strong').hide();
                        return; // Exit process if not match
                    } else {
                        $('#curr_pass_used_suc').html(
                            '*<span style="color:#00b350"> <i class="fa fa-check"></i> Current password match.</span>'
                        ).show();
                        $('#must_strong').hide();
                        $('#curr_pass_used_er').hide();
                        $('#current_password').css('border', '1px solid #00b350');
                        $('#spinner').hide();
                    }

                    if (response.passwordExists) {
                        $('#spinner').hide();
                        $('#pass_used_er').html(
                            '* <i class="glyphicon glyphicon-exclamation-sign"></i> Password has already been used!'
                        ).show();
                        $('#must_strong').hide();
                        $('.pass_verif').css('border', '1px solid red');
                        return; // Exit process if the password has been used
                    } else {
                        $('.pass_verif').css('border', '1px solid #00b350');

                    } if (response.success) {
                        $('#spinner').hide();
                        $('#current_password').val("");
                        $('#new_password').val("");
                        $('#confirm_password').val("");
                        $('#btnSubmit').attr('disabled', true);
                        $('#tos-modal').modal('hide');

                        // Show SweetAlert after modal fade-out is complete
                        setTimeout(function() {
                            Swal.fire({
                                title: 'Thank You!',
                                html: '<h5 style="font-size: 120%;">Your password has been changed successfully. <br> Please hold on for a seconds.</h5>' +
                                    '<h1 id="timer-countdown" style="color:limegreen; font-size: 90px"><b><strong> 3 </strong></b></h1>',
                                icon: 'success',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,
                                timer: 3000,
                                timerProgressBar: true,
                                backdrop: `rgba(147, 250, 165, 0.7) center center`,
                                willOpen: () => {
                                    const timerElement = Swal.getHtmlContainer().querySelector('#timer-countdown');
                                    let timeLeft = 3;

                                    timerInterval = setInterval(() => {
                                        timeLeft--;
                                        timerElement.textContent = timeLeft; // Update the <h1> timer
                                    }, 1000); // every 1 second
                                },
                                willClose: () => {
                                    clearInterval(timerInterval);
                                }
                            }).then((result) => {
                                // If user confirms or if the timer ends, end session
                                if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                                    window.location.href = '{{ route('getLogout') }}';
                                }
                            });
                        }, 500); 

                    } else {
                        $('#spinner').hide();
                        Swal.fire('Error', 'An error occurred while updating the password.', 'error');
                    }
                },
                error: function(xhr) {
                    $('#spinner').hide();
                    Swal.fire('Error', 'An error occurred during the request.', 'error');
                }
            });
        });

        //waive change password
        $('#btnWaive').on('click', function(event) {
            const UserId = $('#user_id').val();
            const waive = parseInt($('#waive_count').val(), 10);

            if (waive >= 4) {
                Swal.fire({
                    title: 'Oops Sorry!',
                    html: '<h5 style="font-size: 120%;">You have already reached the waive limit, <br> Please update your password.</h5>',
                    icon: 'error',
                    confirmButtonText: ` <i class="fa fa-thumbs-up"></i> Okay Got it! `,
                });
                event.preventDefault();
                return;
            }


            $('#spinner').show();
            $.ajax({
                url: 'waive-change-password',
                type: 'POST',
                data: {
                    user_id: UserId,
                    waive: waive+1,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#tos-modal').modal('hide');
                        $('#spinner').hide();
                        $('#email').val("");
                        $('#password').val("");
                        const Toast = Swal.mixin({
                                    toast: true,
                                    position: "top-end",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                        });
                            Toast.fire({
                                icon: "success",
                                title: "Successfully waived change password."
                            });
                        setTimeout(function() {
                            window.location.href = "/admin";
                        }, 3000);
                    } else {
                        $('#spinner').hide();
                        Swal.fire('Error',
                            'An error occurred while waiving change password.',
                            'error');
                    }
                },
                error: function(xhr) {
                    $('#spinner').hide();
                    Swal.fire('Error', 'An error occurred during the request.',
                        'error');
                }
            });

        });

         // popover help information
         $(document).ready(function(){
            // Initialize the popover
            $('#popover-button').popover({
                title: '<i class="fa fa-info-circle text-primary"></i> Help Information',
                content: '<small>Need help in creating a new password? <i class="text-primary" id="generate-password"><u><b><br>Click here!</b></u></i> to generate a strong password.</small>',
                html: true 
            });

            $(document).on('click', '#generate-password', function() {
                showGeneratePass();
                $('#popover-button').popover('hide'); 
            });
            
            function showGeneratePass(){
                $('#generateChangePassTrigger').show();
            }
        });

        function generateStrongPassword(length = 15) {
            const lowercase = 'abcdefghijklmnopqrstuvwxyz';
            const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const numbers = '0123456789';
            const specialChars = '!@#$%^&*()_+-=[]{}|;:,.<>?';
            
            let password = '';
            password += lowercase[Math.floor(Math.random() * lowercase.length)];
            password += uppercase[Math.floor(Math.random() * uppercase.length)];
            password += numbers[Math.floor(Math.random() * numbers.length)];
            password += specialChars[Math.floor(Math.random() * specialChars.length)];

            const allChars = lowercase + uppercase + numbers + specialChars;

            // Generate remaining characters
            const array = new Uint32Array(length - 4); 
            window.crypto.getRandomValues(array);

            for (let i = 0; i < length - 4; i++) {
                password += allChars[array[i] % allChars.length];
            }

            password = password.split('').sort(() => Math.random() - 0.5).join('');

            $('#generated_password').val(password);
            $('#copied').hide();
            $('#copy').show();
            $('#generatedPassNote').hide();
        }

        function copyPass() {
            var passwordField = $('#generated_password'); 

            if($('#generated_password').val() == ""){
                $('#copy').show();
                $('#copied').hide();
                $('#generated_password').css('border', '1px solid red');
            } else {
                // Check for clipboard API support
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    // Modern approach
                    navigator.clipboard.writeText(passwordField.val()).then(function() {
                        $('#copied').show();
                        $('#copy').hide();
                        $('#generatedPassNote').show();
                    }).catch(function(err) {
                        alert('Error copying password: ', err);
                    });
                } else {
                    // Fallback for older browsers
                    passwordField.select();
                    document.execCommand('copy');
                    $('#copied').show();
                    $('#generatedPassNote').show();
                    $('#copy').hide();
                }
            }
        }

        //for normal change pass close modal
        function closeChangePass(){ 
            window.history.back();
        }

        //custom modal fade-in and fade-out animations
        $(document).ready(function() {
        var $customModal = $('#tos-modal');

        $customModal.on('show.bs.modal', function () {
            $(this).removeClass('out').addClass('in');
        });

        $customModal.on('hide.bs.modal', function (e) {
            e.preventDefault();

            var $this = $(this);
            $this.addClass('out');

            setTimeout(function () {
            $this.modal('hide').removeClass('in out');
            $('.modal-backdrop').remove();
            $('#tos-modal').modal('hide');
            }, 500); 
        });
        });
    </script>
@endpush