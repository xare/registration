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
    redirect($CFG->wwwroot. '/local/registration/manage.php','The registration has been cancelled.');
} else if( $data = $newPasswordForm->get_data()) {
    $manager = new manager();
    if ( !$userauth->user_update_password($USER, $data->newpassword1) ) {
        throw new \moodle_exception( 'errorpasswordupdate', 'auth' );
    }
          
    redirect( $CFG->wwwroot. '/local/registration/passwordRenewed.php', $notice );
}

// PAGE RENDERING
echo $OUTPUT->header();
$newPasswordForm->display();
echo $OUTPUT->footer();