<?
class Club_model extends CI_Model {

	public function get($clubId = null) {
		if ($clubId === null) {
			$q = $this->db->get('club');
		} elseif (is_array($clubId)) {
			$q = $this->db->get_where('club',$clubId);

		} else {
			$q = $this->db->get_where('club',['clubId' => $clubId]);
		}
		
		return $q->result_array();
	}

	public function insert($data) {
		$this->db->insert('club',$data);
		return $this->db->insert_id();
	}

	public function update($id,$data) {
		$this->db->where('clubId', $id);
		$this->db->update('club', $data);
	}

	public function delete($id) {
		$this->db->where('clubId', $id);
   		$this->db->delete('club'); 
	}
}