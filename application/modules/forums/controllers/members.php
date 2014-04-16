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

class Members extends Front_Controller {

    private $validation_rules = array(
        'sign_in' => array(
            //0
            array(
                'field' => 'username',
                'rules' => 'required',
                'label' => 'lang:rules_username',
            ),
            //1
            array(
                'field' => 'password',
                'rules' => 'required',
                'label' => 'lang:rules_password',
            ),
        ),
    );

    private $form_fields = array(
        'sign_in' => array(
            //0
            array(
                'name' => 'username',
                'id' => 'username',
                'placeholder' => 'Enter username.',
                'type' =>' text',
                'class' => 'form-control',
            ),
            //1
            array(
                'name' => 'password',
                'id' => 'password',
                'placeholder' => 'Enter password.',
                'type' => 'password',
                'class' => 'form-control',
            ),
        ),
    );

    public function sign_in()
    {
        // Set the validation rules.
        $this->form_validation->set_rules($this->validation_rules['sign_in']);

        // See if the form has run.
        if($this->form_validation->run() == FALSE)
        {
            $page_data = array(
                // Form tags.
                'form_open' => form_open(site_url('members/sign_in'), array('id' => 'sign_in')),
                'form_close' => form_close(),
                // Username
                'username_field' => form_input($this->form_fields['sign_in']['0']),
                // Password
                'password_field' => form_input($this->form_fields['sign_in']['1']),
                // Remember Me
                'remember_me' => form_checkbox('remember', true, true),
                // Buttons
                'submit_button' => form_submit('submit', 'Sign In', 'class="btn btn-primary btn-sm"'),
                // Links
                'forgot_password_link' => anchor('members/forgot_password', 'Forgot Password'),
            );

            $this->construct_template($page_data, 'sign_in_template', $this->lang->line('page_sign_in'));
        }
        else
        {
            // Perform login.
            $identity = $this->input->post('username');
            $password = $this->input->post('password');
            $remember = $this->input->post('remember');

            $login = $this->ion_auth->login($identity, $password, $remember);

            if($login == true)
            {
                // Login successful.
                redirect(site_url());
            }
            else
            {
                redirect(site_url());
            }
        }
    }

    public function sign_up()
    {

    }

    public function sign_out()
    {
        // Perform sign out.
        $this->ion_auth->logout();

        redirect(site_url());
    }
}