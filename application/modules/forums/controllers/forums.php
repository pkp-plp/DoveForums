<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forums extends Front_Controller {

    private $validation_rules = array(
        'new_discussion' => array(
            //0
            array(
                'field' => 'discussion_name',
                'rules' => 'required',
                'label' => 'lang: rules_name',
            ),
            //1
            array(
                'field' => 'comment',
                'rules' => 'required',
                'label' => 'lang: rules_comment',
            ),
            //2
            array(
                'field' => 'category',
                'rules' => 'required',
                'label' => 'lang:rules_category',
            ),
        ),
    );

    private $form_fields = array(
        'new_discussion' => array(
            //0
            array(
                'name' => 'discussion_name',
                'id' => 'discussion_name',
                'placeholder' => 'Enter discussion name.',
                'class' => 'form-control',
                'type' => 'text'
            ),
            //1
            array(
                'name' => 'comment',
                'id' => 'comment',
                'placeholder' => 'Enter comment.',
                'class' => 'form-control',
                'type' => 'textarea',
            ),
            //2
            array(
                'id' => 'category',
                'class' => 'form-control',
            ),
            //3
            array(
                'name' => 'tags',
                'id' => 'tags',
                'class' => 'form-control',
                'type' => 'text',
                'data-role' => 'tagsinput',
                'placeholder' => 'Add Tag & Press Enter.',
            )
        ),
    );

    public function __construct()
    {
        parent::__construct();

        // Load in the slug library.
        $config = array(
            'field' => 'permalink',
            'title' => 'name',
            'table' => 'discussions',
            'id' => 'discussion_id',
        );

        $this->load->library('slug', $config);
    }

    public function index()
    {
        // Set up the pagination.
        $config['base_url'] = site_url('forums/index');
        $config['total_rows'] = $this->discussions->count_all_discussions();
        $config['per_page'] = $this->settings->get_setting('discussions_per_page');
        $config['uri_segment'] = '3';
        $offset = $this->uri->segment('3');

        $this->pagination->initialize($config);

        // Get the latest discussions.
        $discussions = $this->discussions->get_all_discussions($config['per_page'], $offset);

        if($discussions)
        {
            foreach($discussions as $discussion)
            {
                $user = $this->ion_auth->user($discussion['last_comment_by'])->row();

                if($discussion['sticky'] == '1')
                {
                    $sticky = $this->lang->line('text_sticky');
                } else {
                    $sticky = '';
                }

                if($discussion['closed'] == '1')
                {
                    $closed = $this->lang->line('text_closed');
                } else {
                    $closed = '';
                }

                $data['discussions'][] = array(
                    'gravatar' => img(array('src' => $this->gravatar->get_gravatar($discussion['created_by_email'], 'x', '65'), 'class' => 'media-object img-thumbnail img-rounded')),
                    'discussion_name' => anchor(site_url('discussion/'.$discussion['category_permalink'].'/'.$discussion['discussion_permalink'].''), $discussion['discussion_name']),
                    'comments' => $discussion['comments'],
                    'last_comment_gravatar' => img(array('src' => $this->gravatar->get_gravatar($user->email, 'x', '40'), 'class' => 'media-object img-thumbnail img-rounded center-block')),
                    'last_comment_by' => anchor(site_url('users/profile/'.$user->username.''), $user->username),
                    'last_comment_date' => timespan($discussion['last_comment_date'], time()),
                    'category' => anchor(site_url('categories/'.$discussion['category_permalink'].'/'), $discussion['category_name'], 'class="label label-success"'),
                    'tags' => $this->process_tags($discussion['tags']),
                    'hearts' => $discussion['hearts'],
                    'closed' => $closed,
                    'sticky' => $sticky,
                    'edit_button' => anchor(site_url('discussion/edit_discussion/'.$discussion['discussion_permalink']), '<i class="fa fa-pencil"></i>', 'class="btn btn-success btn-sm"'),
                    'delete_button' => anchor(site_url('discussion/delete_discussion/'.$discussion['discussion_permalink']), '<i class="fa fa-trash-o"></i>', 'class="btn btn-success btn-sm"'),
                );
            }
        }

        $page_data = array(
            'text' => 'This is some test content.',
            'discussions' => $data['discussions'],
            'new_discussion_button' => anchor(site_url('discussion/new_discussion'), $this->lang->line('btn_new_discussion'), 'class="btn btn-success btn-icon btn-sm pull-right"'),
            'pagination' => $this->pagination->create_links(),
        );

        $this->construct_template($page_data, 'forums_template', $this->lang->line('page_home'));
    }

    public function new_discussion()
    {
        // Set the validation rules.
        $this->form_validation->set_rules($this->validation_rules['new_discussion']);

        // See if the form has been run.
        if($this->form_validation->run() == FALSE)
        {
            // Get categories from the database.
            $categories = $this->categories->get_categories();

            if($categories)
            {
                foreach($categories as $cat)
                {
                    $category_options[$cat['category_id']] = $cat['category_name'];
                }
            }
            $page_data = array(
                // Form Tags
                'form_open' => form_open(site_url('discussion/new_discussion'), array('id' => 'new_discussion')),
                'form_close' => form_close(),
                // Category Dropdown.
                'category_label' => form_label($this->lang->line('label_category'), $this->form_fields['new_discussion']['2']['id']),
                'category_field' => form_dropdown('category', $category_options, '0', 'class="selectpicker form-control show-tick show-menu-arrow" data-style="btn-default"'),
                // Discussion Name
                'discussion_name_label' => form_label($this->lang->line('label_discussion_name'), $this->form_fields['new_discussion']['0']['id']),
                'discussion_name_field' => form_input($this->form_fields['new_discussion']['0']),
                // Comment
                'comment_label' => form_label($this->lang->line('label_comment'), $this->form_fields['new_discussion']['1']['id']),
                'comment_field' => form_textarea($this->form_fields['new_discussion']['1']),
                // Tags
                'tags_label' => form_label($this->lang->line('label_tags'), $this->form_fields['new_discussion']['3']['id']),
                'tags_field' => form_input($this->form_fields['new_discussion']['3']),
                // Buttons
                'clear_button' => form_reset('reset', 'Clear', 'class="btn btn-danger btn-sm"'),
                'submit_button' => form_submit('submit', 'Create Discussion', 'class="btn btn-success btn-sm"'),
            );

            $this->construct_template($page_data, 'new_discussion_template', $this->lang->line('page_new_discussion'));
        }
        else
        {
            $discussion_data = array(
                'category_id' => $this->input->post('category'),
                'name' => $this->input->post('discussion_name'),
                'created_by' => $this->session->userdata('user_id'),
                'created_date' => now(),
                'created_ip' => $this->input->ip_address(),
                'last_comment_by' => $this->session->userdata('user_id'),
                'last_comment_date' => now(),
                'last_comment_ip' => $this->input->ip_address(),
                'permalink' => $this->slug->create_uri(array('permalink' => $this->input->post('discussion_name'))),
                'tags' => $this->input->post('tags'),
                'hearts' => '0',
                'sticky' => '0',
                'closed' => '0',
            );

            $insert = $this->discussions->add_discussion($discussion_data);

            if($insert == true)
            {
                $comment_data = array(
                    'comment' => $this->input->post('comment'),
                    'discussion_id' => $this->db->insert_id(),
                    'created_by' => $this->session->userdata('user_id'),
                    'created_ip' => $this->input->ip_address(),
                );

                $insert2 = $this->comments->add_comment($comment_data);

                if($insert2 == true)
                {
                    // Discussion has been created.
                    // ** Trigger a message **
                    redirect(site_url());
                } else {
                    // Discussion failed to be created.
                    // ** Trigger a message **
                    redirect(site_url());
                }
            } else {
                // Discussion failed to be created.
                // ** Trigger a message **
                redirect(site_url());
            }
        }
    }

    public function edit_discussion($discussion_permalink)
    {

    }

    public function delete_discussion($discussion_permalink)
    {

    }
}