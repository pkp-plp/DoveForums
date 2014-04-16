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

class categories_m extends CI_Model {

    public function get_categories()
    {
        // Select.
        $this->db->select('
            category_id,
            category_name,
            category_permalink,
            category_description
        ');

        // Query.
        $query = $this->db->get('categories');

        // Result.
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row)
            {
                $data[] = array(
                    'category_id'           => $row['category_id'],
                    'category_name'         => $row['category_name'],
                    'category_permalink'    => $row['category_permalink'],
                    'category_description'  => $row['category_description'],
                );
            }

            return $data;
        }
        else
        {
            return false;
        }
    }

    public function count_discussions($category_id)
    {
        // Select.
        $this->db->select('*');

        // Options.
        $options = array(
            'category_id' => $category_id,
        );

        // Query.
        $query = $this->db->get_where('discussions', $options);

        // Result.
        if($query->num_rows() > 0)
        {
            return $query->num_rows();
        } else {
            return '0';
        }
    }
}