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

class Discussions extends Front_Controller {

    private $validation_rules = array(
        'new_discussion' => array(
            //0
            array(
                'field' => 'discussion_name',
                'rules' => 'required',
                'label' => 'lang: rules_name',
            ),
            //1
            array(
                'field' => 'comment',
                'rules' => 'required',
                'label' => 'lang: rules_comment',
            ),
            //2
            array(
                'field' => 'category',
                'rules' => 'required',
                'label' => 'lang:rules_category',
            ),
        ),
    );

    private $form_fields = array(
        'new_discussion' => array(
            //0
            array(
                'name' => 'discussion_name',
                'id' => 'discussion_name',
                'placeholder' => 'Enter discussion name.',
                'class' => 'form-control',
                'type' => 'text'
            ),
            //1
            array(
                'name' => 'comment',
                'id' => 'comment',
                'placeholder' => 'Enter comment.',
                'class' => 'form-control',
                'type' => 'textarea',
            ),
            //2
            array(
                'id' => 'category',
                'class' => 'form-control',
            ),
            //3
            array(
                'name' => 'tags',
                'id' => 'tags',
                'class' => 'form-control',
                'type' => 'text',
                'data-role' => 'tagsinput',
                'placeholder' => 'Add Tag & Press Enter.',
            )
        ),
    );

    public function __construct()
    {
        parent::__construct();

        // Load in the slug library.
        $config = array(
            'field' => 'permalink',
            'title' => 'name',
            'table' => 'discussions',
            'id' => 'discussion_id',
        );

        $this->load->library('slug', $config);
    }

    public function index($category_permalink)
    {
        // Set up the pagination.
        $config['base_url'] = site_url('categories/'.$category_permalink.'');
        $config['total_rows'] = $this->discussions->count_category_discussions($category_permalink);
        $config['per_page'] = $this->config->item('discussions_per_page');
        $config['uri_segment'] = $this->uri->segment('3');

        $this->pagination->initialize($config);

        // Get category ID.
        $category_id = $this->categories->get_id_by_category_permalink($category_permalink);

        // Get the latest discussions.
        $discussions = $this->discussions->get_category_discussions($category_id, $config['per_page'], $config['uri_segment']);

        $has_discussions = ( is_array($discussions) ? TRUE : FALSE );

        // Initialize some variables.
        $data['unanswered'] = 0;
        $data['my_discussions'] = 0;

        if (($discussions))
        {
            foreach($discussions as $discussion)
            {
                $user = $this->dove_core->user($discussion['last_comment_by']);

                // See if the discussion is a sticky.
                $announcement = ( $discussion['announcement'] == 1 ? $this->lang->line('text_announcement') : NULL );

                // See if the discussion is closed.
                $closed = ( $discussion['closed'] == 1 ? $this->lang->line('text_closed') : NULL );

                if ( $discussion['answered'] == 0 )
                {
                    $data['unanswered']++;
                    $data['tag'] = '<span class="label label-info" title="'.$this->lang->line('title_unanswered').'">'.$this->lang->line('label_unanswered').'</span>';
                } else {
                    $data['tag'] = '<span class="label label-success" title="'.$this->lang->line('title_answered').'">'.$this->lang->line('label_answered').'</span>';
                }

                if ( $discussion['created_by'] == $this->session->userdata('user_id') )
                {
                    $data['my_discussions']++;
                    $owned = 1;
                }
                else
                {
                    $owned = 0;
                }

                // Build data array.
                $data['discussions'][] = array(
                    'discussion_info' => array(
                        'discussion_name' => anchor( site_url('discussion/'.$discussion['category_permalink'].'/'.$discussion['discussion_permalink'].''), $discussion['discussion_name']),
                        'comments' => $discussion['comments'],
                        'last_comment_by' => anchor( site_url('users/profile/'.$user->username.''), $user->username),
                        'last_comment_date' => timespan( $discussion['last_comment_date'], time() ),
                        'category' => anchor( site_url('categories/'.$discussion['category_permalink'].'/'), '<i class="fa fa-sitemap"></i> '.$discussion['category_name'].'', 'class="label label-default" title="'.sprintf($this->lang->line('label_category'), $discussion['category_name']).' - '.$discussion['category_description'].'"' ),
                        'tag' => $data['tag'],
                        'likes' => $discussion['likes'],
                        'closed' => $closed,
                        'announcement' => $announcement,
                        'owned' => $owned,
                    ),
                    'user_info' => array(
                        'gravatar' => img( array( 'src' => $this->gravatar->get_gravatar( $discussion['created_by_email'], $this->config->item('gravatar_rating'), '45', $this->config->item('default_image') ), 'class' => 'media-object img-thumbnail img-responsive') ),
                    ),
                    'buttons' => array(
                        'edit_button' => anchor( site_url( 'discussion/edit_discussion/'.$discussion['discussion_permalink'] ), '<i class="fa fa-pencil"></i>', 'class="btn btn-success btn-sm"' ),
                        'delete_button' => anchor( site_url( 'discussion/delete_discussion/'.$discussion['discussion_permalink'] ), '<i class="fa fa-trash-o"></i>', 'class="btn btn-success btn-sm"' ),
                    ),
                );
            }
        }
        else
        {
            $data['discussions'][] = array(
                'no_discussions' => 'Sorry there are no discussions to display.',
            );
        }

        $page_data = array(
            'discussions' => $data['discussions'],
            'pagination' => $this->pagination->create_links(),
            'has_discussions' => $has_discussions,
            'btn_unanswered_discussions' => anchor( site_url('discussions/unanswered_discussions'), sprintf( $this->lang->line('btn_unanswered_discussions'), $this->discussions->count_unanswered_discussions() ), 'class="btn btn-default btn-xs"'),
            'btn_all_discussions' => anchor( site_url(), sprintf( $this->lang->line('btn_all_discussions'), $this->discussions->count_all_discussions() ), 'class="btn btn-default btn-sx active"'),
            'btn_my_discussions' => anchor( site_url('discussions/my_discussions'), sprintf( $this->lang->line('btn_my_discussions'), $this->discussions->count_user_discussions($this->session->userdata('user_id')) ), 'class="btn btn-default btn-xs"'),
            'page_title' => 'All Discussions',
        );

        $this->construct_template($page_data, 'forums_template', $page_data['page_title']);

        $this->output->enable_profiler(false);
    }

