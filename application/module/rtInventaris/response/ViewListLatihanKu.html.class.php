<?php
/**
* @author Arief M. Ikhsan
* @license http://gtfw.gamatechno.com/#license
**/

require_once Configuration::Instance()->GetValue('application', 'docroot').'module/rtInventaris/business/'.Configuration::Instance()->GetValue('application', 'db_conn',0,'db_type').'/Latihan.class.php';

class ViewListLatihanKu extends HtmlResponse {

	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/rtInventaris/template');
		$this->SetTemplateFile('view_list_latihan_ku.html');
	}
	
	function ProcessRequest() {
		$msg = Messenger::Instance()->Receive(__FILE__);
		if($msg) {
			$return['pesan'] = $msg[0][1];
			$return['css'] = $msg[0][2];
		} else {
			$return['pesan'] = null;
			$return['css'] = null;
		}
		$Obj = new Latihan();
		//Latihan dari class di bussiness Latihan.class.php

		//pagination
		$itemViewed = 10;
		$currPage = 1;
		$startRec = 0;
		if(isset($_GET['page'])) {
			$currPage = (string) $_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec = ($currPage - 1) * $itemViewed;
		}

		/*
		if (is_object($_POST)) {
			$_POST= $_POST->AsArray();
		}
		var_dump($_POST);
		*/

		//cari
		if(isset($_POST['cari'])) {
			$cariNama = $_POST['cari_nama'];
			$cariPemilik = $_POST['pemilik'];
			$totalData = $Obj->GetHitungInventarisCari($cariNama,$cariPemilik);
    		$return['dataInventaris']=$Obj->GetCariInventaris($cariNama,$cariPemilik,$startRec, $itemViewed);
    		$return['text_cari'] = $cariNama;
    		$return['pemilik'] = $cariPemilik;
        } else {
        	$totalData = $Obj->GetHitungInventaris();
			$return['dataInventaris']=$Obj->GetDataInventaris($startRec, $itemViewed);
		//GetDataInventaris dari function di bussiness Latihan.class.php
			$return ['text_cari'] = '';
			$return['pemilik'] = '';
		}
		//end of cari

		$url = Dispatcher::Instance()->GetUrl(
		Dispatcher::Instance()->mModule,
		Dispatcher::Instance()->mSubModule,
		Dispatcher::Instance()->mAction,
		Dispatcher::Instance()->mType);
		
		$destination_id = "subcontent-element";
		
		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed, $totalData, $url, $currPage, $destination_id), Messenger::CurrentRequest);
		$return['start_number'] = $startRec+1;
		//end of pagination
		return $return;
	}
	
	function ParseTemplate($data=NULL) {
		$this->mrTemplate->Addvar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('rtInventaris', 'TambahInventaris', 'View', 'html'));
		$this->mrTemplate->Addvar('content', 'URL_CARI', Dispatcher::Instance()->GetUrl('rtInventaris', 'ListLatihanKu', 'view', 'html'));

		if($data['pesan']!="") {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
		}

		$this->mrTemplate->AddVar('pencarian', 'TEXT_CARI', $data['text_cari']);
		
		switch ($data['pemilik']) {
			case 'RT':
				$this->mrTemplate->addVar('pemilik','RT_IS_SELECTED','selected="selected"');
				break;
			case 'Pemuda':
				$this->mrTemplate->addVar('pemilik','PEMUDA_IS_SELECTED','selected="selected"');
				break;
			case 'Dasawisma':
				$this->mrTemplate->addVar('pemilik','DASAWISMA_IS_SELECTED','selected="selected"');
				break;
			
			default:
				$this->mrTemplate->addVar('pemilik','IS_SELECTED','selected="selected"');
				break;
		}

		if(!empty($data['dataInventaris'])) {
			$this->mrTemplate->AddVar('data_inventaris_cek', 'DATA_EMPTY', 'NO');
			
			$no = $data['start_number'];
			foreach($data['dataInventaris'] as $key=>$value) {
				$value['no'] = $no;
				//$value['row_class'] = $no%2 == 0?'even':'odd';
				if ($value['inv_ket']==NULL) {
					$value['inv_ket']="-";
				}
				$value['URL_UPDATE'] = Dispatcher::Instance()->GetUrl('rtInventaris', 'UbahInventaris', 'view', 'html').'&invId='.Dispatcher::Instance()->Encrypt($value['inv_id']);
				$value['URL_PINJAM'] = Dispatcher::Instance()->GetUrl('rtInventaris', 'PinjamInventaris', 'view', 'html').'&invId='.Dispatcher::Instance()->Encrypt($value['inv_id']);
					$value['row_class'] = $no%2 == 0?'even':'odd';
					$label = "Inventaris";
                    $idEnc = Dispatcher::Instance()->Encrypt($value['inv_id']);
                    $dataName = Dispatcher::Instance()->Encrypt($value['inv_nama']);
                    $urlAccept = 'rtInventaris|HapusInventaris|do|json';
                    $urlReturn = 'rtInventaris|ListLatihanKu|view|html';
                    $value['URL_DELETE']=Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html').'&urlDelete='.$urlAccept.'&urlReturn='.$urlReturn.'&id='.$idEnc.'&label='.$label.'&dataName='.$dataName;
                    $this->mrTemplate->AddVar('content', 'URL_DELETE', Dispatcher::Instance()->GetUrl('confirm', 'confirmDelete', 'do', 'html'));
				$this->mrTemplate->AddVars('data_inventaris', $value);
				$this->mrTemplate->parseTemplate('data_inventaris', 'a');
				$no++;
			}
		} else {
			$this->mrTemplate->AddVar('data_inventaris_cek', 'DATA_EMPTY', 'YES');
		}
	}
}
?>