<?
class Upload_model extends CI_Model {

	public function get($uploadId = null) {
		if ($uploadId === null) {
			$q = $this->db->get('upload');
		} elseif (is_array($uploadId)) {
			$q = $this->db->get_where('upload',$uploadId);

		} else {
			$q = $this->db->get_where('upload',['clubId' => $uploadId]);
		}
		
		return $q->result_array();
	}

	public function insert($data) {
		$this->db->insert('upload',$data);
		return $this->db->insert_id();
	}

	public function update($id,$data) {
		$this->db->where('clubId', $id);
		$this->db->update('upload', $data);
	}

	public function delete($id) {
		$this->db->where('clubId', $id);
   		$this->db->delete('upload'); 
	}
}