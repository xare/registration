<?php

/**
 * Strings for component 'local_registration', language 'en'
 *
 * @package   local_registration
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['emailLabel'] = 'Enter your email address';
$string['nameLabel'] = 'Enter your name';
$string['surnameLabel'] = 'Enter your surname';
$string['countryLabel'] = 'Select your country';
$string['mobileLabel'] = 'Enter your mobile number';

$string['emailRequired'] = 'Your email address is required';
$string['nameRequired'] = 'Your name is required';
$string['surnameRequired'] = 'Your surname is required';
$string['countryRequired'] = 'Your country is required';

$string['emailValidation'] = 'The submitted value is not an email';
$string['nameValidation'] = 'The submitted value should only contain alphabetic characters';
$string['surnameValidation'] = 'The submitted value should only contain alphabetic characters';
$string['mobileValidation'] = 'The mobile number should contain only numeric characters';

$string['oldpassword'] = 'Please, enter the password we have sent to you via email';
$string['newpassword'] = 'Please, enter your new password';
$string['required'] = 'This field is required';
$string['passwordsdiffer'] = 'The passwords are different';
$string['mustchangepassword'] = 'Please change your password';

$string['again'] = 'Again';

$string['registerEmailSubject'] = 'Your account has been created';
$string['registerEmailBodyDear'] = 'Dear';
$string['registerEmailBodyAccountCreated'] = 'Congratulations! Your moodle account has been recently created.';

$string['registerEmailBodyPassword'] = 'This password has been created for your first login:';
$string['registerEmailBodyPasswordRenew'] = 'Please follow this link where you will be asked to submit this password, and then you will submit your password: ';

$string['registerTitle'] = "REGISTER";
$string['registrationNotSuccessful'] = 'The registration has not been successful';
$string['registrationCancelled'] = 'The registration has been cancelled';

$string['registrationPasswordRenewed'] = 'Your password has been sucesfully renewed';

$string['newpasswordTitle'] = "NEW PASSWORD";

$string['usercreatedTitle'] = "User Created";
$string['usercreatedConfirmation'] = "Your user account has been created";

$string['passwordRenewedTitle'] = 'PASSWORD RENEWED';
$string['passwordRenewedConfirmation'] = "Your password has been renewed";
$string['passwordRenewalCancelled'] = 'The password renewal has been cancelled.';

$string['manageMessage'] = "You have cancelled your request. If you are on this page the reason is that you clicked cancelled on your request. If you want to start over, please click back and start over.";

$string['newusernewpasswordtext'] = 'Hi {$a->firstname},

A new account has been created for you at \'{$a->sitename}\'
and you have been issued with a new temporary password.

Your current login information is now:
   username: {$a->username}
   password: {$a->temporaryPassword}
             (you will have to change your password
              when you login for the first time)

To start using \'moodle site\', login at
   {$a->link}

In most mail programs, this should appear as a blue link
which you can just click on.  If that doesn\'t work,
then cut and paste the address into the address
line at the top of your web browser window.

Cheers from the \'{$a->sitename}\' administrator,
{$a->signoff}';

$string['emailCreated'] = "You have been sent an email message";

$string['cancelledMessage'] = "You have cancelled the submission of the form"; 