<?php
/**
* @author rief_ikhsan
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
*/

require_once GTFWConfiguration::GetValue('application', 'docroot'). 'module/data_sekolah/business/mysqlt/Sekolah.class.php';

class ProcessSekolah {
	var $_POST;
	var $Obj;
	var $pageInput;
	var $pageView;
	var $cssDone = "alert-success";
	var $cssFail = "alert-danger";

	function __construct(){
		$this->Obj = new Sekolah();
		$this->_POST = $_POST->AsArray();
		$this->pageView = Dispatcher::Instance()->GetUrl('data_sekolah', 'ListSekolah', 'View', 'html');
	}
	
	function Tambah(){
		if(isset($this->_POST['btnsimpan'])){	
			$tambah = $this->Obj->DoTambahSekolah($this->_POST['sekolah_nama'],$this->_POST['sekolah_alamat'],$this->_POST['sekolah_email'],$this->_POST['sekolah_telp']);
			if ($tambah==true) {
				Messenger::Instance()->Send('data_sekolah', 'ListSekolah', 'view', 'html', array($this->_POST,'<i class="fa fa-check-circle"></i><span style="margin-right:15px;"></span>Penambahan data berhasil dilakukan', $this->cssDone), Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('data_sekolah', 'ListSekolah', 'view', 'html', array($this->_POST,'<i class="fa fa-warning"></i><span style="margin-right:15px;"></span>Penambahan data gagal dilakukan', $this->cssFail), Messenger::NextRequest);
			}
			return $this->pageView;
		}
	}
}
?>