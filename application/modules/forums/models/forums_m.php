<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forums_m extends CI_Model {

    public function get_forums($category_id)
    {
        // Get all the forums for this category.
        $this->db->select('
            forums.id,
            forums.title,
            forums.permalink,
            forums.description,
            forums.latest_discussion_by,
            forums.latest_discussion_time,
            forums.discussions,
            forums.posts,
            forums.order,
            forums.active,
            forums.category_id,
            users.id,
            users.username,
            users.email,
        ');

        // Order
        $this->db->order_by('forums.order', 'asc');

        // Join.
        $this->db->join('users', 'users.id = forums.latest_discussion_by');

        // Options.
        $options = array(
            'forums.active' => '1',
            'forums.category_id' => $category_id,
        );

        // Query.
        $query = $this->db->get_where('forums', $options);

        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result_array() as $row )
            {
                $data[] = array(
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'permalink' => $row['permalink'],
                    'description' => $row['description'],
                    'latest_discussion_by' => $row['username'],
                    'latest_discussion_time' => $row['latest_discussion_time'],
                    'discussions' => $row['discussions'],
                    'posts' => $row['posts'],
                    'order' => $row['order'],
                    'category_id' => $row['category_id'],
                    'last_post_by_gravatar' => $row['email'],
                );
            }

            return $data;
        }
    }
}
