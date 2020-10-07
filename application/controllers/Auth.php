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
    $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
    $this->form_validation->set_rules('password', 'Password', 'required|trim');

    if ($this->form_validation->run() == false) {
      $data['title'] = 'Ridwan Login';
      $this->load->view('templates/auth/auth_header', $data);
      $this->load->view('auth/login');
      $this->load->view('templates/auth/auth_footer');
    } else {
      $this->_login();
    }
  }

  private function _login()
  {
    $email = $this->input->post('email', true);
    $password = $this->input->post('password', true);

    $user = $this->db->get_where('user', ['email' => $email])->row_array();

    if ($user) {
      // Jika User nya ada
      if ($user['is_active'] == 1) {
        // Cek Password
        if (password_verify($password, $user['password'])) {
          # code...
          $data = [
            'email' => $user['email'],
            'role_id' => $user['role_id']
          ];
          $this->session->set_userdata($data);
          redirect('user');
        } else {
          $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
          Password Anda Salah </div>');
          redirect('auth');
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
        User Tidak Aktivasi </div>');
        redirect('auth');
      }
    } else {
      // Jika User nya tidak ada

      $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
      User Tidak tersedia </div>');
      redirect('auth');
    }
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
        'name' => htmlspecialchars($this->input->post('name', true)),
        'email' => htmlspecialchars($this->input->post('email', true)),
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

  public function logout()
  {
    $this->session->unset_userdata('email');
    $this->session->unset_userdata('role_id');
    $this->session->set_flashdata('message', '<div class="alert alert-info" role="alert">
    Berhasil Logout </div>');
    redirect('auth');
  }
}
