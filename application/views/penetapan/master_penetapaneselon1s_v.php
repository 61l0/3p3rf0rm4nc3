	<script  type="text/javascript" >
		$(function(){
			var url;
			newData<?=$objectId;?> = function (){  
				$('#dlg<?=$objectId;?>').dialog('open').dialog('setTitle','Add Program PK Eselon I');  
				$('#fm<?=$objectId;?>').form('clear');  
				url = base_url+'penetapan/masterpenetapaneselon1/save/add';  
				
				//addTab("Add Program PK Kementerian", "penetapan/masterpenetapankl/add");
			}
			//end newData 
			
			editData<?=$objectId;?> = function (){
				var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
				$('#fm<?=$objectId;?>').form('clear');  
				//alert(row.dokter_kode);
				if (row){
					$('#dlg<?=$objectId;?>').dialog('open').dialog('setTitle','Edit Program PK Eselon I');
					$('#fm<?=$objectId;?>').form('load', row);
					url = base_url+'penetapan/masterpenetapaneselon1/save/edit/'+row.id_masterpk_e1;//+row.id;//'update_user.php?id='+row.id;
				}
				
				/*if (row){
					addTab("Edit Program PK Kementerian", "penetapan/masterpenetapankl/edit/"+row.id_masterpk_kl);
				}*/
			}
			//end editData
			
			deleteData<?=$objectId;?> = function (){
				<? if ($this->session->userdata('unit_kerja_e1')=='-1'){?>				
					var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
					if(row){
						if(confirm("Apakah yakin akan menghapus data '" + row.kode_program + "'?")){
							var response = '';
							$.ajax({ type: "GET",
									 url: base_url+'penetapan/masterpenetapaneselon1/delete/' + row.id_masterpk_e1,
									 async: false,
									 success : function(response)
									 {
										var response = eval('('+response+')');
										if (response.success){
											$.messager.show({
												title: 'Success',
												msg: 'Data Berhasil Dihapus'
											});
											
											// reload and close tab
											$('#dg<?=$objectId;?>').datagrid('reload');
										} else {
											$.messager.show({
												title: 'Error',
												msg: response.msg
											});
										}
									 }
							});
						}
					}
				<?} else { ?>	
					alert("Silahkan Login sebagai Superadmin");
				<?} ?>
			}
			//end deleteData 
			
			clearFilter<?=$objectId;?> = function (){
				$("#filter_tahun<?=$objectId;?>").val('');
				searchData<?=$objectId;?>();
			}
			
			//tipe 1=grid, 2=pdf, 3=excel
			getUrl<?=$objectId;?> = function (tipe){
				var filtahun = $("#filter_tahun<?=$objectId;?>").val();
				
				if(filtahun.length==0) filtahun ="-1";
				
				if (tipe==1){
					return "<?=base_url()?>penetapan/masterpenetapaneselon1/grid/"+filtahun;
				}
				else if (tipe==2){
					return "<?=base_url()?>penetapan/masterpenetapaneselon1/pdf/"+filtahun;
				}else if (tipe==3){
					return "<?=base_url()?>penetapan/masterpenetapaneselon1/excel/"+filtahun;
				}
				
			}
			
			searchData<?=$objectId;?> = function (){
				//ambil nilai-nilai filter
				$('#dg<?=$objectId;?>').datagrid({
					url:getUrl<?=$objectId;?>(1),
					queryParams:{lastNo:'0'},	
					pageNumber : 1,
					onLoadSuccess:function(data){	
						$('#dg<?=$objectId;?>').datagrid('options').queryParams.lastNo = data.lastNo;
						//prepareMerge<?=$objectId;?>(data);
				}});
			}
			//end searchData 		
		
			printData<?=$objectId;?>=function(){			
				//$.jqURL.loc(getUrl<?=$objectId;?>(2),{w:800,h:600,wintype:"_blank"});
			//window.open(getUrl<?=$objectId;?>(2));;
			alert("Sedang dalam pengerjaan");
		}
			toExcel<?=$objectId;?>=function(){
				alert("Sedang dalam pengerjaan");	
				//window.open(getUrl<?=$objectId;?>(3));;
			}
			
			saveData<?=$objectId;?>=function(){
				$('#fm<?=$objectId;?>').form('submit',{
					url: url,
					onSubmit: function(){
						return $(this).form('validate');
					},
					success: function(result){
						// alert(result);
						var result = eval('('+result+')');
						if (result.success){
							/* $.messager.show({
								title: 'Success',
								msg: result.msg
							}); */
							$('#dlg<?=$objectId;?>').dialog('close');		// close the dialog
							$('#dg<?=$objectId;?>').datagrid('reload');	// reload the user data
						} else {
							$.messager.show({
								title: 'Error',
								msg: result.msg
							});
						}
					}
				});
			}
			//end saveData
			
			formatPrice=function (val,row){
				return val;//($.fn.autoNumeric.Format("txtAmount"+idx,total,{aSep:".",aDec:",",mDec:2}));
				/* if (val < 20){
					return '<span style="color:red;">('+val+')</span>';
				} else {
					return val;
				} */
			}

			
			setTimeout(function(){
				$('#dg<?=$objectId;?>').datagrid({url:"<?=base_url()?>penetapan/masterpenetapaneselon1/grid"});
			},0);
		 });
	</script>
	<style type="text/css">
		#fm<?=$objectId;?>{
			margin:0;
			padding:10px 30px;
		}
		.ftitle{
			font-size:14px;
			font-weight:bold;
			color:#666;
			padding:5px 0;
			margin-bottom:10px;
			border-bottom:1px solid #ccc;
		}
		.fitem{
			margin-bottom:5px;
		}
		.fitem label{
			display:inline-block;
			width:80px;
		}
	  .fsearch{
		background:#fafafa;
		border-radius:5px;
		-moz-border-radius:0px;
		-webkit-border-radius: 5px;
		-moz-box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.2);
		-webkit-box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.2);
		filter: progid:DXImageTransform.Microsoft.Blur(pixelRadius=2,MakeShadow=false,ShadowOpacity=0.2);
		margin-bottom:10px;
		border: 1px solid #99BBE8;
	    color: #15428B;
	    font-size: 11px;
	    font-weight: bold;
	    position: relative;
	  }
	  .fsearch div{
		background:url('<?=base_url();?>public/css/themes/gray/images/panel_title.gif') repeat-x;
		height:200%;
		border-bottom: 1px solid #99BBE8;
		color:#15428B;
		font-size:10pt;
		text-transform:uppercase;
	    font-weight: bold;
	    padding: 5px;
	    position: relative;
	  }
	  .fsearch table{
	    padding: 15px;
	  }
	  .fsearch label{
		display:inline-block;
		width:60px;
	  }
		.fitemArea{
			margin-bottom:5px;
			text-align:left;
			/* border:1px solid blue; */
		}
		.fitemArea label{
			display:inline-block;
			width:84px;
			margin-bottom:5px;
		}
		
	</style>
	
	<div id="tb<?=$objectId;?>" style="height:auto">
	   <table border="0" cellpadding="1" cellspacing="1" width="100%">
		<tr>
			<td>
			<div class="fsearch" <?=($this->session->userdata('unit_kerja_e1')=='-1'?'':'style="display:none"')?>>
				<table border="0" cellpadding="1" cellspacing="1">
				<tr>
					<td>Tahun :</td>
					<td>
					<?=$this->master_penetapaneselon1_model->getListFilterTahun($objectId)?>
					</td>
				</tr>
				<tr style="height:10px">
					  <td style="">
					  </td>
				</tr>
				<tr>
					<td align="right" colspan="2" valign="top">
						<a href="#" class="easyui-linkbutton" onclick="clearFilter<?=$objectId;?>();" iconCls="icon-reset">Reset</a>
						<a href="#" class="easyui-linkbutton" onclick="searchData<?=$objectId;?>();" iconCls="icon-search">Search</a>
					</td>
				</tr>
				</table>
			</div>
			</td>
		</tr>
		</table>
	  <div style="margin-bottom:5px">  
		<a href="#" onclick="newData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-add" plain="true">Add</a>  
		<a href="#" onclick="editData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-edit" plain="true">Edit</a>
		<a href="#" onclick="deleteData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-remove" plain="true">Delete</a>
		<!--
		<a href="#" onclick="printData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-print" plain="true">Print</a>
		<a href="#" onclick="toExcel<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-excel" plain="true">Excel</a>
		-->
	  </div>
	</div>
	
	<table id="dg<?=$objectId;?>" class="easyui-datagrid" style="height:auto;width:auto" title="Data Program Penetapan Kinerja (PK) Eselon I" toolbar="#tb<?=$objectId;?>" fitColumns="true" singleSelect="true" rownumbers="true" pagination="true">
	  <thead>
	  <tr>
		<th field="id_masterpk_e1" hidden="true" sortable="true" width="50px">Kode</th>
		<th field="tahun" sortable="true" width="30px">Tahun</th>		
		<th field="kode_e1" sortable="true" width="50px">Kode Eselon I</th>
		<th field="kode_program" sortable="true" width="50px">Kode Program</th>
		<th field="nama_program" sortable="true" width="150px">Nama Program</th>
		<th field="total" sortable="true" width="50px" align="right" formatter="formatPrice">Total</th>
	  </tr>
	  </thead>  
	</table>

	<!-- AREA untuk Form Add/EDIT >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  -->
	<div id="dlg<?=$objectId;?>" class="easyui-dialog" style="width:850px;height:250px;padding:10px 20px" closed="true" buttons="#dlg-buttons">
		<div class="ftitle">Tambah/Edit Data Program Penetapan Kinerja (PK) Eselon I</div>
		<form id="fm<?=$objectId;?>" method="post">		
			<div class="fitem">
			  <label style="width:120px">Tahun :</label>
			  <?=$this->penetapaneselon1_model->getListTahun()?>
			</div>					
			<div class="fitem">
				<label style="width:120px">Unit Kerja :</label>
				<?php
					//if($this->session->userdata('unit_kerja_e1')=='-1'){
						echo $this->eselon1_model->getListEselon1($objectId);
				/* 	}else{
						echo $this->eselon1_model->getNamaE1($this->session->userdata('unit_kerja_e1'));
						echo "<input type='hidden' name='kode_e1' value='".$this->session->userdata('unit_kerja_e1')."'/>";
					} */
				?>
			</div>
			<div class="fitem">
				<label style="width:120px">Nama Program :</label>
				<?=$this->programkl_model->getListProgramKL()?>
			</div>
		</form>
    	<div id="dlg-buttons">
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveData<?=$objectId;?>()">Save</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg<?=$objectId;?>').dialog('close')">Cancel</a>
    	</div>
	</div>