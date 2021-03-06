<?php

class Masterpenetapaneselon2 extends CI_Controller {
	var $objectId = 'masterpenetapaneselon2';
	
	function __construct()
	{
		parent::__construct();			
		
		//$this->output->enable_profiler(true);
		
		//$userdata = array ('logged_in' => TRUE);
				//
		//$this->session->set_userdata($userdata);
				
		//if ($this->session->userdata('logged_in') != TRUE) redirect('security/login');					
		$this->load->model('/security/sys_menu_model');
		$this->load->model('/penetapan/master_penetapaneselon2_model');
		$this->load->model('/penetapan/penetapaneselon2_model');
		$this->load->model('/rujukan/eselon1_model');
		$this->load->model('/rujukan/eselon2_model');
		$this->load->model('/rujukan/kegiatankl_model');
		$this->load->library("utility");
		
	}
	
	function index(){
		$data['title'] = 'Program Penetapan Kinerja Eselon II';	
		$data['objectId'] = $this->objectId;
		//$data['formLookupTarif'] = $this->tarif_model->lookup('#winLookTarif'.$data['objectId'],"#medrek_id".$data['objectId']);
	  	$this->load->view('penetapan/master_penetapaneselon2s_v',$data);
	}
	
	public function add(){
		$data['title'] = 'Add Program Penetapan Kinerja Eselon II';	
		$data['objectId'] = $this->objectId;
		//$data['formLookupTarif'] = $this->tarif_model->lookup('#winLookTarif'.$data['objectId'],"#medrek_id".$data['objectId']);
	  	$this->load->view('penetapan/master_penetapaneselon2_v',$data);
	}
	
	function grid($filtahun=null){
		echo $this->master_penetapaneselon2_model->easyGrid($filtahun);
	}
	
	
	private function get_form_values() {
		$dt['tahun'] = $this->input->post("tahun", TRUE); 
		$dt['kode_e2'] = $this->input->post("kode_e2", TRUE); 
		$dt['kode_kegiatan'] = $this->input->post("kode_kegiatan", TRUE); 
		// print_r($dt);
		return $dt;
    }
	
	function save($aksi="", $kode=""){
		$this->load->library('form_validation');
		$data = $this->get_form_values();
		$return_id = 0;
		$result = "";
		$pesan = '';
		
		// validation
		# rules
		$this->form_validation->set_rules("tahun", 'Tahun', 'trim|required|numeric|exact_length[4]|xss_clean');
		$this->form_validation->set_rules("kode_e2", 'Unit Kerja', 'trim|required|xss_clean');
		$this->form_validation->set_rules("kode_kegiatan", 'Kegiatan Eselon II', 'trim|required|xss_clean');
		
		# message rules
		$this->form_validation->set_message('required', 'Field %s harus diisi.');
		$this->form_validation->set_message('numeric', 'Isi field %s dengan angka');
		$this->form_validation->set_message('exact_length', 'Isi field %s dengan 4 karakter angka');
		
		if ($this->form_validation->run() == FALSE){ // jika tidak valid
			$data['pesan_error'] = '';
			$data['pesan_error'].=(trim(form_error('tahun',' ',' '))==''?'':form_error('tahun',' ','<br>'));
			$data['pesan_error'].=(trim(form_error('kode_e2',' ',' '))==''?'':form_error('kode_kl',' ','<br>'));
			$data['pesan_error'].=(trim(form_error('kode_kegiatan',' ',' '))==''?'':form_error('kode_program',' ','<br>'));
			
		}else{
			if($aksi=="add"){ // add
				if($this->master_penetapaneselon2_model->dataCheck($data)){
					$data['pesan_error'] = 'Data Kegiatan Unit Ini Sudah Ditetapkan';
				}else{
					$result = $this->master_penetapaneselon2_model->InsertOnDb($data);
				}
			}else { // edit
				$result = $this->master_penetapaneselon2_model->UpdateOnDb($data, $kode);
			}
			// validasi detail
			//if($this->check_detail($data, $pesan)){
			/*}else{
				$data['pesan_error'].= $pesan;
			}*/
		}
		
		if ($result){
			echo json_encode(array('success'=>true, 'status'=>$return_id));
		} else {
			echo json_encode(array('msg'=>$data['pesan_error']));
		}
	}
	
	/*function check_detail($data, & $pesan){
		$i=1;
		foreach($data['detail'] as $r){
			if($r['penetapan'] == ''){ // nilai target null
				$pesan = 'Penetapan pada no. '.$i.' harus diisi.';
				return FALSE;
			}
			$i++;
		}
		
		return TRUE;
	}*/
	
	function delete($id=''){
		if($id != ''){
			$result = $this->master_penetapaneselon2_model->DeleteOnDb($id);
			if ($result){
				echo json_encode(array('success'=>true, 'haha'=>''));
			} else {
				echo json_encode(array('msg'=>'Some errors occured uy.', 'data'=> ''));
			}
		}
	}
	
	public function getDetail($tahun, $kode_kl, $kode_sasaran_kl){
		echo $this->master_penetapaneselon2_model->getDetail($tahun, $kode_kl, $kode_sasaran_kl);
	}
	
}
?>