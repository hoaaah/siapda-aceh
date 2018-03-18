SET @p_tahun = 2014;
SET @p_perwakilan = 1;
SET @p_pemda = '%';
SELECT a.id, a.name,
CASE 
	WHEN b.opini_0 IS NULL THEN (b.opini_1 + b.opini_2 + b.opini_3)
	ELSE (b.opini_0 + b.opini_1 + b.opini_2)
END AS opini, IFNULL(c.sakip, 0) AS sakip, IFNULL(d.lppd, 0) AS lppd, IFNULL(e.spip, 0) AS spip, IFNULL(f.kasus, 0) AS kasus, IFNULL(g.pilkada, 0) AS pilkada
FROM ref_pemda a LEFT JOIN
    -- part opini
	(
		SELECT b.pemda_id, 
		SUM(b.opini_tahun_0) AS opini_0,
		SUM(b.opini_tahun_1) AS opini_1,
		SUM(b.opini_tahun_2) AS opini_2,
		SUM(b.opini_tahun_3) AS opini_3
		FROM
		(
			SELECT pemda_id, 1 AS opini_tahun_0, 0 AS opini_tahun_1, 0 AS opini_tahun_2, 0 AS opini_tahun_3 
			FROM llkpd WHERE bulan LIKE CONCAT(@p_tahun ,'%') AND opini_id = 'B4' AND pemda_id LIKE @p_pemda AND perwakilan_id LIKE @p_perwakilan
            GROUP BY pemda_id
			UNION ALL
			SELECT pemda_id, 0 AS opini_tahun_0, 1 AS opini_tahun_1, 0 AS opini_tahun_2, 0 AS opini_tahun_3 
			FROM llkpd WHERE bulan LIKE CONCAT((@p_tahun-1) ,'%') AND opini_id = 'B4' AND pemda_id LIKE @p_pemda AND perwakilan_id LIKE @p_perwakilan
            GROUP BY pemda_id
			UNION ALL
			SELECT pemda_id, 0 AS opini_tahun_0, 0 AS opini_tahun_1, 1 AS opini_tahun_2, 0 AS opini_tahun_3 
			FROM llkpd WHERE bulan LIKE CONCAT((@p_tahun-2) ,'%') AND opini_id = 'B4' AND pemda_id LIKE @p_pemda AND perwakilan_id LIKE @p_perwakilan
            GROUP BY pemda_id
			UNION ALL
			SELECT pemda_id, 0 AS opini_tahun_0, 0 AS opini_tahun_1, 0 AS opini_tahun_2, 1 AS opini_tahun_3 
			FROM llkpd WHERE bulan LIKE CONCAT((@p_tahun-3) ,'%') AND opini_id = 'B4' AND pemda_id LIKE @p_pemda AND perwakilan_id LIKE @p_perwakilan
            GROUP BY pemda_id
		) b GROUP BY b.pemda_id
	)b ON a.id = b.pemda_id LEFT JOIN
    -- part sakip
    (
        SELECT a.pemda_id, a.sakip FROM
        (
            SELECT pemda_id, 2 AS sakip 
            FROM levals WHERE bulan LIKE CONCAT(@p_tahun ,'%') AND kat_sakip >= 4 AND pemda_id LIKE @p_pemda AND perwakilan_id LIKE @p_perwakilan ORDER BY id DESC
        ) a GROUP BY a.pemda_id
        UNION ALL
        SELECT a.pemda_id, a.sakip FROM
        (
            SELECT pemda_id, 1 AS sakip 
            FROM levals WHERE bulan LIKE CONCAT(@p_tahun ,'%') AND kat_sakip = 3 AND pemda_id LIKE @p_pemda AND perwakilan_id LIKE @p_perwakilan ORDER BY id DESC
        ) a GROUP BY a.pemda_id
    ) c ON a.id = c.pemda_id LEFT JOIN
    -- part lppd
    (
        SELECT a.pemda_id, a.lppd FROM
        (
            SELECT pemda_id, 3 AS lppd
            FROM levals WHERE bulan LIKE CONCAT(@p_tahun ,'%') AND kat_lppd >= 3 AND pemda_id LIKE @p_pemda AND perwakilan_id LIKE @p_perwakilan ORDER BY id DESC
        ) a GROUP BY a.pemda_id        
    ) d ON a.id = d.pemda_id LEFT JOIN
    -- part spip
    (
        SELECT a.pemda_id, a.spip FROM
        (
            SELECT pemda_id, 1 AS spip
            FROM levals WHERE bulan LIKE CONCAT(@p_tahun ,'%') AND kat_spip >= 2 AND pemda_id LIKE @p_pemda AND perwakilan_id LIKE @p_perwakilan ORDER BY id DESC
        ) a GROUP BY a.pemda_id
    ) e ON a.id = e.pemda_id LEFT JOIN 
    -- part kasus
    (
        SELECT pemda_id, -COUNT(id) AS kasus FROM lkasus 
        WHERE (bulan LIKE CONCAT(@p_tahun ,'%') OR bulan LIKE CONCAT((@p_tahun-1) ,'%') OR bulan LIKE CONCAT((@p_tahun-2) ,'%')) AND pemda_id LIKE @p_pemda AND perwakilan_id LIKE @p_perwakilan
        GROUP BY pemda_id
    ) f ON a.id = f.pemda_id LEFT JOIN 
    -- part tahun politik
    (
        SELECT pemda_id, -COUNT(id) AS pilkada FROM lprofil_pemda
        WHERE bulan LIKE CONCAT(@p_tahun ,'%') AND tahun_politik = 1 AND pemda_id LIKE @p_pemda AND perwakilan_id LIKE @p_perwakilan
        GROUP BY pemda_id
    ) g ON a.id = g.pemda_id
WHERE a.id LIKE @p_pemda AND a.perwakilan_id LIKE @p_perwakilan
ORDER BY a.id