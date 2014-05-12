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
        $config['per_page'] = $this->config->item('discussions_per_page');
        $config['uri_segment'] = '3';
        $offset = $this->uri->segment('3');

        $this->pagination->initialize($config);

        // Get the latest discussions.
        $discussions = $this->discussions->get_discussions($config['per_page'], $offset);

        $has_discussions = ( is_array($discussions) ? TRUE : FALSE );

        // Initialize some variables.
        $data['unanswered'] = 0;
        $data['my_discussions'] = 0;

        if (($discussions))
        {
            foreach($discussions as $discussion)
            {
                $user = $this->dove_core->user($discussion['last_comment_by']);

                // See if the discussion is a sticky.
                $announcement = ( $discussion['announcement'] == 1
                    ? $this->lang->line('text_announcement')
                    : '' );

                // See if the discussion is closed.
                $closed = ( $discussion['closed'] == 1 ? $this->lang->line('text_closed') : '' );

                if ( $discussion['answered'] == 0 )
                {
                    $data['unanswered']++;
                    $data['tag'] = '<span class="label label-info" title="'.$this->lang->line('title_unanswered').'">'.$this->lang->line('label_unanswered').'</span>';
                } else {
                    $data['tag'] = '<span class="label label-success" title="'.$this->lang->line('title_answered').'">'.$this->lang->line('label_answered').'</span>';
                }

                if ( $discussion['created_by'] == $this->session->userdata('user_id') )
                {
                    $data['my_discussions']++;
                    $owned = 1;
                }
                else
                {
                    $owned = 0;
                }

                $data['discussions'][] = array(
                    'gravatar' => img(array('src' => $this->gravatar->get_gravatar($discussion['created_by_email'], $this->config->item('gravatar_rating'), '45', $this->config->item('default_image')), 'class' => 'media-object img-thumbnail img-responsive')),
                    'discussion_name' => anchor(site_url('discussion/'.$discussion['category_permalink'].'/'.$discussion['discussion_permalink'].''), $discussion['discussion_name']),
                    'comments' => $discussion['comments'],
                    'last_comment_by' => anchor(site_url('users/profile/'.$user->username.''), $user->username),
                    'last_comment_date' => timespan($discussion['last_comment_date'], time()),
                    'category' => anchor( site_url('categories/'.$discussion['category_permalink'].'/'), '<i class="fa fa-sitemap"></i> '.$discussion['category_name'].'', 'class="label label-default" title="'.sprintf($this->lang->line('label_category'), $discussion['category_name']).' - '.$discussion['category_description'].'"' ),
                    'tag' => $data['tag'],
                    'likes' => $discussion['likes'],
                    'closed' => $closed,
                    'announcement' => $announcement,
                    'edit_button' => anchor(site_url('discussion/edit_discussion/'.$discussion['discussion_permalink']), '<i class="fa fa-pencil"></i>', 'class="btn btn-success btn-sm"'),
                    'delete_button' => anchor(site_url('discussion/delete_discussion/'.$discussion['discussion_permalink']), '<i class="fa fa-trash-o"></i>', 'class="btn btn-success btn-sm"'),
                    'owned' => $owned,
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
            'discussions' => $data['discussions'],
            'pagination' => $this->pagination->create_links(),
            'has_discussions' => $has_discussions,
            'btn_unanswered_discussions' => anchor( site_url('discussions/unanswered_discussions'), sprintf($this->lang->line('btn_unanswered_discussions'), $this->discussions->count_unanswered_discussions()), 'class="btn btn-default btn-xs"'),
            'btn_all_discussions' => anchor( site_url(), sprintf($this->lang->line('btn_all_discussions'), $this->discussions->count_all_discussions()), 'class="btn btn-default btn-sx active"'),
            'btn_my_discussions' => anchor( site_url('discussions/my_discussions'), sprintf($this->lang->line('btn_my_discussions'), $this->discussions->count_user_discussions($this->session->userdata('user_id'))), 'class="btn btn-default btn-xs"'),
            'page_title' => 'All Discussions',
        );

        $this->construct_template($page_data, 'forums_template', $page_data['page_title']);

        $this->output->enable_profiler(false);
    }

    public function filtered($filter)
    {
        // Set up the pagination.
        $config['base_url'] = site_url('discussions/'.$filter.'');
        $config['per_page'] = $this->config->item('discussions_per_page');
        $config['uri_segment'] = '3';
        $offset = $this->uri->segment('3');

        if( isset($filter) )
        {
            if ( strtolower($filter) == 'unanswered_discussions')
            {
                $config['total_rows'] = $this->discussions->count_unanswered_discussions();
                $data['page_title'] = $this->lang->line('page_unanswered');
            }
            elseif ( strtolower($filter) == 'my_discussions')
            {
                $config['total_rows'] = $this->discussions->count_user_discussions($this->session->userdata('user_id'));
                $data['page_title'] = $this->lang->line('page_my_discussions');
            }
        }

        $this->pagination->initialize($config);

        // Get the latest discussions.
        $discussions = $this->discussions->get_discussions($config['per_page'], $offset, $filter);

        $has_discussions = ( is_array($discussions) ? TRUE : FALSE );

        // Initialize some variables.
        $data['unanswered'] = 0;
        $data['my_discussions'] = 0;

        if (($discussions))
        {
            foreach($discussions as $discussion)
            {
                $user = $this->dove_core->user($discussion['last_comment_by']);

                // See if the discussion is a sticky.
                $announcement = ( $discussion['announcement'] == 1 ? $this->lang->line('text_announcement') : '' );

                // See if the discussion is closed.
                $closed = ( $discussion['closed'] == 1 ? $this->lang->line('text_closed') : '' );

                if ( $discussion['answered'] == 0 )
                {
                    $data['unanswered']++;
                    $data['tag'] = '<span class="label label-info" title="'.$this->lang->line('title_unanswered').'">'.$this->lang->line('label_unanswered').'</span>';
                } else {
                    $data['tag'] = '<span class="label label-success" title="'.$this->lang->line('title_answered').'">'.$this->lang->line('label_answered').'</span>';
                }

                if ( $discussion['created_by'] == $this->session->userdata('user_id') )
                {
                    $data['my_discussions']++;
                    $owned = 1;
                }
                else
                {
                    $owned = 0;
                }

                $data['discussions'][] = array(
                    'gravatar' => img(array('src' => $this->gravatar->get_gravatar($discussion['created_by_email'], $this->config->item('gravatar_rating'), '45', $this->config->item('default_image')), 'class' => 'media-object img-thumbnail img-responsive')),
                    'discussion_name' => anchor(site_url('discussion/'.$discussion['category_permalink'].'/'.$discussion['discussion_permalink'].''), $discussion['discussion_name']),
                    'comments' => $discussion['comments'],
                    'last_comment_by' => anchor(site_url('users/profile/'.$user->username.''), $user->username),
                    'last_comment_date' => timespan($discussion['last_comment_date'], time()),
                    'category' => anchor( site_url('categories/'.$discussion['category_permalink'].'/'), '<i class="fa fa-sitemap"></i> '.$discussion['category_name'].'', 'class="label label-default" title="'.sprintf($this->lang->line('label_category'), $discussion['category_name']).'"' ),
                    'tag' => $data['tag'],
                    'likes' => $discussion['likes'],
                    'closed' => $closed,
                    'announcement' => $announcement,
                    'edit_button' => anchor(site_url('discussion/edit_discussion/'.$discussion['discussion_permalink']), '<i class="fa fa-pencil"></i>', 'class="btn btn-success btn-sm"'),
                    'delete_button' => anchor(site_url('discussion/delete_discussion/'.$discussion['discussion_permalink']), '<i class="fa fa-trash-o"></i>', 'class="btn btn-success btn-sm"'),
                    'owned' => $owned,
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
            'discussions' => $data['discussions'],
            'pagination' => $this->pagination->create_links(),
            'has_discussions' => $has_discussions,
            'btn_unanswered_discussions' => anchor( site_url('discussions/unanswered_discussions'), sprintf($this->lang->line('btn_unanswered_discussions'), $this->discussions->count_unanswered_discussions()), 'class="btn btn-default btn-xs"'),
            'btn_all_discussions' => anchor( site_url(), sprintf($this->lang->line('btn_all_discussions'), $this->discussions->count_all_discussions()), 'class="btn btn-default btn-sx active"'),
            'btn_my_discussions' => anchor( site_url('discussions/my_discussions'), sprintf($this->lang->line('btn_my_discussions'), $this->discussions->count_user_discussions($this->session->userdata('user_id'))), 'class="btn btn-default btn-xs"'),
            'page_title' => $data['page_title'],
        );

        $this->construct_template($page_data, 'forums_template', $data['page_title']);

        $this->output->enable_profiler(TRUE);
    }

    public function new_discussion()
    {
        // Login check.
        $this->login_check();

        // Check permissions.
        $this->permission_check('create_discussions');

        // Set the validation rules.
        $this->form_validation->set_rules($this->validation_rules['new_discussion']);

        // See if the form has been run.
        if($this->form_validation->run() === FALSE)
        {
            // Get categories from the database.
            $categories = $this->categories->get_categories();

            if($categories)
            {
                foreach($categories as $cat)
                {
                    $category_options[$cat['id']] = $cat['name'];
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
                'likes' => '0',
                'announcement' => '0',
                'closed' => '0',
            );

            $comment_data = array(
                'comment' => $this->input->post('comment'),
                'created_by' => $this->session->userdata('user_id'),
                'created_date' => now(),
                'created_ip' => $this->input->ip_address(),
            );

            $insert_discussion = $this->discussions->add_discussion($discussion_data, $comment_data);

            if ($insert_discussion === TRUE)
            {
                // Award XP.
                $this->dove_core->add_xp('1', $this->session->userdata('user_id'));
                $this->create_message('success', $this->dove_core->messages());
                redirect ( site_url('discussion/'.$this->categories->get_category_permalink_by_id($discussion_data['category_id']).'/'.$discussion_data['permalink'].'') );
            }
            else
            {
                $this->create_message('error', $this->dove_core->errors());
                redirect ( site_url() );
            }
        }
    }

    public function edit_discussion($discussion_permalink)
    {
        // Login check.
        $this->login_check();

        // Permission check.
        $this->permission_check('edit_discussions');
    }

    public function delete_discussion($discussion_permalink)
    {
        // Login check.
        $this->login_check();

        // Permission check.
        $this->permission_check('delete_discussions');

        if ( !isset($discussion_permalink) )
        {
            $this->create_message('error', 'No permalink supplied.');
            redirect(site_url('forums'));
        }

        $discussion_id = $this->discussions->get_id_from_permalink($discussion_permalink);

        if ( isset($discussion_id) && isset($discussion_permalink) )
        {
            $delete = $this->discussions->delete($discussion_id);

            if ( $delete === TRUE )
            {
                $this->create_message('success', $this->dove_core->messages());
                redirect ( site_url() );
            }
            else
            {
                $this->create_message('error', $this->dove_core->errors());
                redirect ( site_url() );
            }
        }
        else
        {
            $this->dove_core->set_error('general_error');

            $this->create_message('error', $this->dove_core->errors());
            redirect ( site_url() );
        }
    }
}