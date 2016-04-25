<?php
/**
* @author Arief M. Ikhsan
* @license http://gtfw.gamatechno.com/#license
**/

class ViewTambahPeminjam extends HtmlResponse{

	function TemplateModule(){
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/rtInventaris/template');
		$this->SetTemplateFile('view_tambah_peminjam.html');
	}

	function ProcessRequest(){
		/*
		if (is_object($_GET)) {
			$_GET
		}
		*/
		$msg = Messenger::Instance()->Receive(__FILE__);
		if($msg) {
			$return['pesan'] = $msg[0][1];
			$return['css'] = $msg[0][2];
			$return['data'] = $msg[0][0];
		} else {
			$return['pesan'] = null;
			$return['css'] = null;
		}
		//var_dump($msg);
		//exit;
		$invId = Dispatcher::Instance()->Encrypt($_GET['invId']->Raw());
		$jmlSisa = Dispatcher::Instance()->Encrypt($_GET['sisaJml']->Raw());
		//ini juga bisa -> $invId = Dispatcher::Instance()->Decrypt($_GET['invId']);
		$return['jml_sisa'] = $jmlSisa;
		$return['inv_id'] = $invId;
		return $return;
	}

	function ParseTemplate($data = NULL) {
		if($data['pesan']!="") {
			$this->mrTemplate->SetAttribute('warning_box', 'visibility', 'visible');
			$this->mrTemplate->AddVar('warning_box', 'ISI_PESAN', $data['pesan']);
			$this->mrTemplate->AddVar('warning_box', 'CLASS_PESAN', $data['css']);
			$this->mrTemplate->AddVar('content', 'PINJAM_NAMA', $data['data']['pinjam_nama']);
			$this->mrTemplate->AddVar('content', 'PINJAM_JML', $data['data']['pinjam_jml']);
			$this->mrTemplate->AddVar('content', 'PINJAM_TGL', $data['data']['pinjam_tgl']);
		}

		if (!empty($data['inv_id'])) {
			$this->mrTemplate->AddVar('content', 'INV_ID', $data['inv_id']);
			$this->mrTemplate->AddVar('content', 'JML_SISA', $data['jml_sisa']);
		}
		$this->mrTemplate->Addvar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('rtInventaris', 'TambahPeminjam', 'Do', 'json'));
		$this->mrTemplate->addVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'PinjamInventaris', 'view', 'html').'&invId='.Dispatcher::Instance()->Encrypt($data['inv_id']));
	}
}
?>