<?php
require_once Configuration::Instance()->GetValue('application', 'docroot').'module/rtInventaris/business/mysqlt/Latihan.class.php';

class ViewUbahInventaris extends HtmlResponse {
	function TemplateModule() {
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/rtInventaris/template');
		$this->SetTemplateFile('view_ubah_inventaris.html');
	}
	
	function Processrequest() {
		$ObjInv = new Latihan();
		$invId = Dispatcher::Instance()->Encrypt($_GET['invId']->Raw());
		if(!empty($invId)) {
			$return['dataInventaris'] = $ObjInv->GetInventarisById($invId);
		}
			$return['inv_id'] = $invId;
			return $return;
	}
	
	function ParseTemplate ($data = NULL) {
	
		if(!empty($data['inv_id'])) {
			if ($data['dataInventaris'][0]['inv_pemilik']=="RT") {
				$this->mrTemplate->addVar('pemilik','RT_IS_SELECT','selected');
			} elseif ($data['dataInventaris'][0]['inv_pemilik']=="Pemuda") {
				$this->mrTemplate->addVar('pemilik','PEMUDA_IS_SELECT','selected');
			} else {
				$this->mrTemplate->addVar('pemilik','DASAWISMA_IS_SELECT','selected');
			}
			$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('rtInventaris', 'UbahInventaris', 'do', 'json'));
			$this->mrTemplate->AddVar('content', 'INV_NAMA', $data['dataInventaris'][0]['inv_nama']);
			$this->mrTemplate->AddVar('content', 'INV_PEMILIK', $data['dataInventaris'][0]['inv_pemilik']);
			$this->mrTemplate->AddVar('content', 'INV_JML', $data['dataInventaris'][0]['inv_jml']);
			$this->mrTemplate->AddVar('content', 'INV_KET', $data['dataInventaris'][0]['inv_ket']);
			$this->mrTemplate->AddVar('content', 'INV_ID', $data['inv_id']);
			$this->mrTemplate->addVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'ListLatihanKu', 'view', 'html'));
		} else {
			$this->mrTemplate->AddVar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('rtInventaris', 'ListLatihanKu', 'do', 'json'));
		}
		
	}
}
?>