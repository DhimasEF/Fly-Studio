<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment extends Middle_Controller {

    public function __construct() {
        parent::__construct();
		$this->check_role('creator'); // Middleware untuk cek role
        $this->load->model('M_User');
        $this->load->model('M_Comment');
        $this->load->library('form_validation');
		$this->load->library('session');
		
		// Pengecekan apakah pengguna sudah login atau belum
        if (!$this->session->userdata('logged_in')) {
            // Jika belum login, set flash data dan arahkan ke halaman login
            $this->session->set_flashdata('error', 'You must be logged in');
            redirect('signin');
        }
    }

    // Add a new comment
    public function add_comment() {
        $this->form_validation->set_rules('id_content', 'Content ID', 'required');
        $this->form_validation->set_rules('id_user', 'User ID', 'required');
        $this->form_validation->set_rules('comment_text', 'Comment Text', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
        } else {
            $data = [
                'id_content' => $this->input->post('id_content'),
                'id_user' => $this->input->post('id_user'),
                'id_parent' => $this->input->post('id_parent') ?: NULL, // Optional parent ID
                'comment_text' => $this->input->post('comment_text'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $result = $this->M_Comment->add_comment($data);
            echo json_encode(['status' => $result, 'message' => $result ? 'Comment added successfully' : 'Failed to add comment']);
        }
    }

    // Get all comments for a specific content
    public function get_comments($id_content) {
        $comments = $this->M_Comment->get_comments_by_content($id_content);
        echo json_encode($comments);
    }

    // Delete a comment and its replies
    public function delete_comment($id_comment) {
        $result = $this->M_Comment->delete_comment($id_comment);
        echo json_encode(['status' => $result, 'message' => $result ? 'Comment deleted successfully' : 'Failed to delete comment']);
    }
}
