<?php
/**
* @author rief_ikhsan
* @copyright Copyright (c) 2014, PT Gamatechno Indonesia
* @license http://gtfw.gamatechno.com/#license
*/

require_once GTFWConfiguration::GetValue('application', 'docroot'). 'module/rtInventaris/business/mysqlt/Latihan.class.php';

class ProcessLatihan {
	var $_POST;
	var $Obj;
	var $pageInput;
	var $pageView;
	var $pageView2;
	var $cssDone = "alert-success";
	var $cssFail = "alert-danger";

	function __construct(){
		$this->Obj = new Latihan();
		$this->_POST = $_POST->AsArray();
		$this->pageView = Dispatcher::Instance()->GetUrl('rtInventaris', 'ListLatihanKu', 'View', 'html');

		if (isset($this->_POST['pinjam_inv_id'])) {
			$this->pageView2 = Dispatcher::Instance()->GetUrl('rtInventaris', 'PinjamInventaris', 'View', 'html').'&invId='.Dispatcher::Instance()->Encrypt($this->_POST['pinjam_inv_id']);
		}
	}
	
	function Tambah(){
		if(isset($this->_POST['btnsimpan'])){	
			$tambah = $this->Obj->DoTambahInventaris($this->_POST['inv_nama'],$this->_POST['inv_pemilik'],$this->_POST['inv_jml'],$this->_POST['inv_ket']);
			if ($tambah==true) {
				Messenger::Instance()->Send('rtInventaris', 'ListLatihanKu', 'view', 'html', array($this->_POST,'<i class="fa fa-check-circle"></i><span style="margin-right:15px;"></span>Penambahan data berhasil dilakukan', $this->cssDone), Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('rtInventaris', 'ListLatihanKu', 'view', 'html', array($this->_POST,'<i class="fa fa-warning"></i><span style="margin-right:15px;"></span>Penambahan data gagal dilakukan', $this->cssFail), Messenger::NextRequest);
			}
			return $this->pageView;
		}
	}

	function Ubah(){
		if(isset($this->_POST['btnsimpan'])){
			$invId = $this->_POST['inv_id'];
			$ubah = $this->Obj->DoUbahInventaris($this->_POST['inv_nama'],$this->_POST['inv_pemilik'],$this->_POST['inv_jml'],$this->_POST['inv_ket'], $invId);
			if ($ubah==true) {
				Messenger::Instance()->Send('rtInventaris', 'ListLatihanKu', 'view', 'html', array($this->_POST,'<i class="fa fa-check-circle"></i><span style="margin-right:15px;"></span>Ubah data berhasil dilakukan', $this->cssDone), Messenger::NextRequest);
			} else {
				Messenger::Instance()->Send('rtInventaris', 'ListLatihanKu', 'view', 'html', array($this->_POST,'<i class="fa fa-warning"></i><span style="margin-right:15px;"></span>Ubah data gagal dilakukan', $this->cssFail), Messenger::NextRequest);
			}
			return $this->pageView;
		}
	}

	function Hapus() {
		$invId = $this->_POST['idDelete'];
		if(isset($invId)) {
			$hapus = $this->Obj->DoHapusInventaris($invId);
			if($hapus == true) {
			Messenger::Instance()->Send('rtInventaris', 'ListLatihanKu', 'view', 'html', array($this->_POST,'<i class="fa fa-check-circle"></i><span style="margin-right:15px;"></span>Hapus Data Berhasil Dilakukan', $this->cssDone), Messenger::NextRequest);
			} else {
			Messenger::Instance()->Send('rtInventaris', 'ListLatihanKu', 'view', 'html', array($this->_POST,'<i class="fa fa-warning"></i><span style="margin-right:15px;"></span>Hapus Data Gagal Dilakukan', $this->cssFail), Messenger::NextRequest);
			}
		}
		return $this->pageView;
	}

	function TambahPeminjam(){
		if(isset($this->_POST['btnsimpan'])){
			$pinjam_status="pinjam";
			//$pinjam_tgl = date("Y-m-d", strtotime($this->_POST['pinjam_tgl']));
			if ($this->_POST['pinjam_jml']<=$this->_POST['jml_sisa']) {
				$tambahPeminjam = $this->Obj->DoTambahPeminjam($this->_POST['pinjam_inv_id'],$this->_POST['pinjam_nama'],$this->_POST['pinjam_jml'],$pinjam_status,$this->_POST['pinjam_kondisi'],$this->_POST['pinjam_tgl']);
				if ($tambahPeminjam==true) {
					Messenger::Instance()->Send('rtInventaris', 'PinjamInventaris', 'view', 'html', array($this->_POST,'<i class="fa fa-check-circle"></i><span style="margin-right:15px;"></span>Penambahan data berhasil dilakukan', $this->cssDone), Messenger::NextRequest);
				} else {
					Messenger::Instance()->Send('rtInventaris', 'PinjamInventaris', 'view', 'html', array($this->_POST,'<i class="fa fa-warning"></i><span style="margin-right:15px;"></span>Penambahan data gagal dilakukan', $this->cssFail), Messenger::NextRequest);
				}
				return $this->pageView2;	
			} else {
				Messenger::Instance()->Send('rtInventaris', 'TambahPeminjam', 'view', 'html', array($this->_POST,'* Jumlah tidak boleh lebih dari '.$this->_POST['jml_sisa'], $this->cssFail), Messenger::NextRequest);
				return Dispatcher::Instance()->GetUrl('rtInventaris', 'TambahPeminjam', 'View', 'html').'&invId='.Dispatcher::Instance()->Encrypt($this->_POST['pinjam_inv_id']).'&sisaJml='.Dispatcher::Instance()->Encrypt($this->_POST['jml_sisa']);
			}
		}
	}

	function UbahPeminjam(){
		if(isset($this->_POST['btnsimpan'])){
			if ($this->_POST['pinjam_jml']<=$this->_POST['jml_max']) {
				$ubah = $this->Obj->DoUbahPeminjam($this->_POST['pinjam_nama'],$this->_POST['pinjam_jml'],$this->_POST['pinjam_status'],$this->_POST['pinjam_kondisi'], $this->_POST['pinjam_tgl'],$this->_POST['pinjam_id']);
				if ($ubah==true) {
					Messenger::Instance()->Send('rtInventaris', 'PinjamInventaris', 'view', 'html', array($this->_POST,'<i class="fa fa-check-circle"></i><span style="margin-right:15px;"></span>Ubah data berhasil dilakukan', $this->cssDone), Messenger::NextRequest);
				} else {
					Messenger::Instance()->Send('rtInventaris', 'PinjamInventaris', 'view', 'html', array($this->_POST,'<i class="fa fa-warning"></i><span style="margin-right:15px;"></span>Ubah data gagal dilakukan', $this->cssFail), Messenger::NextRequest);
				}
				return $this->pageView2;
			} else {
				Messenger::Instance()->Send('rtInventaris', 'UbahPeminjam', 'view', 'html', array($this->_POST,'* Jumlah tidak boleh lebih dari '.$this->_POST['jml_max'], $this->cssFail), Messenger::NextRequest);
				return Dispatcher::Instance()->GetUrl('rtInventaris', 'UbahPeminjam', 'View', 'html').'&pinjamId='.Dispatcher::Instance()->Encrypt($this->_POST['pinjam_id']).'&sisaJml='.Dispatcher::Instance()->Encrypt($this->_POST['jml_sisa']);
			}
			
		}
	}
}
?>