<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['tables']['users']           = 'users';
$config['tables']['groups']          = 'groups';
$config['tables']['users_groups']    = 'users_groups';
$config['tables']['login_attempts']  = 'login_attempts';

$config['join']['users']  = 'id_user';
$config['join']['groups'] = 'id_group';

$config['hash_method']    = 'bcrypt';
$config['default_rounds'] = 8;
$config['random_rounds']  = FALSE;
$config['min_rounds']     = 5;
$config['max_rounds']     = 9;
$config['salt_prefix']    = version_compare(PHP_VERSION, '5.3.7', '<') ? '$2a$' : '$2y$';


$config['site_title']                 = "Universitas Bumigora";       // Site Title, example.com
$config['admin_email']                = "pustik@universitasbumigora.ac.id"; // Admin Email, admin@example.com
$config['default_group']              = 'mahasiswa';           // Default group, use name
$config['admin_group']                = 'admin';             // Default administrators group, use name
$config['identity']                   = 'email';             // You can use any unique column in your table as identity column. The values in this column, alongside password, will be used for login purposes
$config['min_password_length']        = 8;                   // Minimum Required Length of Password
$config['max_password_length']        = 20;                  // Maximum Allowed Length of Password
$config['email_activation']           = FALSE;               // Email Activation for registration
$config['manual_activation']          = FALSE;               // Manual Activation for registration
$config['remember_users']             = TRUE;                // Allow users to be remembered and enable auto-login
$config['user_expire']                = 86500;               // How long to remember the user (seconds). Set to zero for no expiration
$config['user_extend_on_login']       = FALSE;               // Extend the users cookies every time they auto-login
$config['track_login_attempts']       = TRUE;                // Track the number of failed login attempts for each user or ip.
$config['track_login_ip_address']     = TRUE;                // Track login attempts by IP Address, if FALSE will track based on identity. (Default: TRUE)
$config['maximum_login_attempts']     = 3;                   // The maximum number of failed login attempts.
$config['lockout_time']               = 600;                 // The number of seconds to lockout an account due to exceeded attempts
$config['forgot_password_expiration'] = 3600;                // 1 hour expiry for password reset tokens
$config['recheck_timer']              = 0;                   /* The number of seconds after which the session is checked again against database to see if the user still exists and is active.
							           Leave 0 if you don't want session recheck. if you really think you need to recheck the session against database, we would
								   recommend a higher value, as this would affect performance */



$config['remember_cookie_name'] = 'remember_code';
$config['identity_cookie_name'] = 'identity';


$config['use_ci_email'] = FALSE; // Send Email using the builtin CI email class, if false it will return the code and the identity
$config['email_config'] = array(
	'mailtype' => 'html',
);

$config['email_templates'] = 'auth/email/';

$config['email_activate'] = 'activate.tpl.php';


$config['email_forgot_password'] = 'forgot_password.tpl.php';

$config['email_forgot_password_complete'] = 'new_password.tpl.php';


$config['salt_length'] = 22;
$config['store_salt']  = FALSE;
$config['delimiters_source']       = 'config'; 	// "config" = use the settings defined here, "form_validation" = use the settings defined in CI's form validation library
$config['message_start_delimiter'] = '<p>'; 	// Message start delimiter
$config['message_end_delimiter']   = '</p>'; 	// Message end delimiter
$config['error_start_delimiter']   = '<p>';		// Error message start delimiter
$config['error_end_delimiter']     = '</p>';	// Error message end delimiter
