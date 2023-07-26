# Registration plugin for moodle

This plugin allows us to register users into moodle. It is a test case so it should not be used on production level until properly tested.

This module creates a registration form, the user submits the form and he receives an email with a temporary password and a link which will allow him to change that password. 

Once the password has been changed he will be allowed to use the moodle platform.

## NOTES

This plugin consists on:
- version.php: plugin definition file
- register.php: registerForm rendering controller file
- user_created.php: controller file redirected once user has been registered.
- new_password.php: controller file rendering the new password form. This file is pointed at in the email that gets sent to the user.
- password_renewed.php: controller file redirected once user has renewed the password.
- cancelled.php: controller we get redirected to on cancellation of the form submission and/or failure to create a user.
- classes/form/registrationForm.php : Form and validation code for registration form
- classes/form/newPasswordForm.php: Form and validation code for new Password form.
- classes/manager.php: "services" or "helper" class to process the submitted data (create user, send email ...)
-  lang/en/local_registration.php: localisation file for english language strings.
- templates/cancelled.mustache: template for cancelled.php
- templates/password_renewed.mustache: Template for password_renewed.php file
- templates/user_create.mustache: Template for user_created.php file.

This plugin has been implemented for Moodle Version 4.2.1+ (Build: 20230721)
A working site where it has been installed can be found at https://moodle.lehioa.katakrak.net

## ABOUT THE PROCESS

At time of the publication of this note some of the expected tasks have been accomplished but also some problems have been met.
- So far the plugin has worked for these tasks
    - Creation of a new user account with temporary password creation
    - Validation of fields
    - Rendering of the forms and redirections
- The problem arises with the login procedure. When submitting the username with email and the password the submitted fails to verify against the one stored in the database. In order to tackle this issue I have "hacked" lib/moodlelib.php and added some debugging tags that would provide data to a file called /errorlog/debug.log where I could check the results. I have been actively checking these results with AI tool (chatGPT) but reached unconclusive results. I do realise that the task may have an "easy solution" but yet tried to go to the bottom of the issue in order to understand what is happening. 

I have "hacked" the "validate_internal_user_password" function of lib/moodlelib.php in order to bring light to this issue:

```
function validate_internal_user_password($user, $password) {
    global $CFG;
    file_put_contents($CFG->dirroot . '/errorlog/debug.log', "Validating password for: {$user->username}, password: {$password}\n", FILE_APPEND);
    if ($user->password === AUTH_PASSWORD_NOT_CACHED) {
        // Internal password is not used at all, it can not validate.
        return false;
    }

    // If hash isn't a legacy (md5) hash, validate using the library function.
    if (!password_is_legacy_hash($user->password)) {
        file_put_contents($CFG->dirroot . '/errorlog/debug.log', "Password is not legacy hash: {$user->password}\n", FILE_APPEND);
        $result = password_verify($password, $user->password);
        file_put_contents($CFG->dirroot . '/errorlog/debug.log', "Result of function password verify: {$result}\n", FILE_APPEND);
        if (!$result) {
            file_put_contents($CFG->dirroot . '/errorlog/debug.log', "Password did not match the hash for: {$user->username}\n", FILE_APPEND);
        }

        return $result;
        
    }

    // Otherwise we need to check for a legacy (md5) hash instead. If the hash
    // is valid we can then update it to the new algorithm.

    $sitesalt = isset($CFG->passwordsaltmain) ? $CFG->passwordsaltmain : '';
    $validated = false;
    file_put_contents($CFG->dirroot . '/errorlog/debug.log', "Checking for legacy Hash\n", FILE_APPEND);
    
    file_put_contents($CFG->dirroot . '/errorlog/debug.log', "password:" . $password."\n", FILE_APPEND);
    file_put_contents($CFG->dirroot . '/errorlog/debug.log', "User->password:". $user->password . "\n", FILE_APPEND);
    file_put_contents($CFG->dirroot . '/errorlog/debug.log', "md5(password.sitesalt):" . md5($password.$sitesalt)."\n", FILE_APPEND);
    file_put_contents($CFG->dirroot . '/errorlog/debug.log', "md5(password):" . md5($password)."\n", FILE_APPEND);
    file_put_contents($CFG->dirroot . '/errorlog/debug.log', "md5(addslashes(password.sitesalt)):" . md5(addslashes($password).$sitesalt)."\n", FILE_APPEND);
    file_put_contents($CFG->dirroot . '/errorlog/debug.log', "md5(addslashes(password):" . md5(addslashes($password))."\n", FILE_APPEND);
    if ($user->password === md5($password.$sitesalt)
            or $user->password === md5($password)
            or $user->password === md5(addslashes($password).$sitesalt)
            or $user->password === md5(addslashes($password))) {
        // Note: we are intentionally using the addslashes() here because we
        //       need to accept old password hashes of passwords with magic quotes.
        file_put_contents($CFG->dirroot . '/errorlog/debug.log', 'Inside multiple if statement', FILE_APPEND);
        $validated = true;

    } else {
        for ($i=1; $i<=20; $i++) { // 20 alternative salts should be enough, right?
            $alt = 'passwordsaltalt'.$i;
            if (!empty($CFG->$alt)) {
                if ($user->password === md5($password.$CFG->$alt) or $user->password === md5(addslashes($password).$CFG->$alt)) {
                    $validated = true;
                    break;
                }
            }
        }
    }

    if ($validated) {
        // If the password matches the existing md5 hash, update to the
        // current hash algorithm while we have access to the user's password.
        update_internal_user_password($user, $password);
    }

    return $validated;
}
```

It's on this line "if (!password_is_legacy_hash($user->password)) " that the system would return false thus preventing the login. Nonetheless the password creating functions that are used are internal functions of moodle as can be seen in local/registration/manage.php 

```
        $user->temporaryPassword = generate_password(10);
        $user->password = hash_internal_user_password($user->temporaryPassword);
```

At the time of submitting this code for analysis this issue remains unsolved expecting to be solve in the forthcoming time.

