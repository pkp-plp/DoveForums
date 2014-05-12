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

class MY_Controller extends CI_Controller{

    public $tables = array();

    public function __construct()
    {
        parent::__construct();

        // Load in required models.
        $this->load->model('forums/categories_m', 'categories');
        $this->load->model('forums/discussions_m', 'discussions');
        $this->load->model('forums/users_m', 'users');
        $this->load->model('forums/comments_m', 'comments');
        $this->load->config('forums', TRUE);

        // Load in required language files.
        $this->load->language('forums/frontend', $this->config->item('site_language'));

        // Tables
        $this->tables = $this->config->item('tables', 'forums');
    }
}

class Front_Controller extends MY_Controller{

    public $frontend_theme;
    public $admin_theme;
    public $sidebar_display;
    public $messages;
    public $permissions;

    public function __construct()
    {
        parent::__construct();

        // Set the frontend theme.
        $this->parser->theme($this->config->item('frontend_theme'));

        $this->permissions = array();
    }

    public function construct_template($page_data, $page, $page_title)
    {
        // Add languages to the parser.
        $languages = array(
            'frontend' => $this->load->language('forums/frontend', $this->config->item('site_language')),
        );

        // Meta Data.
        $meta = array(
            'keywords' => $this->config->item('site_keywords'),
            'description' => $this->config->item('site_description'),
            'author' => $this->config->item('site_author'),
            'site_title' => ''.$this->config->item('site_name').' - '.$page_title.'',
        );

        // Auth Data.
        $auth = array(
            'logged_in' => $this->dove_core->logged_in(),
            'is_admin' => $this->dove_core->is_admin(),
        );

        $this->permissions = $this->dove_core->get_permissions($this->session->userdata('group_id'));

        // Append data to the parser.
        $this->parser->append('meta', $meta);
        $this->parser->append('lang', $languages);
        $this->parser->append('auth', $auth);
        $this->parser->append('permissions', $this->permissions);

        // Config for parser.
        $config['show'] = false;

        // Build Message Data.
        /* TODO - Find a better way of handeling messages */
        if($this->session->flashdata('error'))
        {
            $message_body = $this->session->flashdata('error');
            $message_class = 'alert alert-danger';
            $message_title = 'Error';
            $has_message = true;
        }
        elseif($this->session->flashdata('success'))
        {
            $message_body = $this->session->flashdata('success');
            $message_class = 'alert alert-success';
            $message_title = 'Success';
            $has_message = true;
        }
        else
        {
            $message_body = false;
            $message_class = false;
            $message_title = false;
            $has_message = false;
        }

        $message_data = array(
            'message_body' => $message_body,
            'message_class' => $message_class,
            'message_title' => $message_title,
            'has_message' => $has_message,
        );

        $data['messages'] = $this->parser->parse('sections/messages_template', $message_data, $config);

        // Construct the navigation.
        $navigation_data = array(
            'logo' => anchor(site_url(), $this->config->item('site_name'), 'class="navbar-brand"'),
            'sign_in_link' => anchor(site_url('members/sign_in'), '<i class="fa fa-sign-in"></i> Sign In'),
            'sign_out_link' => anchor(site_url('members/sign_out'), '<i class="fa fa-sign-out"></i> Sign Out'),
        );

        $data['navigation'] = $this->parser->parse('sections/navigation_template', $navigation_data, $config);

        // Construct the content.
        $data['content'] = $this->parser->parse('pages/'.$page.'', $page_data, $config);

        // Construct the sidebar.
        $categories = $this->categories->get_categories();

        if($categories)
        {
            foreach($categories as $cat)
            {
                $discussions = $this->categories->count_discussions($cat['id']);

                $data['categories'][] = array(
                    'category_name' => anchor(site_url('categories/'.$cat['permalink'].''), ''.$cat['name'].'<span class="label label-default pull-right"> '.$discussions.' </span>'),
                );
            }
        }

        // Get sidebar members.
        $members = $this->users->get_sidebar_members();

        foreach ( $members as $row )
        {
            $data['members'][] = array(
                'member' => img( array( 'src' => $this->gravatar->get_gravatar( $row['email'], $this->config->item('gravatar_rating'), '45', $this->config->item('default_image') ), 'class' => 'img-rounded img-responsive', 'title' => $row['username']) ),
            );
        }

        // Create the data for the right sidebar.
        $right_sidebar_data = array(
            'categories' => $data['categories'],
            'member_count' => $this->users->count_members(),
            'members' => $data['members'],
            'new_discussion_button' => anchor( site_url('discussion/new_discussion'), $this->lang->line('btn_new_discussion'), 'class="btn btn-success btn-icon col-md-12"' ),
        );

        // Parse the template & data.
        $data['right_sidebar'] = $this->parser->parse('sections/right_sidebar_template', $right_sidebar_data, $config);

        // Construct the footer.
        $footer_data = array(
            'text' => 'This is the footer',
        );

        $data['footer'] = $this->parser->parse('sections/footer_template', $footer_data, $config);

        // Additional
        $data['breadcrumb'] = set_breadcrumb();

        // Build the final template.
        $this->parser->parse('default', $data);
    }

    public function process_tags($tags)
    {
        $tags = explode(",", $tags);

        foreach($tags as $tag)
        {
            $data['tags'][] = array(
                'tag' => anchor(site_url('search/'.$tag.''), $tag, 'class="label label-info"'),
            );
        }

        return $data['tags'];
    }

    public function create_message($type, $message)
    {
        return $this->session->set_flashdata($type, $message);
    }

    public function login_check()
    {
        if ( $this->dove_core->logged_in() === 0 )
        {
            $this->create_message('error', 'You need to be logged in to perform this action.');
            redirect ( site_url() );
        }
        else
        {
            return $this->dove_core->logged_in();
        }
    }

    public function permission_check($key)
    {
        if ( $this->dove_core->has_permission($key) === FALSE || $this->dove_core->has_permission($key) === 0 )
        {
            $this->create_message('error', 'You do not have permission!.');
            redirect ( site_url() );
        }
        else
        {
            return TRUE;
        }
    }
}

class Admin_Controller extends MY_Controller{

    public function __construct()
    {
        parent::__construct();

        // Set the admin theme.
        $this->admin_theme = $this->config->item('admin_theme');
    }
}

