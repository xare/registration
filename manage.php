<?php 
/**
 * @package     local_registration
 * @author      Juan
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin
 */

 require_once(__DIR__ . '/../../config.php');

 $context = \context_system::instance();
 $PAGE->set_url( new moodle_url('/local/registration/manage.php'));
 $PAGE->set_context( $context );
 $PAGE->set_title('MANAGE');

echo $OUTPUT->header();
echo "Manage";

$templatecontext = (object)[
    'Manage' => get_string( 'manageMessage', 'local_registration' ),
];
echo $OUTPUT->render_from_template('local_registration/manage', $templatecontext);
echo $OUTPUT->footer();
