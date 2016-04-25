<?php
$sql['get_sekolah'] = "
SELECT * FROM magang_sekolah
WHERE sekolahNama LIKE '%s'
OR sekolahAlamat LIKE '%s'
ORDER BY sekolahNama ASC
LIMIT %s,%s
";

$sql['get_count_sekolah'] = "
SELECT
	COUNT(sekolahId) AS total
FROM magang_sekolah
WHERE sekolahNama LIKE '%s'
OR sekolahAlamat LIKE '%s'
";

$sql['do_tambah_sekolah']="
INSERT INTO magang_sekolah (sekolahNama,sekolahAlamat,sekolahEmail,sekolahTelp)
VALUES ('%s','%s','%s','%s');
";
?>