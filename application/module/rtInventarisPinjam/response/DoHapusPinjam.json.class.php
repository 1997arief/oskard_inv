<?php

require_once GTFWConfiguration::GetValue('application', 'docroot').'module/rtInventarisPinjam/response/ProcessPinjam.proc.class.php';

class DoHapusPinjam extends JsonResponse {

        function TemplateModule() {
        }

        function ProcessRequest() {
        $Obj = new ProcessPinjam();
        $urlRedirect = $Obj->Hapus();
        return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
        }

        function ParseTemplate($data = NULL) {
        }
}
?>