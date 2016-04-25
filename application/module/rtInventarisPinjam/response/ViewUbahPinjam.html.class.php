<?php
require_once Configuration::Instance()->GetValue('application', 'docroot').'module/rtInventarisPinjam/business/mysqlt/Pinjam.class.php';

class ViewUbahPinjam extends HtmlResponse {
	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/rtInventarisPinjam/template');
		$this->SetTemplateFile('view_ubah_pinjam.html');
	}
	
	function Processrequest() {
		$msg = Messenger::Instance()->Receive(__FILE__);
		if($msg) {
			$return['pesan'] = $msg[0][1];
			$return['css'] = $msg[0][2];
		} else {
			$return['pesan'] = null;
			$return['css'] = null;
		}

		$Obj = new Pinjam();
		$pinjamId = Dispatcher::Instance()->Encrypt($_GET['pinjamId']->Raw());
		$invId = Dispatcher::Instance()->Encrypt($_GET['invId']->Raw());
		if(!empty($pinjamId)) {
			$return['dataPinjam'] = $Obj->GetPinjamById($pinjamId);
			$return['jmlPinjam'] = $Obj->GetJumlahPinjam($invId);
			//var_dump($Obj->GetJumlahPinjam($invId));
		}
		$return['pinjam_id'] = $pinjamId;
		return $return;
	}
	
	function ParseTemplate ($data = NULL) {
		if($data['pesan']!="") {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
		}

		if(!empty($data['pinjam_id'])) {
			$jmlTersedia=$data['dataPinjam'][0]['inv_jml']-$data['jmlPinjam'][0]['jumlah'];
			$jmlMax=$jmlTersedia+$data['dataPinjam'][0]['pinjam_jml'];
			if ($data['dataPinjam'][0]['pinjam_kondisi']=="B") {
				$this->mrTemplate->AddVar('content', 'B', 'checked');	
			} else {
				$this->mrTemplate->AddVar('content', 'R', 'checked');	
			}
			$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('rtInventarisPinjam', 'UbahPinjam', 'do', 'json'));
			$this->mrTemplate->AddVar('content', 'PINJAM_NAMA', $data['dataPinjam'][0]['pinjam_nama']);
			$this->mrTemplate->AddVar('content', 'INV_NAMA', $data['dataPinjam'][0]['inv_nama']);
			$this->mrTemplate->AddVar('content', 'INV_ID', $data['dataPinjam'][0]['inv_id']);
			$this->mrTemplate->AddVar('content', 'JML_MAX', $jmlMax);
			$this->mrTemplate->AddVar('content', 'PINJAM_JML', $data['dataPinjam'][0]['pinjam_jml']);
			$this->mrTemplate->AddVar('content', 'PINJAM_TGL', $data['dataPinjam'][0]['pinjam_tgl']);
			$this->mrTemplate->AddVar('content', 'PINJAM_ID', $data['pinjam_id']);
			$this->mrTemplate->addVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'ListPinjam', 'view', 'html'));
		} else {
			$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('rtInventarisPinjam', 'ListPinjam', 'do', 'json'));
		}
		
	}
}
?>