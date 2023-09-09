<?php
/**
 * @package     local_registration
 * @author      Juan
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 namespace local_registration;

use dml_exception;
use lang_string;
use stdClass;

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->libdir . '/moodlelib.php');
 
 /**
  * manager
  */
 class manager {    
    /**
     * create_user
     * @param string $email
     * @param string $name
     * @param string $surname
     * @param string $country
     * @param string $mobile
     * @return void
     */
    public function create_user(
        string $email, 
        string $name, 
        string $surname, 
        string $country, 
        string $mobile): mixed 
    {
        global $DB;
        global $CFG;
        // Colecting all data        
        $user = new stdClass();
        $user->auth = 'manual';
        $user->confirmed = 1;
        $user->mnethostid = $CFG->mnet_localhost_id;
        $user->firstname = $name;
        $user->email = $email;
        $user->username = $email;
        $user->lastname = $surname;
        $user->country = $country;
        $user->phone1 = $mobile;
        $temporaryPassword = generate_password(10);
        $user->password = $temporaryPassword;
        file_put_contents($CFG->dirroot . '/errorlog/debug.log', 
            "User created Password: {$user->password}\n", FILE_APPEND);

        try {
            if ( is_numeric( $userId = user_create_user( $user, true, false ) ) ){
                $userObj = $DB->get_record('user', ['id'=> $userId], '*', MUST_EXIST);
                file_put_contents($CFG->dirroot . '/errorlog/debug.log', "User encrypted password: {$userObj->password}\n", FILE_APPEND);
                $this->sendPasswordRenewEmail( $userObj, $temporaryPassword );
                return $userObj;
            }
        } catch (dml_exception $e) {
            return false;
        }
    }
    
    /**
     * sendPasswordRenewEmail
     *
     * @param  stdClass $user
     * @param  string $temporaryPassword
     * @return bool
     */
    public function sendPasswordRenewEmail(stdClass $user, string $temporaryPassword) : bool {
        global $CFG;

        $fromUser = new stdClass;
        $fromUser->email = 'juan.etxenike.almeida@gmail.com';
        $toUser = $user;
        $toUser->link = $CFG->wwwroot. '/local/registration/new_password.php';
        $a = $toUser;
        $a->temporaryPassword = $temporaryPassword; 
        $a->sitename = 'MoodleTest';
        $subject = get_string('registerEmailSubject','local_registration');
        $body = get_string('newusernewpasswordtext', 'local_registration', $a);
        return email_to_user($toUser, $fromUser, $subject, $body, '');
    } 
    
    /**
     * update_user_password
     *
     * @param  stdClass $user
     * @param  string $newPassword
     * @return mixed
     */
    public function update_user_password(stdClass $user, string $newPassword):mixed {
        global $DB;
        global $CFG;

        // Make sure the user object is valid
        if (!$user || !isset($user->id)) {
            throw new \moodle_exception('invaliduser', 'local_registration');
        }

        // Get the appropriate authentication plugin
        $userauth = get_auth_plugin($user->auth);

        // Update the user's password
        if (!$userauth->user_update_password($user, $newPassword)) {
            throw new \moodle_exception('errorpasswordupdate', 'auth');
        }

        // Password updated successfully
        return true;
    }
}