<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Middle_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_login(); // Middleware untuk mengecek login
    }

    protected function check_login() {
		if (!$this->session->userdata('user_id')) {
			redirect('signin');
			exit(); // Tambahkan exit di sini
		}
	}

	protected function check_role($required_role) {
		$user_role = $this->session->userdata('role');

		if (!$user_role || $user_role != $required_role) {
			redirect('signin/no_access');
			exit();
		}
	}

}

