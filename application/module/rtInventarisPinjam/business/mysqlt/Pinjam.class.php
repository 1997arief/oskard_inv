<?php
/**
* @author Arief M. Ikhsan
* @license http://gtfw.gamatechno.com/#license
**/
class Pinjam extends Database
{
	protected $mSqlFile;

	function __construct($connectionNumber=0)
	{
		$this->mSqlFile = 'module/rtInventarisPinjam/business/mysqlt/pinjam.sql.php';
			parent::__construct($connectionNumber);
	}

	function GetDataPinjam($cariNama,$cariStatus,$cariKondisi,$startRec, $itemViewed){
		$result = $this->Open($this->mSqlQueries['get_data_pinjam'], array('%'.$cariNama.'%','%'.$cariStatus.'%','%'.$cariKondisi.'%',$startRec, $itemViewed));
		return $result;
	}

	function GetPinjamById($pinjamId){
		$result = $this->Open($this->mSqlQueries['get_pinjam_by_id'], array($pinjamId));
		return $result;
	}

	function GetJumlahPinjam($invId){
		$result = $this->Open($this->mSqlQueries['get_jumlah_pinjam'], array($invId));
		return $result;
	}

	function GetCountPinjam($cariNama,$cariStatus,$cariKondisi) {
		$result = $this->Open($this->mSqlQueries['get_count_pinjam'], array('%'.$cariNama.'%','%'.$cariStatus.'%','%'.$cariKondisi.'%'));
		return $result[0]['total'];
	}

	function DoHapusPinjam($pinjamId) {
        $result = $this->Execute($this->mSqlQueries['do_hapus_pinjam'], array($pinjamId));
        return $result;
	}
	function DoUbahPinjam($pinjamNama,$pinjamJml,$pinjamStatus,$pinjamKondisi,$pinjamTgl,$pinjamId){
		$result = $this->Execute($this->mSqlQueries['do_ubah_pinjam'], array($pinjamNama,$pinjamJml,$pinjamStatus,$pinjamKondisi,$pinjamTgl,$pinjamId));
        return $result;
	}
}
?>