	<script  type="text/javascript" >
		var idPK<?=$objectId;?>;
		var rowIndexDetail;
		$(function(){
			var url;
			newData<?=$objectId;?> = function (){  
				//$('#dlg<?=$objectId;?>').dialog('open').dialog('setTitle','Add PK Kementerian');  
				//$('#fm<?=$objectId;?>').form('clear');  
				//url = base_url+'penetapan/perubahankl/save';  
				
				addTab("Add PK Kementerian", "penetapan/perubahankl/add");
			}
			//end newData 
			
			editData<?=$objectId;?> = function (editmode){
				var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
				
				if (row){
					addTab((editmode?"Edit":"View")+" PK Kementerian", "penetapan/perubahankl/edit/"+row.id_pk_kl+ "/" + editmode);
				}
			}
			//end editData
			
			deleteData<?=$objectId;?> = function (){
				<? if ($this->session->userdata('unit_kerja_e1')=='-1'){?>				
					var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
					if(row){
						if(confirm("Apakah yakin akan menghapus data '" + row.kode_iku_kl + "'?")){
							var response = '';
							$.ajax({ type: "GET",
									 url: base_url+'penetapan/perubahankl/delete/' + row.id_pk_kl,
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
					return "<?=base_url()?>penetapan/perubahankl/grid/"+filtahun;
				}
				else if (tipe==2){
					return "<?=base_url()?>penetapan/perubahankl/pdf/"+filtahun;
				}else if (tipe==3){
					return "<?=base_url()?>penetapan/perubahankl/excel/"+filtahun;
				}
				
			}
			
			searchData<?=$objectId;?> = function (){
				//ambil nilai-nilai filter
				$('#dg<?=$objectId;?>').datagrid({
					url:getUrl<?=$objectId;?>(1),
					queryParams:{lastNo:'0'},	
					pageNumber : 1,
					onClickCell: function(rowIndex, field, value){
						$('#dg<?=$objectId;?>').datagrid('selectRow', rowIndex);
						var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
						if (row==null) return;
						//alert(row.deskripsi_iku_kl);
						switch(field){
							case "kode_kl":
								showPopup('#popdesc<?=$objectId?>', row.nama_kl);
								break;
							case "kode_sasaran_kl":
								showPopup('#popdesc<?=$objectId?>', row.deskripsi_sasaran_kl);
								break;
							case "kode_iku_kl":
								showPopup('#popdesc<?=$objectId?>', row.deskripsi_iku_kl);
								break;
							/* case "kode_kl":
								showPopup('#popdesc<?=$objectId?>', row.nama_kl);
								break; */
							default:
								closePopup('#popdesc<?=$objectId?>');
								break;
						}
					},
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
						//alert(result);
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
				searchData<?=$objectId;?>();
				//$('#dg<?=$objectId;?>').datagrid({url:"<?=base_url()?>penetapan/perubahankl/grid"});
			},0);
			
						
			$("#popdesc<?=$objectId?>").click(function(){
				closePopup('#popdesc<?=$objectId?>');
			});
			
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
			<div class="fsearch" >
				<table border="0" cellpadding="1" cellspacing="1">
				<tr>
					<td>Tahun :</td>
					<td>
					<?=$this->perubahankl_model->getListFilterTahun($objectId)?>
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
		<? if($this->sys_menu_model->cekAkses('ADD;',104,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
	<!--		<a href="#" onclick="newData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-add" plain="true">Add</a>   -->
		<?}?>
		<!------------Edit View-->
		<? if($this->sys_menu_model->cekAkses('EDIT;',104,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="editData<?=$objectId;?>(true);" class="easyui-linkbutton" iconCls="icon-edit" plain="true">Edit</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('VIEW;',104,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="editData<?=$objectId;?>(false);" class="easyui-linkbutton" iconCls="icon-view" plain="true">View</a>
		<?}?>
		<? if($this->sys_menu_model->cekAkses('DELETE;',104,$this->session->userdata('group_id'),$this->session->userdata('level_id'))){?>
			<a href="#" onclick="deleteData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-remove" plain="true">Delete</a>
		<?}?>
		<!--
		<a href="#" onclick="printData<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-print" plain="true">Print</a>
		<a href="#" onclick="toExcel<?=$objectId;?>();" class="easyui-linkbutton" iconCls="icon-excel" plain="true">Excel</a>
		-->
	  </div>
	</div>
	
	<table id="dg<?=$objectId;?>" style="height:auto;width:auto" title="Data (PK) Kementerian Yang Sudah Ditetapkan" toolbar="#tb<?=$objectId;?>" fitColumns="true" singleSelect="true" rownumbers="true" pagination="true">
	  <thead>
	  <tr>
		<th field="id_pk_kl" hidden="true" sortable="true" width="50px">Kode</th>
		<th field="tahun" sortable="true" width="30px">Tahun</th>		
		<th field="kode_kl" sortable="true" width="50px">Kode Kementerian</th>
		<th field="nama_kl" hidden="true">nama_kl</th>
		<th field="kode_sasaran_kl" sortable="true" width="50px">Kode Sasaran</th>
		<th field="kode_iku_kl" sortable="true" width="50px">Kode IKU</th>
		<th field="target" sortable="true" width="50px" align="right" formatter="formatPrice">Target (RKT)</th>
		<th field="penetapan" sortable="true" width="50px" align="right" formatter="formatPrice">Target (PK)</th>
		<th field="satuan" sortable="true" width="50px">Satuan</th>
		<th field="deskripsi_iku_kl" hidden="true">deskripsi_iku_kl</th>
		<th field="deskripsi_sasaran_kl" hidden="true">deskripsi_sasaran_kl</th>
	  </tr>
	  </thead>  
	</table>
	
	
	<script type="text/javascript">
        $(function(){
			var parentId;
			// chan
			$('#dg<?=$objectId;?>').datagrid({
				url:getUrl<?=$objectId;?>(1),	
				view: detailview,
				queryParams:{rowIdx:'0'},	
				 detailFormatter:function(index,row){
                    return '<div style="padding:2px"><table id="ddv<?=$objectId;?>-' + index + '"></table></div>';
                //  return "tes";
                },
                onExpandRow: function(index,row){
				//	alert(row.id_pk_kl);
						
                    $('#ddv<?=$objectId;?>-'+index).datagrid({
                        url:'<?=base_url()?>penetapan/perubahankl/gridperubahan/'+row.id_pk_kl+'/?parentIndex='+index,
                        fitColumns:true,
                        singleSelect:true,
                        rownumbers:true,
                        loadMsg:'',
                        height:'auto',
                        columns:[[
                            {field:'id_pk_kl',title:'id',hidden:true},
                            {field:'no_history',title:'No.Perubahan',width:40},
                            {field:'tahun',title:'Tahun',width:30},
                            {field:'kode_kl',title:'Kode Kementerian',width:50},
                            {field:'nama_kl',title:'Kriteria Capaian',hidden:true},
                            {field:'kode_sasaran_kl',title:'Kode Sasaran',width:50},
                            {field:'kode_iku_kl',title:'Kode IKU',width:50},
                            {field:'target',title:'Target (RKT)',width:50,align:'right',formatter:formatPrice},
                            {field:'penetapan',title:'Target (PK)',width:50,align:'right',formatter:formatPrice},
                            {field:'satuan',title:'Satuan',width:50},
                            {field:'deskripsi_iku_kl',title:'Ukuran Capaian',hidden:true},
                            {field:'deskripsi_sasaran_kl',title:'Ukuran Capaian',hidden:true}                       
                        ]],
                        onResize:function(){
                            $('#dg<?=$objectId;?>').datagrid('fixDetailRowHeight',index);
                        },
                       onClickCell:function(rowIndex, field, value){
							 $('#ddv<?=$objectId;?>-'+index).datagrid('selectRow', rowIndex);
							var row = $('#ddv<?=$objectId;?>-'+index).datagrid('getSelected');
							if (row==null) return;
							///alert(row);
							idPK<?=$objectId;?> = row.id_pk_kl;
							rowIndexDetail = index;
							
					   },
                        onLoadSuccess:function(){
                            setTimeout(function(){
                                $('#dg<?=$objectId;?>').datagrid('fixDetailRowHeight',index);
                            },0);
                        }
                    });
                    $('#dg<?=$objectId;?>').datagrid('fixDetailRowHeight',index);

	
                },
				onClickCell: function(rowIndex, field, value){
					$('#dg<?=$objectId;?>').datagrid('selectRow', rowIndex);
					
					var row = $('#dg<?=$objectId;?>').datagrid('getSelected');
					idPK<?=$objectId;?> = null;
					if (row==null) return;
					//alert(row.deskripsi_iku_kl);
					switch(field){
						case "kode_kl":
							showPopup('#popdesc<?=$objectId?>', row.nama_kl);
							break;
						case "kode_sasaran_kl":
							showPopup('#popdesc<?=$objectId?>', row.deskripsi_sasaran_kl);
							break;
						case "kode_iku_kl":
							showPopup('#popdesc<?=$objectId?>', row.deskripsi_iku_kl);
							break;
						/* case "kode_kl":
							showPopup('#popdesc<?=$objectId?>', row.nama_kl);
							break; */
						default:
							closePopup('#popdesc<?=$objectId?>');
							break;
					}
				}
			});
			
			
            //searchData<?=$objectId;?>();
        });
    </script>
	
	<div class="popdesc" id="popdesc<?=$objectId?>">&nbsp;</div>