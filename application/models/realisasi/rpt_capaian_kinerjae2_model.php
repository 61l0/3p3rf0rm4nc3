<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *INVISI
*/

class Rpt_capaian_kinerjae2_model extends CI_Model
{	
	/**
	* constructor
	*/
	public function __construct()
    {
        parent::__construct();
		//$this->CI =& get_instance();
    }
	// purpose : 1=buat grid, 2=buat pdf, 3=buat excel
	//http://192.168.0.100/e-performance/realisasi/rpt_capaian_kinerjae2/grid/2012/-1/-1/022.02/022.02.04/1/12
	public function easyGrid($filtahun=null,$filsasaran=null,$filiku=null,$file1=null,$file2=null,$filstart=null,$filend=null,$purpose=1,$pageNumber=null,$pageSize=null){
		$lastNo = isset($_POST['lastNo']) ? intval($_POST['lastNo']) : 0;  
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;  
		$limit = isset($_POST['rows']) ? intval($_POST['rows']) : 10;  
			
		//jika utk pdf & excel maka paging ambil dari parameter
		if (($purpose==2)||($purpose==3)){
			$page = isset($pageNumber) ? intval($pageNumber) : 1;  
			$limit = isset($pageSize) ? intval($pageSize) : 10;  
			//var_dump($pageNumber);
		//	var_dump($pageSize);
		}
		$count = $this->GetRecordCount($filtahun,$filsasaran,$filiku,$file1,$file2,$filstart,$filend);
		$response = new stdClass();
		$response->total = $count;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'iku.kode_ikk';  
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';  
		$offset = ($page-1)*$limit;  
		$pdfdata = array();
		if ($count>0){
			 if($filtahun != '' && $filtahun != '-1' && $filtahun != null) {
				$this->db->where("iku.tahun",$filtahun);
			} 
			
			if($file1 != '' && $file1 != '-1' && $file1 != null) {
					$this->db->where("e2.kode_e1",$file1);
			}
			
			if($file2 != '' && $file2 != '-1' && $file2 != null) {
					$this->db->where("iku.kode_e2",$file2);
			}
			
			
		/* 	if($filsasaran != '' && $filsasaran != '-1' && $filsasaran != null) {
					$this->db->where("rkt.kode_sasaran_e2",$filsasaran);
			} */
			if($filiku != '' && $filiku != '-1' && $filiku != null) {
					$this->db->where("iku.kode_ikk",$filiku);
			}
			
			//$this->db->order_by($sort." ".$order );
			$this->db->order_by("iku.kode_ikk");
			//$this->db->order_by("iku.deskripsi");
			//hanya utk grid saja
			if ($purpose==1) $this->db->limit($limit,$offset);
			$this->db->distinct();
		//	$this->db->select("iku.deskripsi as indikator_kinerja,iku.satuan,pk.target, rkt.realisasi,case  when rkt.triwulan  =1 then rkt.realisasi else 0 end  as triwulan1,case  when rkt.triwulan  =2 then rkt.realisasi else 0 end  as triwulan2,case  when rkt.triwulan  =3 then rkt.realisasi else 0 end  as triwulan3,case  when rkt.triwulan  =4 then rkt.realisasi else 0 end  as triwulan4",false);
			$this->db->select("iku.deskripsi as indikator_kinerja,iku.satuan,iku.kode_ikk",false);
			
			$this->db->from('tbl_ikk iku inner join tbl_eselon2 e2 on e2.kode_e2=iku.kode_e2 ',false);
			$query = $this->db->get();
			
			$i=0;
			
				$no = ($page-1)*$limit;//$lastNo;
			$noIndikator =0;
			$jumlah =0;
			foreach ($query->result() as $row){
				$no++;			
				$response->rows[$i]['no']= $no;								
				$response->rows[$i]['indikator_kinerja']=$row->indikator_kinerja;				
				$response->rows[$i]['satuan']=$row->satuan;				
				$response->rows[$i]['persen']='';	
				$row->target = $this->getTarget($filtahun,$row->kode_ikk);
				$response->rows[$i]['target']= $this->utility->cekNumericFmt($row->target);	
				/* $row->bulan1 = $this->getRealisasi($filtahun,$row->kode_ikk,"1");
				$row->bulan2 = $this->getRealisasi($filtahun,$row->kode_ikk,"2");
				$row->bulan3 = $this->getRealisasi($filtahun,$row->kode_ikk,"3");
				$row->bulan4 = $this->getRealisasi($filtahun,$row->kode_ikk,"4"); */
				$row->bulan1 = 0;$row->bulan2 = 0;$row->bulan3 = 0;$row->bulan4 = 0;
				$row->bulan5 = 0;$row->bulan6 = 0;$row->bulan7 = 0;$row->bulan8 = 0;
				$row->bulan9 = 0;$row->bulan10 = 0;$row->bulan11 = 0;$row->bulan12 = 0;
				$response->rows[$i]['bulan1'] = "";
				$response->rows[$i]['bulan2'] = "";
				$response->rows[$i]['bulan3'] = "";
				$response->rows[$i]['bulan4'] = "";
				$response->rows[$i]['bulan5'] = "";
				$response->rows[$i]['bulan6'] = "";
				$response->rows[$i]['bulan7'] = "";
				$response->rows[$i]['bulan8'] = "";
				$response->rows[$i]['bulan9'] = "";
				$response->rows[$i]['bulan10'] = "";
				$response->rows[$i]['bulan11'] = "";
				$response->rows[$i]['bulan12'] = "";
				//var_dump($filstart);
				if ((1>=intval($filstart))&&(1<=intval($filend))){
					$row->bulan1 = $this->getRealisasi($filtahun,$row->kode_ikk,"1");
					$response->rows[$i]['bulan1']= $this->utility->cekNumericFmt($row->bulan1); 
				}
				if ((2>=intval($filstart))&&(2<=intval($filend))){
					$row->bulan2 = $this->getRealisasi($filtahun,$row->kode_ikk,"2");
					$response->rows[$i]['bulan2']=$this->utility->cekNumericFmt($row->bulan2); 
				}
				if ((3>=intval($filstart))&&(3<=intval($filend))){
					$row->bulan3 = $this->getRealisasi($filtahun,$row->kode_ikk,"3");
					$response->rows[$i]['bulan3']=$this->utility->cekNumericFmt($row->bulan3);
				}
				if ((4>=intval($filstart))&&(4<=intval($filend))){
					$row->bulan4 = $this->getRealisasi($filtahun,$row->kode_ikk,"4");
					$response->rows[$i]['bulan4']=$this->utility->cekNumericFmt($row->bulan4);
				}
				
				if ((5>=intval($filstart))&&(5<=intval($filend))){
					$row->bulan5 = $this->getRealisasi($filtahun,$row->kode_ikk,"5");
					$response->rows[$i]['bulan5']= $this->utility->cekNumericFmt($row->bulan5);
				}
				if ((6>=intval($filstart))&&(6<=intval($filend))){
					$row->bulan6 = $this->getRealisasi($filtahun,$row->kode_ikk,"6");
					$response->rows[$i]['bulan6']= $this->utility->cekNumericFmt($row->bulan6);
				}
				if ((7>=intval($filstart))&&(7<=intval($filend))){
					$row->bulan7 = $this->getRealisasi($filtahun,$row->kode_ikk,"7");
					$response->rows[$i]['bulan7']= $this->utility->cekNumericFmt($row->bulan7);
				}
				if ((8>=intval($filstart))&&(8<=intval($filend))){
					$row->bulan8 = $this->getRealisasi($filtahun,$row->kode_ikk,"8");
					$response->rows[$i]['bulan8']= $this->utility->cekNumericFmt($row->bulan8);
				}
				
				if ((9>=intval($filstart))&&(9<=intval($filend))){
					$row->bulan9 = $this->getRealisasi($filtahun,$row->kode_ikk,"9");
					$response->rows[$i]['bulan9']= $this->utility->cekNumericFmt($row->bulan9);
				}
				if ((10>=intval($filstart))&&(10<=intval($filend))){
					$row->bulan10 = $this->getRealisasi($filtahun,$row->kode_ikk,"10");
					$response->rows[$i]['bulan10']= $this->utility->cekNumericFmt($row->bulan10);
				}
				if ((11>=intval($filstart))&&(11<=intval($filend))){
					$row->bulan11 = $this->getRealisasi($filtahun,$row->kode_ikk,"11");
					$response->rows[$i]['bulan11']= $this->utility->cekNumericFmt($row->bulan11);
				}
				if ((12>=intval($filstart))&&(12<=intval($filend))){
					$row->bulan12 = $this->getRealisasi($filtahun,$row->kode_ikk,"12");
					$response->rows[$i]['bulan12']= $this->utility->cekNumericFmt($row->bulan12);
				}


				// TS
				

				//$row->realisasi = $row->triwulan1+$row->triwulan2+$row->triwulan3+$row->triwulan4;//$this->getRealisasi($filtahun,$row->kode_ikk,"-1");
				
				/* $response->rows[$i]['bulan1']= $this->utility->cekNumericFmt($row->bulan1); 
				$response->rows[$i]['bulan2']=$this->utility->cekNumericFmt($row->bulan2); 
				$response->rows[$i]['bulan3']=$this->utility->cekNumericFmt($row->bulan3); 
				$response->rows[$i]['bulan4']=$this->utility->cekNumericFmt($row->bulan4);  */
				if ($row->bulan12 !=0) {
					$row->realisasi = $row->bulan12;
				} else if ($row->bulan11 !=0) {
					$row->realisasi = $row->bulan11;
				} else if ($row->bulan10 !=0) {
					$row->realisasi = $row->bulan10;
				} else if ($row->bulan9 !=0) {
					$row->realisasi = $row->bulan9;
				} else if ($row->bulan8 !=0) {
					$row->realisasi = $row->bulan8;
				} else if ($row->bulan7 !=0) {
					$row->realisasi = $row->bulan7;
				} else if ($row->bulan6 !=0) {
					$row->realisasi = $row->bulan6;
				} else if ($row->bulan5 !=0) {
					$row->realisasi = $row->bulan5;
				} else if ($row->bulan4 !=0) {
					$row->realisasi = $row->bulan4;
				} else if ($row->bulan3 !=0) {
					$row->realisasi = $row->bulan3;
				} else if ($row->bulan2 !=0) {
					$row->realisasi = $row->bulan2;
				} else {
					$row->realisasi = $row->bulan1;
				}
				$response->rows[$i]['realisasi']=$this->utility->cekNumericFmt($row->realisasi);
				//s	var_dump($row->target." ".$row->kode_ikk);
				if ((is_numeric($row->target))&&(is_numeric($row->realisasi))){
					$response->rows[$i]['persen']=  ($row->target!=0?$this->utility->cekNumericFmt($row->realisasi/$row->target*100)." %":"-");
				}	
				//utk kepentingan export excel ==========================
				$row->target = $response->rows[$i]['target'];
				$row->bulan1=$response->rows[$i]['bulan1'];
				$row->bulan2=$response->rows[$i]['bulan2'];
				$row->bulan3=$response->rows[$i]['bulan3'];
				$row->bulan4=$response->rows[$i]['bulan4'];
				$row->bulan5=$response->rows[$i]['bulan5'];
				$row->bulan6=$response->rows[$i]['bulan6'];
				$row->bulan7=$response->rows[$i]['bulan7'];
				$row->bulan8=$response->rows[$i]['bulan8'];
				$row->bulan9=$response->rows[$i]['bulan9'];
				$row->bulan10=$response->rows[$i]['bulan10'];
				$row->bulan11=$response->rows[$i]['bulan11'];
				$row->bulan12=$response->rows[$i]['bulan12'];
				$row->realiasi=$response->rows[$i]['realisasi'];
				$row->persen=$response->rows[$i]['persen'];
				unset($row->kode_ikk);
				unset($row->realisasi);
			//============================================================
			//utk kepentingan export pdf===================
				$pdfdata[] = array($no,$response->rows[$i]['indikator_kinerja'],$response->rows[$i]['satuan'],$response->rows[$i]['target'],$response->rows[$i]['bulan1'],$response->rows[$i]['bulan2'],$response->rows[$i]['bulan3'],$response->rows[$i]['bulan4'],$response->rows[$i]['bulan5'],$response->rows[$i]['bulan6'],$response->rows[$i]['bulan7'],$response->rows[$i]['bulan8'],$response->rows[$i]['bulan9'],$response->rows[$i]['bulan10'],$response->rows[$i]['bulan11'],$response->rows[$i]['bulan12'],$response->rows[$i]['persen']);
			//============================================================
			
				$i++;
			} 
			$response->lastNo = $no;
		//	$query->free_result();
		}else {
				
				$response->rows[$count]['no']= "";
				$response->rows[$count]['target']= "";
				$response->rows[$count]['realisasi']='';
				$response->rows[$count]['indikator_kinerja']='';
				$response->rows[$count]['persen']='';
				$response->rows[$count]['bulan1']='';
				$response->rows[$count]['bulan2']='';
				$response->rows[$count]['bulan3']='';
				$response->rows[$count]['bulan4']='';
				$response->rows[$count]['bulan5'] = "";
				$response->rows[$count]['bulan6'] = "";
				$response->rows[$count]['bulan7'] = "";
				$response->rows[$count]['bulan8'] = "";
				$response->rows[$count]['bulan9'] = "";
				$response->rows[$count]['bulan10'] = "";
				$response->rows[$count]['bulan11'] = "";
				$response->rows[$count]['bulan12'] = "";
				$response->lastNo = 0;
		}
		
		/*$response->footer[0]['sasaran_strategis']='<b>Jumlah </b>';
		$response->footer[0]['indikator_kinerja']='<b>'.number_format($jumlah).' Indikator Kinerja</b>';
		$response->footer[0]['no']='';
		$response->footer[0]['pejabat']='';		
		$response->footer[0]['no_indikator']='';
		*/
		if ($purpose==1) //grid normal
			return json_encode($response);
		else if($purpose==2){//pdf
			return $pdfdata;
		}
		else if($purpose==3){//to excel
			//tambahkan header kolom
			$colHeaders = array("Indikator Kinerja Kegiatan","Satuan","Target","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktorber","November","Desember","Realisasi Persen");		
			to_excel($query,"CapaianKinerjaE2",$colHeaders);
		}
		
	}
	
