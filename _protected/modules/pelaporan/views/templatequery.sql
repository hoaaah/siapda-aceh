SET @p_tahun = 2014;
SET @p_bulan = 201412;
SET @p_perwakilan = 1;
SET @p_pemda = '%';
SELECT a.id, a.name, b.jumlah_desa AS jumlah_desa_alokasi, b.nilai AS nilai_alokasi, c.jumlah_desa AS jumlah_desa_rkud, c.nilai AS nilai_rkud, d.jumlah_desa AS jumlah_desa_rkudesa, d.nilai AS nilai_rkudesa, e.jumlah_desa_implementasi, e.kompilasi
FROM ref_pemda a LEFT JOIN
    -- part alokasi dana desa
    (
        SELECT
        a.pemda_id, a.jumlah_desa, a.nilai
        FROM ldanadesa_alokasi a
        WHERE a.bulan <= @p_bulan AND a.tahun = @p_tahun AND a.perwakilan_id LIKE @p_perwakilan AND a.pemda_id LIKE @p_pemda AND a.pendapatan_desa_id = 2 AND
        a.bulan = (SELECT MAX(b.bulan) FROM ldanadesa_alokasi b WHERE b.pemda_id = a.pemda_id AND b.tahun = @p_tahun AND b.pendapatan_desa_id = 2)        
    ) b ON a.id = b.pemda_id LEFT JOIN
    -- part penyaluran ke RKUD
    (
        SELECT
        a.pemda_id, SUM(a.jumlah_desa) AS jumlah_desa, SUM(a.nilai) AS nilai
        FROM ldanadesa_penyaluran_rkud a
        WHERE a.bulan <= @p_bulan AND a.tahun = @p_tahun AND a.perwakilan_id LIKE @p_perwakilan AND a.pemda_id LIKE @p_pemda AND a.pendapatan_desa_id = 2   
        GROUP BY a.pemda_id  
    ) c ON a.id = c.pemda_id LEFT JOIN
    -- part penyaluran ke RKUD
    (
        SELECT
        a.pemda_id, SUM(a.jumlah_desa) AS jumlah_desa, SUM(a.nilai) AS nilai
        FROM ldanadesa_penyaluran_rkudesa a
        WHERE a.bulan <= @p_bulan AND a.tahun = @p_tahun AND a.perwakilan_id LIKE @p_perwakilan AND a.pemda_id LIKE @p_pemda AND a.pendapatan_desa_id = 2   
        GROUP BY a.pemda_id  
    ) d ON a.id = d.pemda_id LEFT JOIN
    -- part alokasi dana desa
    (
        SELECT
        a.pemda_id, a.jumlah_desa_implementasi, a.kompilasi
        FROM ldanadesa_siskeudes a
        WHERE a.bulan <= @p_bulan AND a.perwakilan_id LIKE @p_perwakilan AND a.pemda_id LIKE @p_pemda  AND
        a.bulan = (SELECT MAX(b.bulan) FROM ldanadesa_siskeudes b WHERE b.pemda_id = a.pemda_id)        
    ) e ON a.id = e.pemda_id 
WHERE a.id LIKE @p_pemda AND a.perwakilan_id LIKE @p_perwakilan
ORDER BY a.id