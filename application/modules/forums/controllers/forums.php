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
        $config['uri_segment'] = $this->uri->segment('3');

        // Initialize the pagination.
        $this->pagination->initialize($config);

        // Get discussions from the database.
        $discussions = $this->discussions->get_discussions($config['per_page'], $config['uri_segment']);

        // Initialize some variables.
        $data['unanswered'] = (int) 0;
        $data['my_discussions'] = (int) 0;
        $data['has_discussions'] = ( is_array($discussions) ? (int) 1 : (int) 0 );

        if ( is_array($discussions))
        {
            foreach($discussions as $row)
            {
                $user = $this->dove_core->user($row->last_comment_by);

                // See if the discussion is a sticky.
                $data['announcement'] = ( $row->announcement == 1 ? $this->lang->line('text_announcement') : NULL );

                // See if the discussion is closed.
                $data['closed'] = ( $row->closed == 1 ? $this->lang->line('text_closed') : NULL );

                // Is the discussion been marked as answered.
                if ( $row->answered == 0 )
                {
                    $data['unanswered']++;
                    $data['tag'] = '<span class="label label-info" title="'.$this->lang->line('title_unanswered').'">'.$this->lang->line('label_unanswered').'</span>';
                } else {
                    $data['tag'] = '<span class="label label-success" title="'.$this->lang->line('title_answered').'">'.$this->lang->line('label_answered').'</span>';
                }

                // Is the logged in user the creator of the discussion.
                if ( $row->created_by == $this->session->userdata('user_id') )
                {
                    $data['my_discussions']++;
                    $data['owned'] = (int) 1;
                }
                else
                {
                    $data['owned'] = (int) 0;
                }

                // Build data array.
                $data['discussions'][] = array(
                    'discussion_info' => array(
                        'discussion_name' => anchor( site_url('discussion/'.$row->category_permalink.'/'.$row->discussion_permalink.''), $row->discussion_name),
                        'comments' => $this->comments->count_discussion_comments( (int) $row->discussion_id),
                        'last_comment_by' => anchor( site_url('users/profile/'.$user->username.''), $user->username),
                        'last_comment_date' => timespan( $row->last_comment_date, time() ),
                        'category' => anchor( site_url('categories/'.$row->category_permalink.'/'), '<i class="fa fa-sitemap"></i> '.$row->category_name.'', 'class="label label-default" title="'.sprintf($this->lang->line('label_category'), $row->category_name).' - '.$row->category_description.'"' ),
                        'tag' => element('tag', $data),
                        'likes' => $row->likes,
                        'closed' => element('closed', $data),
                        'announcement' => element('announcement', $data),
                        'owned' => element('owned', $data),
                    ),
                    'user_info' => array(
                        'gravatar' => img( array( 'src' => $this->gravatar->get_gravatar( $row->created_by_email, $this->config->item('gravatar_rating'), '45', $this->config->item('default_image') ), 'class' => 'media-object img-thumbnail img-responsive') ),
                    ),
                    'buttons' => array(
                        'btn_edit' => button( 'discussion/edit_discussion/'.$row->discussion_permalink, '<i class="fa fa-pencil"></i>', 'class="btn btn-success btn-sm"' ),
                        'btn_delete' => button( 'discussion/delete_discussion/'.$row->discussion_permalink, '<i class="fa fa-trash-o"></i>', 'class="btn btn-success btn-sm"' ),
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
            'discussions' => element('discussions', $data),
            'pagination' => $this->pagination->create_links(),
            'has_discussions' => element('has_discussions', $data),
            'btn_unanswered_discussions' => button( 'discussions/unanswered_discussions', sprintf($this->lang->line('btn_unanswered_discussions'), $this->discussions->count_unanswered_discussions()), 'class="btn btn-default btn-xs"'),
            'btn_all_discussions' => button( NULL, sprintf($this->lang->line('btn_all_discussions'), count ($discussions) ), 'class="btn btn-default btn-sx active"'),
            'btn_my_discussions' => button( 'discussions/my_discussions', sprintf($this->lang->line('btn_my_discussions'), $this->discussions->count_user_discussions( (int) $this->session->userdata('user_id'))), 'class="btn btn-default btn-xs"'),
            'page_title' => 'All Discussions',
        );

        $this->construct_template($page_data, 'forums_template', element('page_title', $page_data));
    }

    public function filtered($filter)
    {
        // Set up the pagination.
        $config['base_url'] = site_url('discussions/'.$filter.'');
        $config['per_page'] = $this->config->item('discussions_per_page');
        $config['uri_segment'] = $this->uri->segment('3');

        if( isset($filter) )
        {
            if ( strtolower($filter) == 'unanswered_discussions' )
            {
                $config['total_rows'] = $this->discussions->count_unanswered_discussions();
                $data['page_title'] = $this->lang->line('page_unanswered');
            }
            elseif ( strtolower($filter) == 'my_discussions' )
            {
                $config['total_rows'] = $this->discussions->count_user_discussions($this->session->userdata('user_id'));
                $data['page_title'] = $this->lang->line('page_my_discussions');
            }
        }

        $this->pagination->initialize($config);

        // Get the latest discussions.
        $discussions = $this->discussions->get_discussions($config['per_page'], $config['uri_segment'], $filter);

        // Initialize some variables.
        $data['unanswered'] = (int) 0;
        $data['my_discussions'] = (int) 0;
        $data['has_discussions'] = ( is_array($discussions) ? (int) 1 : (int) 0 );

        if ( is_array($discussions) )
        {
            foreach($discussions as $row)
            {
                $user = $this->dove_core->user($row->last_comment_by);

                // See if the discussion is a sticky.
                $data['announcement'] = ( $row->announcement == 1 ? $this->lang->line('text_announcement') : NULL );

                // See if the discussion is closed.
                $data['closed'] = ( $row->closed == 1 ? $this->lang->line('text_closed') : NULL );

                if ( $row->answered == 0 )
                {
                    $data['unanswered']++;
                    $data['tag'] = '<span class="label label-info" title="'.$this->lang->line('title_unanswered').'">'.$this->lang->line('label_unanswered').'</span>';
                }
                else
                {
                    $data['tag'] = '<span class="label label-success" title="'.$this->lang->line('title_answered').'">'.$this->lang->line('label_answered').'</span>';
                }

                if ( $row->created_by == $this->session->userdata('user_id') )
                {
                    $data['my_discussions']++;
                    $data['owned'] = (int) 1;
                }
                else
                {
                    $data['owned'] = (int) 0;
                }

                $data['discussions'][] = array(
                    'discussion_info' => array(
                        'discussion_name' => anchor( site_url('discussion/'.$row->category_permalink.'/'.$row->discussion_permalink.''), $row->discussion_name),
                        'comments' => $this->comments->count_discussion_comments( (int) $row->discussion_id ),
                        'last_comment_by' => anchor( site_url('users/profile/'.$user->username.''), $user->username),
                        'last_comment_date' => timespan( $row->last_comment_date, time() ),
                        'category' => anchor( site_url('categories/'.$row->category_permalink.'/'), '<i class="fa fa-sitemap"></i> '.$row->category_name.'', 'class="label label-default" title="'.sprintf($this->lang->line('label_category'), $row->category_name).' - '.$row->category_description.'"' ),
                        'tag' => element('tag', $data),
                        'likes' => $row->likes,
                        'closed' => element('closed', $data),
                        'announcement' => element('announcement', $data),
                        'owned' => element('owned', $data),
                    ),
                    'user_info' => array(
                        'gravatar' => img( array( 'src' => $this->gravatar->get_gravatar( $row->created_by_email, $this->config->item('gravatar_rating'), '45', $this->config->item('default_image') ), 'class' => 'media-object img-thumbnail img-responsive') ),
                    ),
                    'buttons' => array(
                        'btn_edit' => button( 'discussion/edit_discussion/'.$row->discussion_permalink, '<i class="fa fa-pencil"></i>', 'class="btn btn-success btn-sm"' ),
                        'btn_delete' => button( 'discussion/delete_discussion/'.$row->discussion_permalink, '<i class="fa fa-trash-o"></i>', 'class="btn btn-success btn-sm"' ),
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
            'discussions' => element('discussions', $data),
            'pagination' => $this->pagination->create_links(),
            'has_discussions' => element('has_discussions', $data),
            'btn_unanswered_discussions' => button( 'discussions/unanswered_discussions', sprintf($this->lang->line('btn_unanswered_discussions'), $this->discussions->count_unanswered_discussions()), 'class="btn btn-default btn-xs"'),
            'btn_all_discussions' => button( NULL, sprintf($this->lang->line('btn_all_discussions'), count ($discussions) ), 'class="btn btn-default btn-sx active"'),
            'btn_my_discussions' => button( 'discussions/my_discussions', sprintf($this->lang->line('btn_my_discussions'), $this->discussions->count_user_discussions( (int) $this->session->userdata('user_id') )), 'class="btn btn-default btn-xs"'),
            'page_title' => $data['page_title'],
        );

        $this->construct_template($page_data, 'forums_template', element('page_title', $data));
    }
}