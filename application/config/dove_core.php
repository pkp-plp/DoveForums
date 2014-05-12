<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Open Software License version 3.0
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is
 * bundled with this package in the files license.txt / license.rst. It is
 * also available through the world wide web at this URL:
 * http://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package Dove Forums
 * @copyright Copyright (c) 2012 - Christopher Baines
 * @license http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link http://www.doveforums.com
 * @since Version 2.0.0
 * @author Christopher Baines
 *
 */

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