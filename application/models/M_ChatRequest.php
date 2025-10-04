<?php
class M_ChatRequest extends CI_Model {

    // Tambahkan permintaan chat
    public function add_request($data) {
        return $this->db->insert('chat_requests', $data);
    }

    // Ambil permintaan chat berdasarkan status
    public function get_requests_by_status($status) {
        $this->db->where('status', $status);
        return $this->db->get('chat_requests')->result();
    }

    // Update status permintaan chat
    public function update_request_status($id_chat_request, $status) {
        $this->db->where('id_chat_request', $id_chat_request);
        return $this->db->update('chat_requests', ['status' => $status]);
    }
}
?>
