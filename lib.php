<?php 
/**
 * @package     local_registration
 * @author      Juan
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin
 */

 defined('MOODLE_INTERNAL') || die();

 function local_registration_user_created($user) {
    error_log('local_registration_user_created called', 3, __DIR__ . '/debug.log');
     global $DB;
     
     $body = 'Thank you for registering with us';
     // Original fields.
     /* foreach ($user as $field => $value) {
         $body .= $field . ' = ' . $value . "\n";
     } */
     
     // Custom fields.
    /*  $sql = "SELECT f.id, f.name, d.data
             FROM {user_info_field} f
             LEFT JOIN {user_info_data} d 
             ON d.fieldid = f.id 
             AND d.userid = :userid";
     $customfields = $DB->get_records_sql($sql, array('userid' => $user->id);
     foreach ($customfields as $customfield) {
         $body .= $customfield->name . ' = ' . $customfield->data . "\n";
     } */
     
     // Send the email to the admin user
    /*  $admin = get_admin();
     $subject = get_string('newuser');
     email_to_user($admin, $admin, $subject, $body);
 
     return true; */
     ob_start();
        var_dump($user);
        $dumpedContent = ob_get_clean();
        error_log($dumpedContent, 3, __DIR__ . '/user_dump.log');
     return $user;
 }