	public function GetRecordCount($filtahun=null,$filsasaran=null,$filiku=null,$file1=null,$file2=null,$filstart=null,$filend=null){
		$where = '';
	 	if($filtahun != '' && $filtahun != '-1' && $filtahun != null) {
			$where.= " and iku.tahun='$filtahun'";
		}	 
		if($file1 != '' && $file1 != '-1' && $file1 != null) {
			$where.=" and e2.kode_e1='$file1'";
		}
		
		if($file2 != '' && $file2 != '-1' && $file2 != null) {
			$where .=" and iku.kode_e2 ='$file2'";
		}	
		/* if($filsasaran != '' && $filsasaran != '-1' && $filsasaran != null) {
			$where.=" and rkt.kode_sasaran_e2='$filsasaran'";
		} */
		if($filiku != '' && $filiku != '-1' && $filiku != null) {
				$where.=" and iku.kode_ikk='$filiku'";
		}	
		if ($where!="")
			$where = " where ".substr($where,5,strlen($where));
	//	$this->db->from('tbl_kinerja_eselon2 rkt inner join tbl_ikk iku on iku.kode_ikk = rkt.kode_ikk inner join tbl_sasaran_eselon2 sasaran on sasaran.kode_sasaran_e2 = rkt.kode_sasaran_e2 inner join tbl_pk_eselon2 pk on pk.kode_sasaran_e2 = rkt.kode_sasaran_e2',false);
		//$this->db->from('select sasaran.deskripsi from tbl_kinerja_eselon2 rkt inner join tbl_ikk iku on iku.kode_ikk = rkt.kode_ikk inner join tbl_sasaran_eselon2 sasaran on sasaran.kode_sasaran_e2 = rkt.kode_sasaran_e2 '.$where.' GROUP BY sasaran.deskripsi, sasaran.kode_sasaran_e2 ) as t1',false);
		//$this->db->group_by('sasaran.deskripsi, sasaran.kode_sasaran_e2');
		 //$sql = 'select count(*) as num_rows from (SELECT DISTINCT iku.deskripsi as indikator_kinerja,iku.satuan,pk.target, rkt.realisasi FROM tbl_kinerja_eselon2 rkt inner join tbl_ikk iku on iku.kode_ikk = rkt.kode_ikk inner join tbl_sasaran_eselon2 sasaran on sasaran.kode_sasaran_e2 = rkt.kode_sasaran_e2 inner join tbl_pk_eselon2 pk on pk.kode_sasaran_e2 = rkt.kode_sasaran_e2 and pk.kode_ikk=rkt.kode_ikk  inner join tbl_eselon2 e2 on e2.kode_e2=rkt.kode_e2 '.$where.'  ) as t1';
		 $sql = 'select count(*) as num_rows from (SELECT DISTINCT iku.deskripsi as indikator_kinerja,iku.satuan,pk.target FROM tbl_ikk iku left join tbl_pk_eselon2 pk on pk.kode_ikk=iku.kode_ikk and pk.tahun=iku.tahun inner join tbl_eselon2 e2 on e2.kode_e2=iku.kode_e2 '.$where.'  ) as t1';
		$q = $this->db->query($sql);
		return $q->row()->num_rows;  
		//return $this->db->count_all_results();
		//$this->db->free_result();
	}
	
