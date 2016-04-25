<?php
$sql['get_data_pinjam'] = "
SELECT * FROM tb_pinjam
INNER JOIN tb_inventaris
ON tb_pinjam.pinjam_inv_id=tb_inventaris.inv_id
WHERE pinjam_nama LIKE '%s'
AND pinjam_status LIKE '%s'
AND pinjam_kondisi LIKE '%s'
ORDER BY pinjam_nama ASC
LIMIT %s,%s
";

$sql['get_pinjam_by_id'] = "
SELECT * FROM tb_pinjam
INNER JOIN tb_inventaris
ON tb_pinjam.pinjam_inv_id=tb_inventaris.inv_id
WHERE pinjam_id='%s'
";

$sql['get_jumlah_pinjam'] = "
SELECT SUM(pinjam_jml) AS jumlah
FROM tb_pinjam
WHERE pinjam_inv_id = '%s'
AND pinjam_status='pinjam'
";

$sql['get_count_pinjam'] = "
SELECT
	COUNT(pinjam_id) AS total
FROM tb_pinjam
WHERE pinjam_nama LIKE '%s'
AND pinjam_status LIKE '%s'
AND pinjam_kondisi LIKE '%s'
";

$sql['do_hapus_pinjam']="
DELETE from tb_pinjam
WHERE pinjam_id='%s';
";

$sql['do_ubah_pinjam']="
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