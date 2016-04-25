<?php

require_once GTFWConfiguration::GetValue('application', 'docroot').'module/data_sekolah/response/ProcessSekolah.proc.class.php';

class DoTambahSekolah extends JsonResponse {

	function TemplateModule() {
	}

	function ProcessRequest() {
		$Obj = new ProcessSekolah();
		$urlRedirect = $Obj->Tambah();
		return array('exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
	}

	function ParseTemplate($data = NULL) {
	}
}
?>