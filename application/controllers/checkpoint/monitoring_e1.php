<?php

class Monitoring_e1 extends CI_Controller {

	function __construct()
	{
		parent::__construct();			
		
	//	$userdata = array ('userLogin' => $userLogin,'logged_in' => TRUE,'groupId'=>$this->sys_login_model->groupId,'fullName'=>$this->sys_login_model->fullName,'userId'=>$this->sys_login_model->userId,'groupLevel'=>$this->sys_login_model->level);
		$userdata = array ('logged_in' => TRUE);
				//
		//$this->session->set_userdata($userdata);
				
	//if ($this->session->userdata('logged_in') != TRUE) redirect('security/login');					
		$this->load->model('/security/sys_menu_model');
		$this->load->model('/checkpoint/monitoring_e1_model');
		$this->load->library("utility");
	}
	
	function index()
	{
		
		$data = array(
				
					'title_page'=>'Biroren Kemenhub',
					'title'=>'Capaian Akhir IKU Eselon I',
					'objectId'=>'monitoringE1',
					'sess_fullname'=>$this->session->userdata('full_name'),
					'sess_apptype'=>$this->session->userdata('app_type'),
					'js'=>array('js/easyui/jquery-1.6.min.js','js/easyui/jquery.easyui.min.js','js/uri_encode_decode.js','js/json2.js','js/jquery.autogrow.js','js/jquery.formatCurrency-1.4.0.min.js','js/formwizard.js','js/jquery.jqURL.js'),
					'css'=>array('css/themes/gray/easyui.css','css/themes/icon.css')
				);
		//$data['title'] =$this->session->userdata('userlogin');
	  
		//$data['menuList'] =  $this->sys_menu_model->prepareMenuManual();//($this->session->userdata('groupId'),'');
		
		$this->load->view('checkpoint/monitoring_e1_vw',$data);
		//$this->load->view('footer_vw',$data);
	}
	

	public function grid($filtahun=null,$filperiode=null){
		
		echo $this->monitoring_e1_model->easyGrid($filtahun,$filperiode);
	}
	
	
	function getDataPie($filtahun=null){
		   $data = $this->monitoring_e1_model->dataPie;//array("Tercapai"=>20,"Tidak Tercapai"=>3);
			echo json_encode($data);
	}
	
	function getLoginStatus(){
		echo $this->session->userdata('logged_in');
	}
	
}
?>
