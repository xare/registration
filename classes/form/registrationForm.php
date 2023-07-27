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

 class registrationForm extends moodleform {

    public function definition() {
        global $CFG;

        $registrationForm = $this->_form;

        $registrationForm->addElement( 'text', 'email', get_string('emailLabel', 'local_registration')); //email
            $registrationForm->setType('email', PARAM_RAW );
            $registrationForm->addRule('email', get_string('emailRequired', 'local_registration'), 'required', null, 'server');
        $registrationForm->addElement( 'text', 'name', get_string('nameLabel', 'local_registration')); //Name
            $registrationForm->setType('name', PARAM_NOTAGS );
            $registrationForm->addRule('name', get_string('nameRequired', 'local_registration'), 'required', null, 'server');
        $registrationForm->addElement( 'text', 'surname', get_string('surnameLabel', 'local_registration')); //Surname
            $registrationForm->setType('surname', PARAM_NOTAGS );
            $registrationForm->addRule('surname', get_string('surnameRequired', 'local_registration'), 'required', null, 'server');
        
        $registrationForm->addElement( 'select', 'country', get_string('countryLabel', 'local_registration'), [
            'ES' => 'Spain', 
            'GR' => 'Greece', 
            'IT' => 'Italy', 
            'FR' => 'France', 
            'GB' => 'United Kingdom'
        ]); //Country
            $registrationForm->setDefault('country','greece');
            $registrationForm->addRule('country', get_string('countryRequired', 'local_registration'), 'required', null, 'server');

        $registrationForm->addElement( 'text', 'mobile', get_string('mobileLabel', 'local_registration')); //Mobile
            $registrationForm->setType( 'mobile', PARAM_NOTAGS );
            // we remove password since a temporary password will get sent via email to the user
        //$registrationForm->addElement( 'password', 'password', 'Enter your password');
            $this->add_action_buttons();
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $patterns = [ 
            'email' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'letters' => '/^[a-zA-Z]+$/',
            'numbers' => '/^[0-9]+$/',
        ];
        $fieldsValidations = [
            'email' => 'email',
            'name' => 'letters',
            'surname' => 'letters',
            'mobile' => 'numbers'
        ];
        
        foreach ( $fieldsValidations as $field => $pattern ) {
            if( 1 !== preg_match( $patterns[$pattern], $data[$field] )) {
                $errors[$field] = get_string($field.'Validation', 'local_registration');
            }
        }
        return $errors;
    }

 }