    public function view($category_permalink=null, $discussion_permalink=null)
    {
        $discussion_id = $this->discussions->get_discussion_id_from_permalink($discussion_permalink);

        // Get all the comments for the discussion.
        $comments = $this->comments->get_comments($discussion_id);

        if($comments)
        {
            foreach($comments as $row)
            {
                // Get user rank.
                $user_rank = $this->dove_core->get_user_xp($row['user_id']);

                $data['comments'][] = array(
                    'comment_info' => array(
                        'comment' => nl2br($row['comment']),
                        'created_date' => timespan($row['created_date'], time()),
                    ),
                    'user_info' => array(
                        'group' => $row['group_name'],
                        'rank' => $user_rank['rank'],
                        'user_xp' => $user_rank['user_xp'],
                        'min_xp' => $user_rank['min_xp'],
                        'max_xp' => $user_rank['max_xp'],
                        'username' => $row['created_by'],
                        'signature' => $row['signature'],
                        'gravatar' => img(array('src' => $this->gravatar->get_gravatar($row['created_by_email'], $this->config->item('gravatar_rating')), 'class' => 'img-thumbnail img-rounded img-responsive')),
                    ),
                );
            }
        }

        $page_data = array(
            'discussion_name' => $this->discussions->get_discussion_name_from_permalink($discussion_permalink),
            'comments' => $data['comments'],
        );

        $this->construct_template($page_data, 'view_template', $this->lang->line('page_discussions') . ' - ' . $page_data['discussion_name']);
    }

