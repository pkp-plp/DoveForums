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

    public function get_all_discussions($limit=null, $offset=null)
    {
        // Select.
        $this->db->select('
            discussions.discussion_id,
            discussions.category_id,
            discussions.name,
            discussions.permalink,
            discussions.tags,
            discussions.created_by,
            discussions.created_date,
            discussions.created_ip,
            discussions.last_comment_by,
            discussions.last_comment_date,
            discussions.last_comment_ip,
            discussions.hearts,
            discussions.sticky,
            discussions.closed,
            categories.category_name,
            categories.category_permalink,
            users.username,
            users.email,
        ');

        // Join.
        $this->db->join('categories', 'categories.category_id = discussions.category_id');
        $this->db->join('users', 'users.id = discussions.created_by');

        // Order By
        $this->db->order_by('sticky', 'desc');
        $this->db->order_by('discussion_id', 'desc');

        // Limit
        $this->db->limit($limit, $offset);

        // Query.
        $query = $this->db->get('discussions');

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
                    'created_by'            => $row['username'],
                    'created_date'          => $row['created_date'],
                    'created_ip'            => $row['created_ip'],
                    'last_comment_by'       => $row['last_comment_by'],
                    'last_comment_date'     => $row['last_comment_date'],
                    'last_comment_ip'       => $row['last_comment_ip'],
                    'category_name'         => $row['category_name'],
                    'category_permalink'    => $row['category_permalink'],
                    'created_by_email'      => $row['email'],
                    'comments'              => $comment_count,
                    'tags'                  => $row['tags'],
                    'hearts'                => $row['hearts'],
                    'sticky'                => $row['sticky'],
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
        // Select
        $this->db->select('*');

        // Query
        $query = $this->db->get('discussions');

        // Result
        if($query->num_rows() > 0)
        {
            return $query->num_rows();
        } else {
            return '0';
        }
    }

    public function get_category_discussions($category_permalink)
    {

    }

    public function add_discussion($discussion_data)
    {
        // Insert.
        $this->db->insert('discussions', $discussion_data);

        if($this->db->affected_rows() > 0)
        {
            return true;
        } else {
            return false;
        }
    }
}