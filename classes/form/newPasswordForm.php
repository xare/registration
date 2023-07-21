<?php 
/**
 * @package     local_registration
 * @author      Juan
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin
 */

 namespace local_registration\form;

 use moodleform;


 require_once("$CFG->libdir/formslib.php");
 require_once($CFG->dirroot.'/user/lib.php');

 class newPasswordForm extends moodleform {
    public function definition() {
        global $USER, $CFG;

        $newPasswordForm = $this->_form;
        $purpose = user_edit_map_field_purpose($USER->id, 'password');
        $newPasswordForm->addElement('password', 'password', get_string('oldpassword','local_registration'), $purpose);
        $newPasswordForm->addRule('password', get_string('required','local_registration'), 'required', null, 'client');
        $newPasswordForm->setType('password', PARAM_RAW);

        $newPasswordForm->addElement('password', 'newpassword1', get_string('newpassword','local_registration'),
                            ['autocomplete' => 'new-password']);
        $newPasswordForm->addRule('newpassword1', get_string('required','local_registration'), 'required', null, 'client');
        $newPasswordForm->setType('newpassword1', PARAM_RAW);

        $newPasswordForm->addElement('password', 'newpassword2',
                            get_string('newpassword','local_registration').' ('.get_String('again','local_registration').')',
                            ['autocomplete' => 'new-password']);
        $newPasswordForm->addRule('newpassword2', get_string('required','local_registration'), 'required', null, 'client');
        $newPasswordForm->setType('newpassword2', PARAM_RAW);
        
        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        global $USER;
        $errors = parent::validation($data, $files);

        if ($data['newpassword1'] <> $data['newpassword2']) {
            $errors['newpassword1'] = get_string('passwordsdiffer','local_registration');
            $errors['newpassword2'] = get_string('passwordsdiffer','local_registration');
            return $errors;
        }

        if ($data['password'] == $data['newpassword1']){
            $errors['newpassword1'] = get_string('mustchangepassword','local_registration');
            $errors['newpassword2'] = get_string('mustchangepassword','local_registration');
            return $errors;
        }

        return $errors;
    }
 }