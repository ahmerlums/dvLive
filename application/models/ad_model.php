<?
class Ad_model extends CI_Model {

	public function get($adId = null) {
		if ($adId === null) {
			$q = $this->db->get('ad');
		} elseif (is_array($adId)) {
			$q = $this->db->get_where('ad',$adId);

		} else {
			$q = $this->db->get_where('ad',['adId' => $adId]);
		}
		
		return $q->result_array();
	}
	public function getRandomAd()
	{
	    $this->db->order_by('rand()');
	    $this->db->limit(1);
	    $query = $this->db->get('ad');
	    return $query->result_array();
	}
	public function insert($data) {
		$this->db->insert('ad',$data);
		return $this->db->insert_id();
	}

	public function update($id,$data) {
		$this->db->where('adId', $id);
		$this->db->update('ad', $data);
	}

	public function delete($id) {
		$this->db->where('adId', $id);
   		$this->db->delete('ad'); 
	}
}