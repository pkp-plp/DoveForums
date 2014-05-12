<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Tables
$config['tables']['users']              = 'users';
$config['tables']['groups']             = 'groups';
$config['tables']['users_groups']       = 'users_groups';
$config['tables']['login_attempts']     = 'login_attempts';
$config['tables']['permissions']        = 'permissions';
$config['tables']['permissions_map']    = 'permissions_map';

// Join Columns
$config['join']['users']                = 'user_id';
$config['join']['groups']               = 'group_id';

// Hash Settings.
$config['hash_method']                  = 'bcrypt';
$config['default_rounds']               = 8;
$config['random_rounds']                = FALSE;
$config['min_rounds']                   = 5;
$config['max_rounds']                   = 9;

// Authentication Options.
$config['default_group']                = 'members';
$config['moderator_group']              = 'moderators';
$config['admin_group']                  = 'admin';
$config['identity']                     = 'username';
$config['track_login_attempts']         = TRUE;

// Message Delimiters
$config['message_start_delimiter']      = '<p>';
$config['message_end_delimiter']        = '</p>';
$config['error_start_delimiter']        = '<p>';
$config['error_end_delimiter']          = '</p>';

$config['user_expire']                  = 0;
$config['remember_users']               = TRUE;