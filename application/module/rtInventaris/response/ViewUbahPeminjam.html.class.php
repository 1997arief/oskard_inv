<?php
require_once Configuration::Instance()->GetValue('application', 'docroot').'module/rtInventaris/business/mysqlt/Latihan.class.php';

class ViewUbahPeminjam extends HtmlResponse {
	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/rtInventaris/template');
		$this->SetTemplateFile('view_ubah_peminjam.html');
	}
	
	function Processrequest() {
		$msg = Messenger::Instance()->Receive(__FILE__);
		if($msg) {
			$return['pesan'] = $msg[0][1];
			$return['css'] = $msg[0][2];
			$return['detail'] = $msg[0][0];
		} else {
			$return['pesan'] = null;
			$return['css'] = null;
		}

		$Obj = new Latihan();
		$pinjamId = Dispatcher::Instance()->Encrypt($_GET['pinjamId']->Raw());
		$jmlSisa = Dispatcher::Instance()->Encrypt($_GET['sisaJml']->Raw());
		if(!empty($pinjamId)) {
			$return['dataPeminjam'] = $Obj->GetPeminjamById($pinjamId);
		}
		//var_dump($msg);
		$return['pinjam_id'] = $pinjamId;
		$return['jml_sisa'] = $jmlSisa;
		return $return;
	}
	
	function ParseTemplate ($data = NULL) {
	
		if($data['pesan']!="") {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
			//$this->mrTemplate->AddVar('content', 'PINJAM_NAMA', $data['detail']['pinjam_nama']);
			//$this->mrTemplate->AddVar('content', 'PINJAM_TGL', $data['detail']['pinjam_tgl']);
		}

		if(!empty($data['pinjam_id'])) {
			/*
			if ($data['dataPeminjam'][0]['inv_pemilik']=="RT") {
				$this->mrTemplate->addVar('pemilik','RT_IS_CHECKED','checked="checked"');
			} else {
				$this->mrTemplate->addVar('pemilik','PEMUDA_IS_CHECKED','checked="checked"');
			}
			*/
			if ($data['dataPeminjam'][0]['pinjam_kondisi']=="B") {
				$this->mrTemplate->AddVar('content','B','checked="checked"');
			} else {
				$this->mrTemplate->AddVar('content','R','checked="checked"');
			}
			$jmlMax = $data['jml_sisa'] + $data['dataPeminjam'][0]['pinjam_jml'];
			$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('rtInventaris', 'UbahPeminjam', 'do', 'json'));
			$this->mrTemplate->AddVar('content', 'PINJAM_NAMA', $data['dataPeminjam'][0]['pinjam_nama']);
			//$this->mrTemplate->AddVar('content', 'PINJAM_STATUS', $data['dataPeminjam'][0]['pinjam_status']);
			$this->mrTemplate->AddVar('content', 'PINJAM_JML', $data['dataPeminjam'][0]['pinjam_jml']);
			$this->mrTemplate->AddVar('content', 'PINJAM_KONDISI', $data['dataPeminjam'][0]['pinjam_kondisi']);
			$this->mrTemplate->AddVar('content', 'PINJAM_TGL', $data['dataPeminjam'][0]['pinjam_tgl']);
			$this->mrTemplate->AddVar('content', 'PINJAM_INV_ID', $data['dataPeminjam'][0]['pinjam_inv_id']);
			$this->mrTemplate->AddVar('content', 'PINJAM_ID', $data['pinjam_id']);
			$this->mrTemplate->AddVar('content', 'SISA_JUMLAH', $data['jml_sisa']);
			$this->mrTemplate->AddVar('content', 'JML_MAX', $jmlMax);
			$this->mrTemplate->addVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'PinjamInventaris', 'view', 'html').'&invId='.Dispatcher::Instance()->Encrypt($data['dataPeminjam'][0]['pinjam_inv_id']));
		} else {
			$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('rtInventaris', 'ListLatihanKu', 'do', 'json'));
		}
		
	}
}
?>