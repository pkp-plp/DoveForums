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
        return $query->num_rows();
    }

    public function add_comment($comment_data)
    {
        // Insert
        $this->db->insert('comments', $comment_data);

        if($this->db->affected_rows() > 0)
        {
            return true;
        } else {
            return false;
        }
    }
}