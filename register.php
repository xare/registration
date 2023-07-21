<?php 
/**
 * @package     local_registration
 * @author      Juan
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin
 */

use local_registration\form\registrationForm;
use local_registration\manager;
use dml_exception;

require_once(__DIR__ . '/../../config.php');

$context = \context_system::instance();
$PAGE->set_url( new moodle_url('/local/registration/register.php'));
$PAGE->set_context( $context );
$PAGE->set_title(get_string('registerTitle', 'local_registration'));

$registrationForm = new registrationForm();

if ( $registrationForm->is_cancelled() ) {
    redirect($CFG->wwwroot. '/local/registration/manage.php',get_string('registrationCancelled','local_registration'));
} else if( $rForm = $registrationForm->get_data()) {
    $manager = new manager();
    $user = $manager->create_user($rForm->email, $rForm->name, $rForm->surname, $rForm->country, $rForm->mobile, $rForm->password);

    if(false !== $user) {
        $notice =  $sendMail . ' '.$user->email;
        redirect($CFG->wwwroot. '/local/registration/user_created.php', $notice);
    } else { 
        $notice = get_string('registrationNotSuccessful', 'local_registration');
        redirect($CFG->wwwroot. '/local/registration/manage.php', $notice);
    }    
    
}

echo $OUTPUT->header();
$registrationForm->display();
echo $OUTPUT->footer();
