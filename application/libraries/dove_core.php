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

class Dove_core
{
    protected $permissions = array();

    public function __construct()
    {
        $this->load->config('dove_core', TRUE);
        $this->load->helper('cookie');
        $this->load->helper('language');
        $this->load->helper('url');
        $this->load->model('dove_core_m');

        // Get the permissions if user is logged in.
        if ( $this->logged_in())
        {
            $this->permissions = $this->dove_core_m->get_permissions($this->session->userdata('group_id'));
        }

    }

    public function __call($method, $arguments)
    {
        if (!method_exists( $this->dove_core_m, $method) )
        {
            throw new Exception('Undefined method dove_core::' . $method . '() called');
        }

        return call_user_func_array( array($this->dove_core_m, $method), $arguments);
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function login($identity, $password, $remember = FALSE)
    {
        // Set hook.
        $this->trigger_events('pre_login');

        // Check supplied details.
        if (empty($identity) || empty($password))
        {
            $this->set_error('login_unsuccessful');
            return FALSE;
        }

        // Perform login.
        $login = $this->dove_core_m->login($identity, $password, $remember);

        return $login;
    }

    public function register($username, $password, $email)
    {
        // Set hook.
        $this->trigger_events('pre_register');

        if ( $this->dove_core_m->register($username, $password, $email) == TRUE )
        {
            echo 'account created';
        } else {
            echo 'creation failed.';
        }
    }

    public function activate()
    {

    }

    public function logged_in()
    {
        // Set hook.
        $this->trigger_events('logged_in');

        return (bool) $this->session->userdata('identity');
    }

    public function is_admin($id = NULL)
    {
        // Set hook.
        $this->trigger_events('is_admin');

        $admin_group = $this->config->item('admin_group', 'dove_core');

        return $this->in_group($admin_group, $id);
    }

    public function in_group($check_group, $id = NULL)
    {
        // Set hook.
        $this->trigger_events('in_group');

        $id = ( !$id ? $this->session->userdata('group_id') : $id );

        $db_group = $this->dove_core_m->in_group($id);

        if ($db_group == $check_group)
        {
            return TRUE;
        }

        return FALSE;
    }

    public function has_permission($key)
    {
        if ( array_key_exists($key, $this->permissions) )
        {
            if($this->permissions[$key]['value'] === 1 || $this->permissions[$key]['value'] === true)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

}