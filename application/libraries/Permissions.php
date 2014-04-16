<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissions {

    // Set some variables.

    private $ci;
    public $permissions = array();

    public function __construct()
    {
        // Set CI instance.
        $this->ci =& get_instance();

        // See if a group_id has been set.
        $this->group_id = ($this->ci->session->userdata('group_id')) ? $this->ci->session->userdata('group_id') : 0;
    }

    public function get_permissions($group_id)
    {
        // Select.
        $this->ci->db->select('key');

        // Join.
        $this->ci->db->join('permissions', 'permissions.permission_id = permission_map.permissions_id');

        // Where.
        $this->ci->db->where('group_id', $group_id);

        // Query.
        $query = $this->ci->db->get('permission_map');

        // Results.
        if($query->num_rows())
        {
            foreach($query->result_array() as $row)
            {
                $permissions[] = $row['key'];
            }

            return $permissions;
        }
        else
        {
            return false;
        }
    }

    public function has_permission($permission_key)
    {
        if(in_array($permission_key, $this->permissions))
        {
            return true;
        } else {
            return false;
        }
    }
}

