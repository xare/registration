<?php

/**
 * @package     local_registration
 * @author      Juan
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin
 */

///////////////////////////////////////////////////////////////
// http(s)://domain.tld/local/registration/new_password.php  //
// This is a controller file that will load the newPassword //
// form located in:                                          //
// /local/registration/classes/form/newPasswordForm.php      //
// The file will redirect to a default manage.php if         //
// cancelled and to passwordRenewed.phppage if success       //
///////////////////////////////////////////////////////////////
use local_registration\form\newPasswordForm;
use local_registration\manager;
use dml_exception;
 
require_once(__DIR__ . '/../../config.php');

// Restrict access to logged-in users
require_login();

$context = \context_system::instance();

// PAGE SETUP
$PAGE->set_url( new moodle_url( '/local/registration/new_password.php' ) );
$PAGE->set_context( $context );
$PAGE->set_title( get_string( 'newpasswordTitle', 'local_registration' ) );
 
// FORM MANAGEMENT
$newPasswordForm = new newPasswordForm();
// load the appropriate auth plugin
$userauth = get_auth_plugin( $USER->auth );

if ( $newPasswordForm->is_cancelled() ) {
    redirect( $CFG->wwwroot. '/local/registration/cancelled.php', 
                get_string( 'passwordRenewalCancelled', 'local_registration' ) );
} else if( $data = $newPasswordForm->get_data()) {
    try {
        $manager = new manager();
        // Update the user's password
        $manager->update_user_password($USER, $data->newpassword1);
        $notice = get_string('registrationPasswordRenewed', 'local_registration');
    } catch (\moodle_exception $e) {
        $notice = "Failure to update User password: ". $e->getMessage();
    }
    redirect( $CFG->wwwroot. '/local/registration/password_renewed.php', $notice );
}

// PAGE RENDERING
echo $OUTPUT->header();
$newPasswordForm->display();
echo $OUTPUT->footer();