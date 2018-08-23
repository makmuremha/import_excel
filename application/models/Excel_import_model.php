<?php
class Excel_import_model extends CI_Model
{
	function select()
	{
		$this->db->order_by('Id', 'DESC');
		$query = $this->db->get('tbl_shipping');
		return $query;
	}

	function insert($data)
	{
		$this->db->insert_batch('tbl_shipping', $data);
	}
}
