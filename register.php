<?php
/**
 * @package     local_registration
 * @author      Juan
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin
 */

///////////////////////////////////////////////////////////
// http(s)://domain.tld/local/registration/register.php  //
// This is a controller file that will load the register //
// form located in:                                      //
// /local/registration/classes/form/registrationForm.php //
// The file will redirect to a default manage.php if     //
// cancelled and to new_password.php page if success     //
///////////////////////////////////////////////////////////
use local_registration\form\registrationForm;
use local_registration\manager;
use dml_exception;

require_once(__DIR__ . '/../../config.php');

$context = \context_system::instance();

// PAGE SETUP
$PAGE->set_url( new moodle_url('/local/registration/register.php'));
$PAGE->set_context( $context );
$PAGE->set_title(get_string('registerTitle', 'local_registration'));

// FORM MANAGEMENT
$registrationForm = new registrationForm();

if ( $registrationForm->is_cancelled() ) {
    redirect($CFG->wwwroot. '/local/registration/cancelled.php',get_string('registrationCancelled','local_registration'));
} else if( $rForm = $registrationForm->get_data()) {
    //instantiate the /local/registration/classes/manager.php class
    $manager = new manager();
    $user = $manager->create_user(
        $rForm->email,
        $rForm->name,
        $rForm->surname,
        $rForm->country,
        $rForm->mobile);

    if(false !== $user) {
        $notice =  get_string('emailCreated', 'local_registration') .$user->email;
        redirect($CFG->wwwroot. '/local/registration/user_created.php', $notice);
    } else {
        $notice = get_string('registrationNotSuccessful', 'local_registration');
        redirect($CFG->wwwroot. '/local/registration/cancelled.php', $notice);
    }

}

// PAGE RENDERING
echo $OUTPUT->header();
$registrationForm->display();
echo $OUTPUT->footer();
