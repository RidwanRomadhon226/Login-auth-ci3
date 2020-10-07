<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
  public function index()
  {
    $this->load->view('templates/auth/auth_header');
    $this->load->view('auth/login');
    $this->load->view('templates/auth/auth_footer');
  }

  public function register()
  {
    $this->load->view('templates/auth/auth_header');
    $this->load->view('auth/register');
    $this->load->view('templates/auth/auth_footer');
  }
}
