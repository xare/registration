<?php
/**
 * @package     local_registration
 * @author      Juan
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 namespace local_registration;

use dml_exception;
use stdClass;

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->dirroot . '/user/lib.php');

 class manager {
    public function create_user($email, $name, $surname, $country, $mobile, $password): mixed 
    {
        global $DB;
        global $CFG;
        // Colecting all data
        $temporaryPassword = $this->_createTemporaryPassword();
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
        //$user->password = hash_internal_user_password($password);
        $user->password = hash_internal_user_password($temporaryPassword);
        try {
            if ( is_numeric( user_create_user( $user, true, false ) ) ){
                $this->sendPasswordRenewEmail( $email, $name . ' ' . $surname, $temporaryPassword);
                return $user;
            }
        } catch (dml_exception $e) {
            return false;
        }
        //return true if data stored
    }

    public function sendPasswordRenewEmail($userEmail, $userName, $temporaryPassword) {
        global $CFG;

        $fromUser = new stdClass;
        $fromUser->email = 'juan.etxenike.almeida@gmail.com';
        $toUser = new stdClass;
        $toUser->email = $userEmail;
        $link = $CFG->wwwroot. '/local/registration/new_password.php';
        $subject = get_string('registerEmailSubject','local_registration');
        $body = get_string('registerEmailBodyDear','local_registration').' '
                    .$userName.' '
                    .get_string('registerEmailBodyAccountCreated','local_registration'). ' '
                    .get_string('registerEmailBodyPassword', 'local_registration'). ' : '
                    //password comes here
                    .$temporaryPassword. ' '
                    .get_string('registerEmailBodyPasswordRenew', 'local_registration'). ' : '
                    .$link;
        return email_to_user($toUser, $fromUser, $subject, $body, '');
    }

    private function _createTemporaryPassword($length = 10) {
        // Define the character set
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-={}[]|\:;"<>,.?/~`';

        // Generate the password
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            // Get a random character from the character set
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $password;
     }
 }