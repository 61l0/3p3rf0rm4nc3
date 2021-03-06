<?php

class pengesahan_penetapaneselon1 extends CI_Controller {
	var $objectId = 'pengesahanpenetapaneselon1';
	
	function __construct()
	{
		parent::__construct();			
		
		//$this->output->enable_profiler(true);
		
		//$userdata = array ('logged_in' => TRUE);
				//
		//$this->session->set_userdata($userdata);
				
		//if ($this->session->userdata('logged_in') != TRUE) redirect('security/login');					
		$this->load->model('/security/sys_menu_model');
		$this->load->model('/penetapan/rseselon1_model');
		$this->load->model('/penetapan/penetapaneselon1_model');
		$this->load->model('/rujukan/kl_model');
		$this->load->model('/rujukan/eselon1_model');
		$this->load->model('/rujukan/programkl_model');
		$this->load->model('/pengaturan/sasaran_eselon1_model');
		$this->load->model('/penetapan/pengesahan_penetapaneselon1_model');
		$this->load->library("utility");
		
	}
	
	function index(){
		$data['title'] = 'Pengesahan Kinerja Eselon 1';	
		$data['objectId'] = $this->objectId;
		//$data['formLookupTarif'] = $this->tarif_model->lookup('#winLookTarif'.$data['objectId'],"#medrek_id".$data['objectId']);
	  	$this->load->view('penetapan/pengesahan_penetapaneselon1_v',$data);
	}
	
	function save(){
		$return_id = 0;
		$result = "";
		$data['pesan_error'] = '';
		$pesan = '';
		
		# data
		$data['tahun'] 			= $this->input->post('tahun'.$this->objectId);
		$data['kode_e1'] 		= $this->input->post('kode_e1');
		$data['detail'] 		= $this->input->post('detail');
		
		$data['id_masterpk_e1'] = $this->input->post('id_masterpk_e1');
		$data['kode_program'] 	= $this->input->post('kode_program');
		
		# proses pengesahan data pk
		$status='0';
		foreach($data['detail'] as $r){
			if(isset($r['approve'])){
				$status = '1';
			}else{
				$status = '0';
			}
			$result = $this->pengesahan_penetapaneselon1_model->UpdateDataPK($r['id_pk_e1'], $r['penetapan'], $status);
		}
		
		# proses insert atau update data master pk
		// insert
		$result = $this->pengesahan_penetapaneselon1_model->InsertDataMasterPK($data['tahun'], $data['kode_e1'], $data['kode_program']);
		
		// validation
		//$result = $this->pengesahan_penetapaneselon1_model->InsertOnDb($data);
		
		if ($result){
			echo json_encode(array('success'=>true, 'status'=>$return_id));
		} else {
			echo json_encode(array('msg'=>$data['pesan_error']));
		}
	}
	
	public function getDetail($tahun="", $kode_kl=""){
		echo $this->pengesahan_penetapaneselon1_model->getDetail($tahun, $kode_kl, $this->objectId);
	}
	
}
?>