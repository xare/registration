<?php

/**
 * @package     local_registration
 * @author      Kristian
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

$context = context_system::instance();

$PAGE->set_url(new moodle_url('/local/message/password_renewed.php'));
$PAGE->set_context($context);
$PAGE->set_title(get_string('passwordRenewedTitle', 'local_registration'));

echo $OUTPUT->header();

$templatecontext = (object)[
    'passwordRenewedConfirmation' => get_string( 'passwordRenewedConfirmation', 'local_registration' ),
];
echo $OUTPUT->render_from_template('local_registration/password_renewed', $templatecontext);

echo $OUTPUT->footer();