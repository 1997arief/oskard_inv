<?php
/**
* @author Arief M. Ikhsan
*/
class Sekolah extends Database
{
	protected $mSqlFile;

	function __construct($connectionNumber=0)
	{
		$this->mSqlFile = 'module/data_sekolah/business/mysqlt/sekolah.sql.php';
			parent::__construct($connectionNumber);
	}

	function GetSekolah($textCariNama,$textCariAlamat,$startRec, $itemViewed) {
        $result = $this->Open($this->mSqlQueries['get_sekolah'], array('%'.$textCariNama.'%','%'.$textCariAlamat.'%',$startRec, $itemViewed));
        return $result;
    }
    
    function GetCountSekolah($textCariNama,$textCariAlamat) {
		$result = $this->Open($this->mSqlQueries['get_count_sekolah'], array('%'.$textCariNama.'%','%'.$textCariAlamat.'%'));
		return $result[0]['total'];
	}

	function DoTambahSekolah($sekolahNama,$sekolahAlamat,$sekolahEmail,$sekolahTelp) {
		$result = $this->Execute($this->mSqlQueries['do_tambah_sekolah'], array($sekolahNama,$sekolahAlamat,$sekolahEmail,$sekolahTelp));
		return $result;
	}
}
?>