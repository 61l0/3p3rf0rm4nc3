<?php

class Dsb_kl extends CI_Controller {
	var $dataPie = array();
	function __construct()
	{
		parent::__construct();			
		
					
		$this->load->model('/security/sys_menu_model');
		$this->load->model('/dashboard/dsb_kl_model');
		$this->load->library("utility");
	}
	
	function index()
	{
		
		$data = array(
				
					'title_page'=>'Biroren Kemenhub',
					'title'=>'Capaian Akhir IKU Kementerian',
					'objectId'=>'dashboardKinerjaKl',
					'sess_fullname'=>$this->session->userdata('full_name'),
					'sess_apptype'=>$this->session->userdata('app_type'),
					'js'=>array('js/easyui/jquery-1.6.min.js','js/easyui/jquery.easyui.min.js','js/uri_encode_decode.js','js/json2.js','js/jquery.autogrow.js','js/jquery.formatCurrency-1.4.0.min.js','js/formwizard.js','js/jquery.jqURL.js'),
					'css'=>array('css/themes/gray/easyui.css','css/themes/icon.css')
				);
		//$data['title'] =$this->session->userdata('userlogin');
	  
		//$data['menuList'] =  $this->sys_menu_model->prepareMenuManual();//($this->session->userdata('groupId'),'');
		
		$this->load->view('dashboard/dsb_kl_vw',$data);
		//$this->load->view('footer_vw',$data);
	}
	

	public function grid($filtahun=null){
		
		echo $this->dsb_kl_model->easyGrid($filtahun);
		 $this->dataPie = $this->dsb_kl_model->dataPie;
		 // $data = array("Tercapai"=>20,"Tidak Tercapai"=>3);
			//var_dump($this->dataPie );die;
	}
	
	
	function getDataPie($filtahun=null){
		//  $data = $this->dsb_kl_model->dataPie;// 
		  
		// $this->dsb_kl_model->easyGrid($filtahun);
		// $this->dataPie = $this->dsb_kl_model->dataPie;
		//  $data = array("Tercapai"=>20,"Tidak Tercapai"=>3);
		//	var_dump($this->dataPie);die;
			echo json_encode($this->dataPie);
	}
	
	function getLoginStatus(){
		echo $this->session->userdata('logged_in');
	}
	
}
?>
