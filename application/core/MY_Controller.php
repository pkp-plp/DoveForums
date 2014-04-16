<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller{

    public function __construct()
    {
        parent::__construct();

        // Load in required models.
        $this->load->model('forums/categories_m', 'categories');
        $this->load->model('forums/discussions_m', 'discussions');
        $this->load->model('forums/users_m', 'users');
        $this->load->model('forums/comments_m', 'comments');

        // Load in required language files.
        $this->load->language('forums/frontend', $this->settings->get_setting('site_language'));
    }
}

class Front_Controller extends MY_Controller{

    public $frontend_theme;
    public $admin_theme;
    public $sidebar_display;

    public function __construct()
    {
        parent::__construct();

        // Set the frontend theme.
            $this->parser->theme($this->settings->get_setting('frontend_theme'));
    }

    public function construct_template($page_data, $page, $page_title)
    {
        // Add languages to the parser.
        $languages = array(
            'frontend' => $this->load->language('forums/frontend', $this->settings->get_setting('site_language')),
        );

        // Meta Data.
            $meta = array(
                'keywords' => $this->settings->get_setting('site_keywords'),
                'description' => $this->settings->get_setting('site_description'),
                'author' => $this->settings->get_setting('site_author'),
                'site_title' => ''.$this->settings->get_setting('site_name').' - '.$page_title.'',
            );

        // Auth Data.
            $auth = array(
                'logged_in' => $this->ion_auth->logged_in(),
                'is_admin' => $this->ion_auth->is_admin(),
            );

        // Append data to the parser.
            $this->parser->append('meta', $meta);
            $this->parser->append('lang', $languages);
            $this->parser->append('auth', $auth);

        // Config for parser.
            $config['show'] = false;

        // Construct the navigation.
            $navigation_data = array(
                'logo' => anchor(site_url(), $this->settings->get_setting('site_name'), 'class="navbar-brand"'),
                'sign_in_link' => anchor(site_url('members/sign_in'), '<i class="fa fa-sign-in"></i> Sign In'),
                'sign_out_link' => anchor(site_url('members/sign_out'), '<i class="fa fa-sign-out"></i> Sign Out'),
            );

            $data['navigation'] = $this->parser->parse('sections/navigation_template', $navigation_data, $config);

        // Construct the content.
            $data['content'] = $this->parser->parse('pages/'.$page.'', $page_data, $config);

        // Construct the sidebar.
            // Get the categories for the sidebar.
            $categories = $this->categories->get_categories();

            if($categories)
            {
                foreach($categories as $cat)
                {
                    $discussions = $this->categories->count_discussions($cat['category_id']);

                    $data['categories'][] = array(
                        'category_name' => anchor(site_url('categories/'.$cat['category_permalink'].''), ''.$cat['category_name'].'<span class="label label-default pull-right"> '.$discussions.' </span>'),
                    );
                }
            }

            // Create the data for the right sidebar.
            $right_sidebar_data = array(
                    'categories' => $data['categories'],
                    'member_count' => $this->users->count_members(),
            );

            // Parse the template & data.
            $data['right_sidebar'] = $this->parser->parse('sections/right_sidebar_template', $right_sidebar_data, $config);

        // Construct the footer.
            $footer_data = array(
                'text' => 'This is the footer',
            );

            $data['footer'] = $this->parser->parse('sections/footer_template', $footer_data, $config);

        // Aditional
            $data['breadcrumb'] = set_breadcrumb();

        // Build the final template.
            $this->parser->parse('default', $data);
    }

    public function process_tags($tags)
    {
        $tags = explode(",", $tags);

        foreach($tags as $tag)
        {
            $data['tags'][] = array(
                'tag' => anchor(site_url('search/'.$tag.''), '<em class="fa fa-tag"></em> '.$tag.'', 'class="label label-info"'),
            );
        }

        return $data['tags'];
    }
}

class Admin_Controller extends MY_Controller{

    public function __construct()
    {
        parent::__construct();

        // Set the admin theme.
        $this->admin_theme = $this->setings->get_setting('admin_theme');
    }
}

