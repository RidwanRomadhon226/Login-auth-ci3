<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data = [
      'title' => 'Ridwan Login'
    ];
    $this->load->view('templates/auth/auth_header', $data);
    $this->load->view('auth/login');
    $this->load->view('templates/auth/auth_footer');
  }

  public function register()
  {
    $this->form_validation->set_rules('name', 'Name', 'required|trim');
    $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]');
    $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[8]|matches[password2]', [
      'matches' => 'password dont match',
      'min_length' => 'Password too short'
    ]);
    $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

    if ($this->form_validation->run() == false) {
      $data['title'] = 'Ridwan Register';
      $this->load->view('templates/auth/auth_header', $data);
      $this->load->view('auth/register');
      $this->load->view('templates/auth/auth_footer');
    } else {

      $data = [
        'name' => $this->input->post('name'),
        'email' => $this->input->post('email'),
        'image' => 'default.png',
        'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
        'role_id' => 2,
        'is_active' => 1,
        'created_at' => time(),
      ];

      $this->db->insert('user', $data);
      $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">
       Data Created Plise Login <a href="#" class="alert-link">an example link</a>
    </div>');
      redirect('auth');
      // echo 'date berhasil di tambah';
    }
  }
}
