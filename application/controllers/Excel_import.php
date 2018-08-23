<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excel_import extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('excel_import_model');
		$this->load->library('excel');
	}

	function index()
	{
		$this->load->view('excel_import');
	}
	
	function fetch()
	{
		$data = $this->excel_import_model->select();
		$output = '
		<h3 align="center">Total Data - '.$data->num_rows().'</h3>
		<table class="table table-striped table-bordered">
			<tr>
				<th>Customer Name</th>
				<th>Address</th>
				<th>City</th>
				<th>Postal Code</th>
				<th>Country</th>
			</tr>
		';
		foreach($data->result() as $row)
		{
			$output .= '
			<tr>
				<td>'.$row->Shipping_Date.'</td>
				<td>'.$row->Part_Packing_List.'</td>
				<td>'.$row->Shipping_Document.'</td>
				<td>'.$row->Part_Allocation.'</td>
				<td>'.$row->Part_Supply_Request_Date.'</td>
			</tr>
			';
		}
		$output .= '</table>';
		echo $output;
	}

	function import()
	{
		
		if(isset($_FILES["file"]["tmp_name"]))
		{
			$path = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach($object->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				for($row=2; $row<=$highestRow; $row++)
				{
					$customer_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$address = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$city = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$postal_code = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$country = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					
					$data[] = array(
						'Shipping_Date'				=> date('Y-m-d H:i:s',strtotime($customer_name)),
						'Part_Packing_List'			=> $address,
						'Shipping_Document'			=> $city,
						'Part_Allocation'			=> $postal_code,
						'Part_Supply_Request_Date'	=> date('Y-m-d H:i:s',strtotime($country))
					);
				}
			}
			
			$this->excel_import_model->insert($data);
			echo 'Data Imported successfully';
		}else{
			echo 'File Name Tidak Ditemukan'.$path;
		}
	}
}

?>