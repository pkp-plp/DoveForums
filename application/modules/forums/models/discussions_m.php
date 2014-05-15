<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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

class discussions_m extends CI_Model {

    public function get_discussions($limit=NULL, $offset=NULL, $filter=NULL)
    {
        // Set hook.
        $this->dove_core->trigger_events('pre_get_all_discussions');

        // Select.
        $this->db->select('discussions.discussion_id, discussions.category_id, discussions.name, discussions.permalink,
            discussions.answered, discussions.created_by, discussions.created_date, discussions.created_ip,
            discussions.last_comment_by, discussions.last_comment_date, discussions.last_comment_ip, discussions.likes,
            discussions.announcement, discussions.closed, categories.name as category_name,
            categories.permalink as category_permalink, categories.description as category_description,
            users.username, users.email')
                            ->join('categories', 'categories.id = discussions.category_id')
                            ->join('users', 'users.id = discussions.created_by')
                            ->order_by('announcement', 'desc')
                            ->order_by('discussion_id', 'desc')
                            ->order_by('answered', 'desc')
                            ->limit($limit, $offset);

        // Options.
        if ( isset($filter) )
        {
            if ( strtolower($filter) == 'unanswered_discussions')
            {
                $this->db->where('answered', 0);
            }
            elseif ( strtolower($filter) == 'my_discussions')
            {
                $this->db->where('created_by', $this->session->userdata('user_id'));
            }
        }

        // Query.
        $query = $this->db->get($this->tables['discussions']);

        // Results.
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row)
            {
                // Count the amount of comments.
                $comment_count = $this->comments->count_discussion_comments($row['discussion_id']);

                $data[] = array(
                    'discussion_id'         => $row['discussion_id'],
                    'category_id'           => $row['category_id'],
                    'discussion_name'       => $row['name'],
                    'discussion_permalink'  => $row['permalink'],
                    'created_by'            => $row['created_by'],
                    'created_by_username'   => $row['username'],
                    'created_date'          => $row['created_date'],
                    'created_ip'            => $row['created_ip'],
                    'last_comment_by'       => $row['last_comment_by'],
                    'last_comment_date'     => $row['last_comment_date'],
                    'last_comment_ip'       => $row['last_comment_ip'],
                    'category_name'         => $row['category_name'],
                    'category_permalink'    => $row['category_permalink'],
                    'category_description'  => $row['category_description'],
                    'created_by_email'      => $row['email'],
                    'comments'              => $comment_count,
                    'answered'              => $row['answered'],
                    'likes'                 => $row['likes'],
                    'announcement'          => $row['announcement'],
                    'closed'                => $row['closed'],
                );
            }

            return $data;
        }
        else
        {
            return false;
        }
    }

    public function get_category_discussions($category_id, $limit=NULL, $offset=NULL)
    {
        // Set hook.
        $this->dove_core->trigger_events('pre_get_category_discussions');

        // Select.
        $query = $this->db->select('discussions.discussion_id, discussions.category_id, discussions.name,
            discussions.permalink, discussions.answered, discussions.created_by, discussions.created_date,
            discussions.created_ip, discussions.last_comment_by, discussions.last_comment_date,
            discussions.last_comment_ip, discussions.likes, discussions.announcement, discussions.closed,
            categories.name as category_name, categories.permalink as category_permalink,
            categories.description as category_description, users.username, users.email')
                            ->join('categories', 'categories.id = discussions.category_id')
                            ->join('users', 'users.id = discussions.created_by')
                            ->order_by('announcement', 'desc')
                            ->order_by('discussion_id', 'desc')
                            ->order_by('answered', 'desc')
                            ->limit($limit, $offset)
                            ->where('category_id', $category_id)
                            ->get($this->tables['discussions']);

        // Results.
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row)
            {
                // Count the amount of comments.
                $comment_count = $this->comments->count_discussion_comments($row['discussion_id']);

                $data[] = array(
                    'discussion_id'         => $row['discussion_id'],
                    'category_id'           => $row['category_id'],
                    'discussion_name'       => $row['name'],
                    'discussion_permalink'  => $row['permalink'],
                    'created_by'            => $row['created_by'],
                    'created_by_username'   => $row['username'],
                    'created_date'          => $row['created_date'],
                    'created_ip'            => $row['created_ip'],
                    'last_comment_by'       => $row['last_comment_by'],
                    'last_comment_date'     => $row['last_comment_date'],
                    'last_comment_ip'       => $row['last_comment_ip'],
                    'category_name'         => $row['category_name'],
                    'category_permalink'    => $row['category_permalink'],
                    'category_description'  => $row['category_description'],
                    'created_by_email'      => $row['email'],
                    'comments'              => $comment_count,
                    'answered'              => $row['answered'],
                    'likes'                 => $row['likes'],
                    'announcement'          => $row['announcement'],
                    'closed'                => $row['closed'],
                );
            }

            return $data;
        }
        else
        {
            return false;
        }
    }

    public function count_all_discussions()
    {
        // Query
        $query = $this->db->select('*')
                            ->get($this->tables['discussions']);

        // Result
        return ( $query->num_rows() > 0 ? $query->num_rows() : 0 );
    }

    public function count_category_discussions($category_permalink)
    {
        // Get category ID.
        $category_id = $this->categories->get_id_by_category_permalink($category_permalink);

        // Query
        $query = $this->db->select('*')
                            ->where('category_id', $category_id)
                            ->get($this->tables['discussions']);

        // Result
        return ( $query->num_rows() > 0 ? $query->num_rows() : 0 );
    }

    public function count_unanswered_discussions()
    {
        // Query
        $query = $this->db->select('*')
                            ->where('answered', '0')
                            ->get($this->tables['discussions']);

        // Result
        return ( $query->num_rows() > 0 ? $query->num_rows() : 0 );
    }

    public function count_user_discussions($user_id)
    {
        // Query.
        $query = $this->db->select('*')
                            ->where('created_by', $user_id)
                            ->get($this->tables['discussions']);

        // Result.
        return ( $query->num_rows() > 0 ? $query->num_rows() : 0 );
    }

    public function add_discussion($discussion_data, $comment_data)
    {
        // Trans start.
        $this->db->trans_start();

        // Insert.
        $this->db->insert($this->tables['discussions'], $discussion_data);
        $insert_id = $this->db->insert_id();

        // Add insert id to comments array.
        $comment_data['discussion_id'] = $insert_id;

        $this->db->insert($this->tables['comments'], $comment_data);

        // Trans end.
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            $this->dove_core->set_error('create_discussion_failed.');
            $this->db->trans_rollback();
            return FALSE;
        }
        else
        {
            $this->dove_core->set_message('create_discussion_successful');
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function get_id_from_permalink($permalink)
    {
        // Query.
        $query = $this->db->select('discussion_id')
                    ->where('permalink', $permalink)
                    ->limit('1')
                    ->get($this->tables['discussions']);

        // Result.
        return ( $query->num_rows() > 0 ? $query->row('discussion_id') : 0 );
    }

    public function delete($discussion_id)
    {
        // Delete.
        $delete = $this->dove_core->delete(array('discussion_id' => $discussion_id), $this->tables['discussions']);

        if ( $delete === TRUE )
        {
            $delete = $this->dove_core->delete(array('discussion_id' => $discussion_id), $this->tables['comments']);

            if ( $delete === TRUE )
            {
                $this->dove_core->set_message('remove_discussion_success');
                return TRUE;
            }
            else
            {
                $this->dove_core->set_error('remove_discussion_failed');
                return FALSE;
            }
        }
        else
        {
            $this->dove_core->set_error('remove_discussion_failed');
            return FALSE;
        }
    }

    public function get_discussion_id_from_permalink($discussion_permalink)
    {
        // Query.
        $query = $this->db->select('discussion_id')
                    ->where('permalink', $discussion_permalink)
                    ->get($this->tables['discussions']);

        // Result.
        return ( $query->num_rows() > 0 ? $query->row('discussion_id') : false );
    }

    public function get_discussion_name_from_permalink($discussion_permalink)
    {
        // Query.
        $query = $this->db->select('name')
                            ->where('permalink', $discussion_permalink)
                            ->get('discussions');

        // Result.
        return ( $query->num_rows() > 0 ? $query->row('name') : false );
    }
}