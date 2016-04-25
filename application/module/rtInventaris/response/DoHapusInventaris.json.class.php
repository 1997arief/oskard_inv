<?php

require_once GTFWConfiguration::GetValue('application', 'docroot').'module/rtInventaris/response/ProcessLatihan.proc.class.php';

class DoHapusInventaris extends JsonResponse {

        function TemplateModule() {
        }

        function ProcessRequest() {
        $Obj = new ProcessLatihan();
        $urlRedirect = $Obj->Hapus();
        return array( 'exec' => 'GtfwAjax.replaceContentWithUrl("subcontent-element","'.$urlRedirect.'&ascomponent=1")');
        }

        function ParseTemplate($data = NULL) {
        }
}
?>