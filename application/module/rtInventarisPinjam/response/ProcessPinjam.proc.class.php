<?php
/**
* @author Arief M. Ikhsan
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
*/

require_once GTFWConfiguration::GetValue('application', 'docroot'). 'module/rtInventarisPinjam/business/mysqlt/Pinjam.class.php';

class ProcessPinjam {
	var $_POST;
	var $Obj;
	var $pageInput;
	var $pageView;
	var $cssDone = "alert-success";
	var $cssFail = "alert-danger";

	function __construct(){
		$this->Obj = new Pinjam();
		$this->_POST = $_POST->AsArray();
		$this->pageView = Dispatcher::Instance()->GetUrl('rtInventarisPinjam', 'ListPinjam', 'View', 'html');
	}

	function Ubah(){
		if(isset($this->_POST['btnsimpan'])){
			$pinjamId = $this->_POST['pinjam_id'];
			if ($this->_POST['pinjam_jml']<=$this->_POST['jml_max']) {
				$ubah = $this->Obj->DoUbahPinjam($this->_POST['pinjam_nama'],$this->_POST['pinjam_jml'],$this->_POST['pinjam_status'],$this->_POST['pinjam_kondisi'],$this->_POST['pinjam_tgl'], $pinjamId);
				if ($ubah==true) {
					Messenger::Instance()->Send('rtInventarisPinjam', 'ListPinjam', 'view', 'html', array($this->_POST,'<i class="fa fa-check-circle"></i><span style="margin-right:15px;"></span>Ubah data berhasil dilakukan', $this->cssDone), Messenger::NextRequest);
				} else {
					Messenger::Instance()->Send('rtInventarisPinjam', 'ListPinjam', 'view', 'html', array($this->_POST,'<i class="fa fa-warning"></i><span style="margin-right:15px;"></span>Ubah data gagal dilakukan', $this->cssFail), Messenger::NextRequest);
				}
				return $this->pageView;
			} else {
				Messenger::Instance()->Send('rtInventarisPinjam', 'UbahPinjam', 'view', 'html', array($this->_POST,'* Jumlah tidak boleh lebih dari '.$this->_POST['jml_max'], $this->cssFail), Messenger::NextRequest);
				return Dispatcher::Instance()->GetUrl('rtInventarisPinjam', 'UbahPinjam', 'view', 'html').'&pinjamId='.Dispatcher::Instance()->Encrypt($pinjamId).'&invId='.Dispatcher::Instance()->Encrypt($this->_POST['inv_id']);
			}
			
		}
	}

	function Hapus() {
		$pinjamId = $this->_POST['idDelete'];
		if(isset($pinjamId)) {
			$hapus = $this->Obj->DoHapusPinjam($pinjamId);
			if($hapus == true) {
			Messenger::Instance()->Send('rtInventarisPinjam', 'ListPinjam', 'view', 'html', array($this->_POST,'<i class="fa fa-check-circle"></i><span style="margin-right:15px;"></span>Hapus Data Berhasil Dilakukan', $this->cssDone), Messenger::NextRequest);
			} else {
			Messenger::Instance()->Send('rtInventarisPinjam', 'ListPinjam', 'view', 'html', array($this->_POST,'<i class="fa fa-warning"></i><span style="margin-right:15px;"></span>Hapus Data Gagal Dilakukan', $this->cssFail), Messenger::NextRequest);
			}
		}
		return $this->pageView;
	}
}
?>