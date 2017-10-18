<?
class User_model extends CI_Model {

	public function get($userId = null) {
		if ($userId === null) {
			$q = $this->db->get('user');
		} elseif (is_array($userId)) {
			$q = $this->db->get_where('user',$userId);

		} else {
			$q = $this->db->get_where('user',['userId' => $userId]);
		}
		
		return $q->result_array();
	}

	public function insert($data) {
		$this->db->insert('user',$data);
		return $this->db->insert_id();
	}

	public function update($id,$data) {
		$this->db->where('userId', $id);
		$this->db->update('user', $data);
	}

	public function delete($id) {
		$this->db->where('userId', $id);
   		$this->db->delete('user'); 
	}
}