<?php
class M_EventFile extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    // Fungsi untuk menambah file ke dalam database
    public function add_file($data) {
        return $this->db->insert('event_files', $data);
    }

    // Fungsi untuk mengambil file berdasarkan id_event
    public function get_files_by_event($id_event) {
        $this->db->where('id_event', $id_event);
        return $this->db->get('event_files')->result();
    }
}
?>
