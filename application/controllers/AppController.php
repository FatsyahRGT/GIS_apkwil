<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AppController extends CI_Controller {

	public function dashboard()
	{
		$this->load->view('app/header');
		$this->load->view('app/sidebar');
		$this->load->view('dashboard');
		$this->load->view('app/footer');
	}
}
