<?php

class ViewTambahSekolah extends HtmlResponse{

	function TemplateModule(){
		$this->SetTemplateBasedir(Configuration::Instance()->GetValue('application', 'docroot').'module/data_sekolah/template');
		$this->SetTemplateFile('view_tambah_sekolah.html');
	}

	function ProcessRequest(){

	}

	function ParseTemplate($data = NULL) {
	$this->mrTemplate->Addvar('content', 'URL_ACTION', Dispatcher::Instance()->GetUrl('data_sekolah', 'TambahSekolah', 'Do', 'json'));
	$this->mrTemplate->addVar('content', 'URL_CANCEL', Dispatcher::Instance()->GetUrl(Dispatcher::Instance()->mModule, 'ListSekolah', 'view', 'html'));
	}
}
?>