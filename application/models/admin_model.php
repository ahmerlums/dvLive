<?
class Admin_model extends CI_Model {

	public function get($adminId = null) {
		if ($adminId === null) {
			$q = $this->db->get('admin');
		} elseif (is_array($adminId)) {
			$q = $this->db->get_where('admin',$adminId);

		} else {
			$q = $this->db->get_where('admin',['adminId' => $adminId]);
		}
		
		return $q->result_array();
	}

	public function insert($data) {
		$this->db->insert('admin',$data);
		return $this->db->insert_id();
	}

	public function update() {

	}

	public function delete() {
		
	}
}