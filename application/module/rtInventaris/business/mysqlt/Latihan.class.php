<?php
/**
* 
*/
class Latihan extends Database
{
	protected $mSqlFile;

	function __construct($connectionNumber=0)
	{
		$this->mSqlFile = 'module/rtInventaris/business/mysqlt/latihan.sql.php';
			parent::__construct($connectionNumber);
	}

	function GetDataInventaris($startRec, $itemViewed) {
		$result = $this->Open($this->mSqlQueries['get_data_inventaris'], array($startRec, $itemViewed));
		//get_data_inventaris dari latihan.sql.php
		return $result;
	}
	function GetCariInventaris($cariNama,$cariPemilik,$startRec, $itemViewed) {
		$result = $this->Open($this->mSqlQueries['get_cari_inventaris'], array('%'.$cariNama.'%','%'.$cariPemilik.'%',$startRec, $itemViewed));
		return $result;
	}
	function DoTambahInventaris($invNama,$invPemilik,$invJml,$invKet) {
		$result = $this->Execute($this->mSqlQueries['do_tambah_inventaris'], array($invNama,$invPemilik,$invJml,$invKet));
		return $result;
	}
	function GetInventarisById($invId) {
		$result = $this->Open($this->mSqlQueries['get_inventaris_by_id'], array($invId));
		return $result;
	}
	
	function DoUbahInventaris($invNama,$invPemilik,$invJml,$invKet,$invId) {
		$result = $this->Execute($this->mSqlQueries['do_ubah_inventaris'], array($invNama,$invPemilik,$invJml,$invKet,$invId));
		return $result;
	}
	function DoHapusInventaris($invId) {
		$result = $this->Execute($this->mSqlQueries['do_hapus_inventaris'], array($invId));
		$result = $this->Execute($this->mSqlQueries['do_hapus_pinjam'], array($invId));
		return $result;
	}
	function GetHitungInventaris() {
		$result = $this->Open($this->mSqlQueries['get_hitung_inventaris'], array());
		return $result[0]['total'];
	}
	function GetHitungInventarisCari($cariNama,$cariPemilik) {
		$result = $this->Open($this->mSqlQueries['get_hitung_inventaris_cari'], array('%'.$cariNama.'%','%'.$cariPemilik.'%'));
		return $result[0]['total'];
	}
	function GetJumlahPinjam($invId){
		$result = $this->Open($this->mSqlQueries['get_jumlah_pinjam'], array($invId));
		return $result;
	}
	function GetPeminjam($invId,$startRec, $itemViewed){
		$result = $this->Open($this->mSqlQueries['get_peminjam'], array($invId,$startRec, $itemViewed));
		return $result;
	}
	function GetPeminjamById($pinjamId) {
		$result = $this->Open($this->mSqlQueries['get_peminjam_by_id'], array($pinjamId));
		return $result;
	}
	function GetHitungPeminjam($invId){
		$result = $this->Open($this->mSqlQueries['get_hitung_peminjam'], array($invId));
		return $result[0]['total'];
	}
	function DoTambahPeminjam($pinjamInvId,$pinjamNama,$pinjamJml,$pinjamStatus,$pinjamKondisi,$pinjamTgl){
		$result = $this->Execute($this->mSqlQueries['do_tambah_peminjam'], array($pinjamInvId,$pinjamNama,$pinjamJml,$pinjamStatus,$pinjamKondisi,$pinjamTgl));
		return $result;
	}
	function DoUbahPeminjam($pinjamNama,$pinjamJml,$pinjamStatus,$pinjamKondisi,$pinjamTgl,$pinjamId){
		$result = $this->Execute($this->mSqlQueries['do_ubah_peminjam'], array($pinjamNama,$pinjamJml,$pinjamStatus,$pinjamKondisi,$pinjamTgl,$pinjamId));
		return $result;
	}
}
?>