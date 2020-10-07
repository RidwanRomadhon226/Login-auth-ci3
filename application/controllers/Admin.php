<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
  public function index()
  {
    $data = [
      'title' => 'Home Admin',
      'user' => $this->db->get_where('user', ['email' =>
      $this->session->userdata('email')])->row_array()
    ];

    // $data['user'] = ;
    // echo 'Selemat Datang User ' . $data['user']['name'];

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('templates/topbar');
    $this->load->view('admin/index', $data);
    $this->load->view('templates/footer');
  }
}
