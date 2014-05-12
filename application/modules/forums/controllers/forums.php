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

class Forums extends Front_Controller {

    public function index()
    {
        // Set up the pagination.
        $config['base_url'] = site_url('forums/index');
        $config['total_rows'] = $this->discussions->count_all_discussions();
        $config['per_page'] = $this->config->item('discussions_per_page');
        $config['uri_segment'] = '3';
        $offset = $this->uri->segment('3');

        $this->pagination->initialize($config);

        // Get the latest discussions.
        $discussions = $this->discussions->get_discussions($config['per_page'], $offset);

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
                $announcement = ( $discussion['announcement'] == 1
                    ? $this->lang->line('text_announcement')
                    : '' );

                // See if the discussion is closed.
                $closed = ( $discussion['closed'] == 1 ? $this->lang->line('text_closed') : '' );

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

                $data['discussions'][] = array(
                    'gravatar' => img(array('src' => $this->gravatar->get_gravatar($discussion['created_by_email'], $this->config->item('gravatar_rating'), '45', $this->config->item('default_image')), 'class' => 'media-object img-thumbnail img-responsive')),
                    'discussion_name' => anchor(site_url('discussion/'.$discussion['category_permalink'].'/'.$discussion['discussion_permalink'].''), $discussion['discussion_name']),
                    'comments' => $discussion['comments'],
                    'last_comment_by' => anchor(site_url('users/profile/'.$user->username.''), $user->username),
                    'last_comment_date' => timespan($discussion['last_comment_date'], time()),
                    'category' => anchor( site_url('categories/'.$discussion['category_permalink'].'/'), '<i class="fa fa-sitemap"></i> '.$discussion['category_name'].'', 'class="label label-default" title="'.sprintf($this->lang->line('label_category'), $discussion['category_name']).' - '.$discussion['category_description'].'"' ),
                    'tag' => $data['tag'],
                    'likes' => $discussion['likes'],
                    'closed' => $closed,
                    'announcement' => $announcement,
                    'edit_button' => anchor(site_url('discussion/edit_discussion/'.$discussion['discussion_permalink']), '<i class="fa fa-pencil"></i>', 'class="btn btn-success btn-sm"'),
                    'delete_button' => anchor(site_url('discussion/delete_discussion/'.$discussion['discussion_permalink']), '<i class="fa fa-trash-o"></i>', 'class="btn btn-success btn-sm"'),
                    'owned' => $owned,
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
            'btn_unanswered_discussions' => anchor( site_url('discussions/unanswered_discussions'), sprintf($this->lang->line('btn_unanswered_discussions'), $this->discussions->count_unanswered_discussions()), 'class="btn btn-default btn-xs"'),
            'btn_all_discussions' => anchor( site_url(), sprintf($this->lang->line('btn_all_discussions'), $this->discussions->count_all_discussions()), 'class="btn btn-default btn-sx active"'),
            'btn_my_discussions' => anchor( site_url('discussions/my_discussions'), sprintf($this->lang->line('btn_my_discussions'), $this->discussions->count_user_discussions($this->session->userdata('user_id'))), 'class="btn btn-default btn-xs"'),
            'page_title' => 'All Discussions',
        );

        $this->construct_template($page_data, 'forums_template', $page_data['page_title']);

        $this->output->enable_profiler(false);
    }

    public function filtered($filter)
    {
        // Set up the pagination.
        $config['base_url'] = site_url('discussions/'.$filter.'');
        $config['per_page'] = $this->config->item('discussions_per_page');
        $config['uri_segment'] = '3';
        $offset = $this->uri->segment('3');

        if( isset($filter) )
        {
            if ( strtolower($filter) == 'unanswered_discussions')
            {
                $config['total_rows'] = $this->discussions->count_unanswered_discussions();
                $data['page_title'] = $this->lang->line('page_unanswered');
            }
            elseif ( strtolower($filter) == 'my_discussions')
            {
                $config['total_rows'] = $this->discussions->count_user_discussions($this->session->userdata('user_id'));
                $data['page_title'] = $this->lang->line('page_my_discussions');
            }
        }

        $this->pagination->initialize($config);

        // Get the latest discussions.
        $discussions = $this->discussions->get_discussions($config['per_page'], $offset, $filter);

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
                $announcement = ( $discussion['announcement'] == 1 ? $this->lang->line('text_announcement') : '' );

                // See if the discussion is closed.
                $closed = ( $discussion['closed'] == 1 ? $this->lang->line('text_closed') : '' );

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

                $data['discussions'][] = array(
                    'gravatar' => img(array('src' => $this->gravatar->get_gravatar($discussion['created_by_email'], $this->config->item('gravatar_rating'), '45', $this->config->item('default_image')), 'class' => 'media-object img-thumbnail img-responsive')),
                    'discussion_name' => anchor(site_url('discussion/'.$discussion['category_permalink'].'/'.$discussion['discussion_permalink'].''), $discussion['discussion_name']),
                    'comments' => $discussion['comments'],
                    'last_comment_by' => anchor(site_url('users/profile/'.$user->username.''), $user->username),
                    'last_comment_date' => timespan($discussion['last_comment_date'], time()),
                    'category' => anchor( site_url('categories/'.$discussion['category_permalink'].'/'), '<i class="fa fa-sitemap"></i> '.$discussion['category_name'].'', 'class="label label-default" title="'.sprintf($this->lang->line('label_category'), $discussion['category_name']).'"' ),
                    'tag' => $data['tag'],
                    'likes' => $discussion['likes'],
                    'closed' => $closed,
                    'announcement' => $announcement,
                    'edit_button' => anchor(site_url('discussion/edit_discussion/'.$discussion['discussion_permalink']), '<i class="fa fa-pencil"></i>', 'class="btn btn-success btn-sm"'),
                    'delete_button' => anchor(site_url('discussion/delete_discussion/'.$discussion['discussion_permalink']), '<i class="fa fa-trash-o"></i>', 'class="btn btn-success btn-sm"'),
                    'owned' => $owned,
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
            'btn_unanswered_discussions' => anchor( site_url('discussions/unanswered_discussions'), sprintf($this->lang->line('btn_unanswered_discussions'), $this->discussions->count_unanswered_discussions()), 'class="btn btn-default btn-xs"'),
            'btn_all_discussions' => anchor( site_url(), sprintf($this->lang->line('btn_all_discussions'), $this->discussions->count_all_discussions()), 'class="btn btn-default btn-sx active"'),
            'btn_my_discussions' => anchor( site_url('discussions/my_discussions'), sprintf($this->lang->line('btn_my_discussions'), $this->discussions->count_user_discussions($this->session->userdata('user_id'))), 'class="btn btn-default btn-xs"'),
            'page_title' => $data['page_title'],
        );

        $this->construct_template($page_data, 'forums_template', $data['page_title']);

        $this->output->enable_profiler(TRUE);
    }
}