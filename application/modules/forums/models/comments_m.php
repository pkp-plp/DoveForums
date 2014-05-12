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

class comments_m extends CI_Model {

    public function get_comments($discussion_id)
    {
        // Select.
        $this->db->select('
            comments.comment_id,
            comments.comment,
            comments.created_by,
            comments.created_date,
            comments.created_ip,
            comments.discussion_id,
            users.id as user_id,
            users.username,
            users.email,
            users.group_id,
            users.signature,
            groups.display_name,
        ');

        // Join
        $this->db->join('users', 'users.id = comments.created_by');
        $this->db->join('groups', 'groups.id = users.group_id');

        // Where.
        $this->db->where('discussion_id', $discussion_id);

        // Query.
        $query = $this->db->get('comments');

        // Result.
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row)
            {
                $comments[] = array(
                    'comment' => $row['comment'],
                    'created_date' => $row['created_date'],
                    'created_by' => $row['username'],
                    'created_by_email' => $row['email'],
                    'group_id' => $row['group_id'],
                    'signature' => $row['signature'],
                    'group_name' => $row['display_name'],
                    'user_id' => $row['user_id']
                );
            }

            return $comments;
        }
        else
        {
            return false;
        }
    }

    public function count_discussion_comments($discussion_id)
    {
        // Select
        $this->db->select('*');

        // Options.
        $options = array(
            'discussion_id' => $discussion_id,
        );

        // Query.
        $query = $this->db->get_where('comments', $options);

        // Result.
        return ( $query->num_rows() > 0 ? $query->num_rows() : 0 );
    }

    public function add_comment($comment_data)
    {
        // Insert
        $this->db->insert('comments', $comment_data);

        return ( $this->db->affected_rows() > 0 ? true : false );
    }

    public function delete_comments($discussion_id)
    {
        // Where.
        $this->db->where('discussion_id', $discussion_id);

        // Query.
        $this->db->delete('comments');

        // Result.
        return ( $this->db->affected_rows() > 0 ? true : false );
    }
}