<?php

require_once GTFWConfiguration::GetValue('application', 'docroot').'module/rtInventarisPinjam/response/ProcessPinjam.proc.class.php';

class DoUbahPinjam extends JsonResponse {

        function TemplateModule() {
        }

        function ProcessRequest() {
        $Obj = new ProcessPinjam();
        $urlRedirect = $Obj->Ubah();
        return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
        }

        function ParseTemplate($data = NULL) {
        }
}
?>