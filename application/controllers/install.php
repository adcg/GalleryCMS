<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * 
 */
class Install extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    if ($this->db->table_exists('user'))
    {
      redirect('auth');
      exit();
    }
  }
  
  public function index()
  {
    $data['email'] = '';
    
    if ($this->is_method_post() == TRUE)
    {
      // Validate form.
      $this->load->library('form_validation');
      $this->form_validation->set_error_delimiters('<div class="alert alert-error"><strong>Error: </strong>', '</div>');
      $this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email|xss_clean');
      $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|matches[password_conf]|sha1');
      if ($this->form_validation->run() == TRUE)
      {
        // TODO Perform check for GD2, etc.
        if ($this->create_tables() == TRUE)
        {
          redirect('album');
        }
        else
        {
          $data['install_error'] = 'An error occurred during installation';
        }
      }
      else
      {
        $data['email'] = $this->input->post('email_address');
      }
    }
    
    $this->load->helper('form');
    $this->load->view('install/index', $data);
  }
  
  protected function create_tables()
  {
    $this->load->dbforge();
    // user table
    $user = array(
        'id'             => array(
            'type'            => 'INT',
            'auto_increment'  => TRUE,
            'unsigned'        => TRUE,
        ),
        'uuid'           => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45
        ),
        'email_address'  => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'password'       => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'is_active'      => array(
            'type'            => 'TINYINT',
            'constraint'      => 1,
            'default'         => 0
        ),
        'is_admin'       => array(
            'type'            => 'TINYINT',
            'constraint'      => 1,
            'default'         => 0
        ),
        'created_at'     => array(
            'type'            => 'DATETIME',
            'null'            => TRUE
        ),
        'updated_at'     => array(
            'type'            => 'DATETIME',
            'null'            => TRUE
        ),
        'last_logged_in' => array(
            'type'            => 'DATETIME',
            'null'            => TRUE
        ),
        'last_ip'        => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45
        )
    );
    
    $this->dbforge->add_field($user);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('user', TRUE);
    
    // album table
    $album = array(
        'id'             => array(
            'type'            => 'INT',
            'auto_increment'  => TRUE,
            'unsigned'        => TRUE,
        ),
        'uuid'           => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'name'           => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45
        ),
        'published'      => array(
            'type'            => 'TINYINT',
            'constraint'      => 1,
            'default'         => 1
        ),
        'created_by'     => array(
            'type'            => 'INT',
            'unsigned'        => TRUE,
        ),
        'updated_by'     => array(
            'type'            => 'INT',
            'unsigned'        => TRUE,
        ),
        'created_at'     => array(
            'type'            => 'DATETIME'
        ),
        'updated_at'     => array(
            'type'            => 'DATETIME'
        )
    );
    
    $this->dbforge->add_field($album);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('album', TRUE);
    
    // album_config table
    $album_config = array(
        'id'             => array(
            'type'            => 'INT',
            'auto_increment'  => TRUE,
            'unsigned'        => TRUE,
        ),
        'album_id'       => array(
            'type'            => 'INT',
            'null'            => TRUE,
            'unsigned'        => TRUE,
        ),
        'thumb_width'    => array(
            'type'            => 'INT',
            'default'         => 100
        ),
        'thumb_height'   => array(
            'type'            => 'INT',
            'default'         => 100
        ),
        'crop_thumbnails' => array(
            'type'            => 'TINYINT',
            'constraint'      => 1,
            'default'         => 0
        ),
        'auto_publish'   => array(
            'type'            => 'TINYINT',
            'constraint'      => 1,
            'default'         => 1
        )
    );
    
    $this->dbforge->add_field($album_config);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('album_config', TRUE);
    
    // image table
    $image = array(
        'id'             => array(
            'type'            => 'INT',
            'auto_increment'  => TRUE,
            'unsigned'        => TRUE,
        ),
        'album_id'       => array(
            'type'            => 'INT',
            'null'            => TRUE,
            'unsigned'        => TRUE,
        ),
        'uuid'           => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'name'           => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'order_num'      => array(
            'type'            => 'INT',
            'null'            => TRUE
        ),
        'caption'        => array(
            'type'            => 'TEXT',
            'null'            => TRUE
        ),
        'raw_name'       => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'file_type'      => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'file_name'      => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'file_ext'       => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'file_size'      => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'path'           => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'full_path'      => array(
            'type'            => 'VARCHAR',
            'constraint'      => 255,
            'null'            => TRUE
        ),
        'published'      => array(
            'type'            => 'TINYINT',
            'constraint'      => 1,
            'null'            => FALSE
        ),
        'created_at'     => array(
            'type'            => 'DATETIME',
            'null'            => TRUE
        ),
        'updated_at'     => array(
            'type'            => 'DATETIME',
            'null'            => TRUE
        ),
        'created_by'     => array(
            'type'            => 'INT',
            'null'            => TRUE,
            'unsigned'        => TRUE,
        ),
        'updated_by'     => array(
            'type'            => 'INT',
            'null'            => TRUE,
            'unsigned'        => TRUE,
        )
    );
    
    $this->dbforge->add_field($image);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('image', TRUE);
    
    // image_comment table
    $image_comment = array(
        'id'             => array(
            'type'            => 'INT',
            'auto_increment'  => TRUE,
            'unsigned'        => TRUE,
        ),
        'image_id'       => array(
            'type'            => 'INT',
            'null'             => TRUE,
            'unsigned'        => TRUE,
        ),
        'published'      => array(
            'type'            => 'TINYINT',
            'constraint'      => 1,
            'default'         => 0
        ),
        'comment'        => array(
            'type'            => 'TEXT',
            'null'            => TRUE
        )
    );
    
    $this->dbforge->add_field($image_comment);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('image_comment', TRUE);
    
    // ticket table
    $ticket = array(
        'id'             => array(
            'type'            => 'INT',
            'auto_increment'  => TRUE,
            'unsigned'        => TRUE
        ),
        'user_id'        => array(
            'type'            => 'INT',
            'null'            => TRUE,
            'unsigned'        => TRUE
        ),
        'uuid'           => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        )
    );
    
    $this->dbforge->add_field($ticket);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('ticket', TRUE);
    
    // feed table
    $feed = array(
        'id'             => array(
            'type'            => 'INT',
            'auto_increment'  => TRUE,
            'unsigned'        => TRUE
        ),
        'name'           => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'created_by'     => array(
            'type'            => 'INT',
            'null'            => TRUE,
            'unsigned'        => TRUE,
        ),
        'uuid'           => array(
            'type'            => 'VARCHAR',
            'constraint'      => 45,
            'null'            => TRUE
        ),
        'created_at'     => array(
            'type'            => 'DATETIME',
            'null'            => TRUE
        )
    );
    
    $this->dbforge->add_field($feed);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('feed', TRUE);
    
    // feed_album table
    $feed_album = array(
        'id'             => array(
            'type'            => 'INT',
            'auto_increment'  => TRUE,
            'unsigned'        => TRUE
        ),
        'feed_id'        => array(
            'type'            => 'INT',
            'null'            => TRUE,
            'unsigned'        => TRUE
        ),
        'album_id'       => array(
            'type'            => 'INT',
            'null'            => TRUE,
            'unsigned'        => TRUE,
        ),
        'order_num'      => array(
            'type'            => 'INT',
            'null'            => TRUE
        )
    );
    
    $this->dbforge->add_field($feed_album);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('feed_album', TRUE);
    
    // chmod 'uploads' dir
    chmod('./uploads', 0775);
    
    // Success, create user & redirect
    $this->load->model('user_model');
    $now = date('Y-m-d H:i:s');
    $user_data = array(
                  'email_address'   => $this->input->post('email_address'), 
                  'password'        => $this->input->post('password'),
                  'is_active'       => 1,
                  'is_admin'        => 1,
                  'created_at'      => $now,
                  'uuid'            => $this->create_uuid(),
                  'updated_at'      => $now);
    $user_id = $this->user_model->create($user_data);
    // Fetch user record
    $user = $this->user_model->find_by_id($user_id);
    // Sign in
    $this->create_login_session($user);
    
    $this->session->set_flashdata('flash_message', "User successfully created. You are now logged in");
     
   return TRUE;
  }
  
  /**
   *
   * @param type $user 
   */
  protected function create_login_session($user)
  {
    $session_data = array(
        'email_address'  => $user->email_address,
        'user_id'        => $user->id,
        'logged_in'      => TRUE,
        'is_admin'       => $user->is_admin,
        'ip'             => $this->input->ip_address()
    );
    $this->session->set_userdata($session_data);
  }
  
}
