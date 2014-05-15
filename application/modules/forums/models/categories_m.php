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
        // Query.
        $query = $this->db->select('id, name, permalink, description, order')
                            ->get($this->tables['categories']);

        // Result.
        return ( $query->num_rows() > 0 ? $query->result_array() : NULL );
    }

    public function count_discussions($category_id)
    {
        // Query.
        $query = $this->db->select('*')
                            ->where('category_id', $category_id)
                            ->get($this->tables['discussions']);

        // Result.
        return ( $query->num_rows() > 0 ? $query->num_rows() : 0 );
    }

    public function get_category_permalink_by_id($category_id)
    {
        // Query.
        $query = $this->db->select('permalink')
                            ->where('id', $category_id)
                            ->limit(1)
                            ->get($this->tables['categories']);

        // Result.
        return ( $query->num_rows() > 0 ? $query->row('permalink') : NULL );
    }

    public function get_id_by_category_permalink($category_permalink)
    {
        // Query.
        $query = $this->db->select('id')
                            ->where('permalink', $category_permalink)
                            ->limit(1)
                            ->get($this->tables['categories']);

        // Result.
        return ( $query->num_rows() > 0 ? $query->row('id') : NULL );
    }

    public function get_category_name_by_permalink($category_permalink)
    {
        // Query.
        $query = $this->db->select('name')
                            ->where('permalink', $category_permalink)
                            ->limit(1)
                            ->get($this->tables['categories']);
        // Result.
        return ( $query->num_rows() > 0 ? $query->row('name') : NULL );
    }
}