	public function getListTahun($objectId){
		
		$this->db->flush_cache();
		$this->db->select('distinct tahun',false);
		$this->db->from('tbl_kinerja_eselon2');
		$e2 = $this->session->userdata('unit_kerja_e2');
		if (($e2!="-1")&&($e2!=null)){
			$this->db->where('kode_e2',$e2);
			//$value = $e1;
		}
		$this->db->order_by('tahun');
		
		$que = $this->db->get();
		
		$out = '<select name="filter_tahun'.$objectId.'" id="filter_tahun'.$objectId.'">';
	//	$out .= '<option value="">Semua</option>';
		foreach($que->result() as $r){
			$out .= '<option value="'.$r->tahun.'">'.$r->tahun.'</option>';
		}
		
		$out .= '</select>';
		//chan
		if ($que->num_rows()==0){
			$out = "Data Kinerja belum tersedia.";
		}
		
		echo $out;
	}
	
	
	public function getRealisasi($tahun,$kode_iku,$bulan){
		$this->db->flush_cache();
		$this->db->select('realisasi as jumlah',false);
		$this->db->from('tbl_kinerja_eselon2');
		$this->db->where('kode_ikk', $kode_iku);
		$this->db->where('tahun', $tahun);
		if ($bulan>-1)
			$this->db->where('triwulan', $bulan);
		$query = $this->db->get();
		if ($query->num_rows()>0)
			return $query->row()->jumlah;
		else return 0;
		
	}
	
	
	public function getTarget($tahun,$kode_iku){
		$this->db->flush_cache();
		$this->db->select('penetapan as jumlah',false);
		$this->db->from('tbl_pk_eselon2');
		$this->db->where('kode_ikk', $kode_iku);
		$this->db->where('tahun', $tahun);
		$query = $this->db->get();
		if ($query->num_rows()>0)
			return $query->row()->jumlah;
		else return 0;
		
	}
	
}
?>
