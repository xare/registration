# Registration plugin for moodle

This plugin allows us to register users into moodle. It is a test case so it should not be used on production level until properly tested.

This module creates a registration form, the user submits the form and he receives an email with a temporary password and a link which will allow him to change that password. 

Once the password has been changed he will be allowed to use the moodle platform.

The content of this repository should be installed inside the the /local/registration folder of a moodle site.


## NOTES

This plugin consists on:
- **version.php**: plugin definition file
- **register.php**: registerForm rendering controller file
- **user_created.php**: controller file redirected once user has been registered.
- **new_password.php**: controller file rendering the new password form. This file is pointed at in the email that gets sent to the user.
- **password_renewed.php**: controller file redirected once user has renewed the password.
- **cancelled.php**: controller we get redirected to on cancellation of the form submission and/or failure to create a user.
- **classes/form/registrationForm.php** : Form and validation code for registration form
- **classes/form/newPasswordForm.php**: Form and validation code for new Password form.
- **classes/manager.php**: "services" or "helper" class to process the submitted data (create user, send email ...)
- **lang/en/local_registration.php**: localisation file for english language strings.
- **templates/cancelled.mustache**: template for cancelled.php
- **templates/password_renewed.mustache**: Template for password_renewed.php file
- **templates/user_create.mustache**: Template for user_created.php file.

This plugin has been implemented for Moodle Version 4.2.1+ (Build: 20230721)
A working site where it has been installed can be found at https://moodle.lehioa.katakrak.net

The application files can be accessed at
https://moodle.lehioa.katakrak.net/local/registration/register.php
https://moodle.lehioa.katakrak.net/local/registration/new_password.php (login protected)

## ABOUT THE PROCESS

At time of the publication of this note some of the expected tasks have been accomplished but also some problems have been met.
- So far the plugin has worked for these tasks
    - Creation of a new user account with temporary password creation
    - Validation of fields
    - Rendering of the forms and redirections
    - Access to form to renew the password
    - The password gets renewed
    - Success while login to the site with the renewed password