<?php
$sql['get_data_inventaris'] = "
SELECT * FROM tb_inventaris
ORDER BY inv_nama ASC
LIMIT %s, %s
";
$sql['do_tambah_inventaris']="
INSERT INTO tb_inventaris (inv_nama,inv_pemilik,inv_jml,inv_ket)
VALUES ('%s','%s','%s','%s');
";
$sql['get_inventaris_by_id']= "
SELECT * FROM tb_inventaris
WHERE inv_id = '%s'
";
$sql['do_ubah_inventaris']="
UPDATE tb_inventaris
SET
	inv_nama = '%s',
	inv_pemilik = '%s',
	inv_jml = '%s',
	inv_ket = '%s'
WHERE inv_id = '%s';
";
$sql['do_hapus_inventaris']="
DELETE from tb_inventaris
WHERE inv_id='%s';
";
$sql['do_hapus_pinjam']="
DELETE from tb_pinjam
WHERE pinjam_inv_id='%s';
";
$sql['get_hitung_inventaris'] = "
SELECT
	COUNT(inv_id) AS total
FROM tb_inventaris
";

$sql['get_cari_inventaris'] = "
SELECT * FROM tb_inventaris
WHERE inv_nama LIKE '%s'
AND inv_pemilik LIKE '%s'
ORDER BY inv_nama ASC
LIMIT %s, %s
";
$sql['get_hitung_inventaris_cari'] = "
SELECT
	COUNT(inv_id) AS total
FROM tb_inventaris
WHERE inv_nama LIKE '%s'
AND inv_pemilik LIKE '%s'
";

$sql['get_jumlah_pinjam'] = "
SELECT SUM(pinjam_jml) AS jumlah
FROM tb_pinjam
WHERE pinjam_inv_id = '%s'
AND pinjam_status='pinjam'
";

$sql['get_peminjam'] = "
SELECT * FROM tb_pinjam
WHERE pinjam_inv_id = '%s'
ORDER BY  CASE pinjam_status WHEN 'pinjam' THEN 1 ELSE 2 END,
 pinjam_tgl DESC, pinjam_nama ASC
LIMIT %s,%s
";

$sql['get_peminjam_by_id']= "
SELECT * FROM tb_pinjam
WHERE pinjam_id = '%s'
";

$sql['get_hitung_peminjam'] = "
SELECT 
	COUNT(pinjam_id) AS total
FROM tb_pinjam
WHERE pinjam_inv_id = '%s'
";

$sql['do_tambah_peminjam']="
INSERT INTO tb_pinjam (pinjam_inv_id,pinjam_nama,pinjam_jml,pinjam_status,pinjam_kondisi,pinjam_tgl)
VALUES ('%s','%s','%s','%s','%s','%s');
";
$sql['do_ubah_peminjam']="
UPDATE tb_pinjam
SET
	pinjam_nama = '%s',
	pinjam_jml = '%s',
	pinjam_status = '%s',
	pinjam_kondisi = '%s',
	pinjam_tgl = '%s'
WHERE pinjam_id = '%s';
";

?>