    public function new_discussion()
    {
        // Login check.
        $this->login_check();

        // Check permissions.
        $this->permission_check('create_discussions');

        // Set the validation rules.
        $this->form_validation->set_rules($this->validation_rules['new_discussion']);

        // See if the form has been run.
        if($this->form_validation->run() === FALSE)
        {
            // Get categories from the database.
            $categories = $this->categories->get_categories();

            if($categories)
            {
                foreach($categories as $cat)
                {
                    $category_options[$cat['id']] = $cat['name'];
                }
            }

            $page_data = array(
                // Form Tags
                'form_open' => form_open(site_url('discussion/new_discussion'), array('id' => 'new_discussion')),
                'form_close' => form_close(),
                // Category Dropdown.
                'category_label' => form_label($this->lang->line('label_category'), $this->form_fields['new_discussion']['2']['id']),
                'category_field' => form_dropdown('category', $category_options, '0', 'class="selectpicker form-control show-tick show-menu-arrow" data-style="btn-default"'),
                // Discussion Name
                'discussion_name_label' => form_label($this->lang->line('label_discussion_name'), $this->form_fields['new_discussion']['0']['id']),
                'discussion_name_field' => form_input($this->form_fields['new_discussion']['0']),
                // Comment
                'comment_label' => form_label($this->lang->line('label_comment'), $this->form_fields['new_discussion']['1']['id']),
                'comment_field' => form_textarea($this->form_fields['new_discussion']['1']),
                // Buttons
                'clear_button' => form_reset('reset', 'Clear', 'class="btn btn-danger btn-sm"'),
                'submit_button' => form_submit('submit', 'Create Discussion', 'class="btn btn-success btn-sm"'),
            );

            $this->construct_template($page_data, 'new_discussion_template', $this->lang->line('page_new_discussion'));
        }
        else
        {
            $discussion_data = array(
                'category_id' => $this->input->post('category'),
                'name' => $this->input->post('discussion_name'),
                'created_by' => $this->session->userdata('user_id'),
                'created_date' => now(),
                'created_ip' => $this->input->ip_address(),
                'last_comment_by' => $this->session->userdata('user_id'),
                'last_comment_date' => now(),
                'last_comment_ip' => $this->input->ip_address(),
                'permalink' => $this->slug->create_uri(array('permalink' => $this->input->post('discussion_name'))),
                'likes' => '0',
                'announcement' => '0',
                'closed' => '0',
            );

            $comment_data = array(
                'comment' => $this->input->post('comment'),
                'created_by' => $this->session->userdata('user_id'),
                'created_date' => now(),
                'created_ip' => $this->input->ip_address(),
            );

            $insert_discussion = $this->discussions->add_discussion($discussion_data, $comment_data);

            if ($insert_discussion === TRUE)
            {
                // Award XP.
                $this->dove_core->add_xp('1', $this->session->userdata('user_id'));
                $this->create_message('success', $this->dove_core->messages());
                redirect ( site_url('discussion/'.$this->categories->get_category_permalink_by_id($discussion_data['category_id']).'/'.$discussion_data['permalink'].'') );
            }
            else
            {
                $this->create_message('error', $this->dove_core->errors());
                redirect ( site_url() );
            }
        }
    }

    public function edit_discussion($discussion_permalink)
    {
        // Login check.
        $this->login_check();

        // Permission check.
        $this->permission_check('edit_discussions');
    }

    public function delete_discussion($discussion_permalink)
    {
        // Login check.
        $this->login_check();

        // Permission check.
        $this->permission_check('delete_discussions');

        if ( !isset($discussion_permalink) )
        {
            $this->create_message('error', 'No permalink supplied.');
            redirect(site_url('forums'));
        }

        $discussion_id = $this->discussions->get_id_from_permalink($discussion_permalink);

        if ( isset($discussion_id) && isset($discussion_permalink) )
        {
            $delete = $this->discussions->delete($discussion_id);

            if ( $delete === TRUE )
            {
                $this->create_message('success', $this->dove_core->messages());
                redirect ( site_url() );
            }
            else
            {
                $this->create_message('error', $this->dove_core->errors());
                redirect ( site_url() );
            }
        }
        else
        {
            $this->dove_core->set_error('general_error');

            $this->create_message('error', $this->dove_core->errors());
            redirect ( site_url() );
        }
    }
}
