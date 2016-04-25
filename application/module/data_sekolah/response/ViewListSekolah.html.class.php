<?php
/**
* @author Arief M. Ikhsan
*/

require_once Configuration::Instance()->GetValue('application', 'docroot').'module/data_sekolah/business/'.Configuration::Instance()->GetValue('application', 'db_conn',0,'db_type').'/Sekolah.class.php';

class ViewListSekolah extends HtmlResponse {

	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/data_sekolah/template');
		$this->SetTemplateFile('view_list_sekolah.html');
	}
	
	function ProcessRequest() {
		//message
		$msg = Messenger::Instance()->Receive(__FILE__);
		if($msg) {
			$return['pesan'] = $msg[0][1];
			$return['css'] = $msg[0][2];
		} else {
			$return['pesan'] = null;
			$return['css'] = null;
		}
		//end of message

		$Obj = new Sekolah();

		//pagination
		$itemViewed = 10;
		$currPage = 1;
		$startRec = 0;
		if(isset($_GET['page'])) {
			$currPage = (string) $_GET['page']->StripHtmlTags()->SqlString()->Raw();
			$startRec = ($currPage - 1) * $itemViewed;
		}
		$url = Dispatcher::Instance()->GetUrl(
		Dispatcher::Instance()->mModule,
		Dispatcher::Instance()->mSubModule,
		Dispatcher::Instance()->mAction,
		Dispatcher::Instance()->mType);
		
		$destination_id = "subcontent-element";
		
		//search
		$textCari='';
		if (isset($_POST['cari'])) {
			$textCari = $_POST['text_cari'];
		}
		$return['text_cari'] = $textCari;
		//end of search

		$totalData = $Obj->GetCountSekolah($textCari,$textCari);
        $return['dataSekolah'] = $Obj->GetSekolah($textCari,$textCari,$startRec, $itemViewed);

		Messenger::Instance()->SendToComponent('paging', 'Paging', 'view', 'html', 'paging_top', array($itemViewed, $totalData, $url, $currPage, $destination_id), Messenger::CurrentRequest);
		$return['start_number'] = $startRec+1;
		//end of pagination

        return $return;
	}
	
	function ParseTemplate($data=NULL) {
		$this->mrTemplate->Addvar('content', 'URL_CARI', Dispatcher::Instance()->GetUrl('data_sekolah', 'ListSekolah', 'view', 'html'));
		$this->mrTemplate->AddVar('pencarian', 'TEXT_CARI', $data['text_cari']);
		$this->mrTemplate->Addvar('content', 'URL_ADD', Dispatcher::Instance()->GetUrl('data_sekolah', 'TambahSekolah', 'View', 'html'));

		if($data['pesan']!="") {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
		}
		
		if(!empty($data['dataSekolah'])) {
			$this->mrTemplate->AddVar('data_sekolah_cek', 'DATA_EMPTY', 'NO');
			$no = $data['start_number'];
	        foreach($data['dataSekolah'] as $key=>$value) {
	        	$value['no'] = $no;
	        	$value['sekolah_id'] = $value['sekolahId'];
	        	$value['sekolah_nama'] = $value['sekolahNama'];
	        	$value['sekolah_alamat'] = $value['sekolahAlamat'];
	        	$value['sekolah_email'] = $value['sekolahEmail'];
	        	$value['sekolah_telp'] = $value['sekolahTelp'];
				$this->mrTemplate->AddVars('data_sekolah', $value);
				$this->mrTemplate->parseTemplate('data_sekolah', 'a');
				$no++;
			}
		} else {
			$this->mrTemplate->AddVar('data_sekolah_cek', 'DATA_EMPTY', 'YES');
		}
	}
}
?>