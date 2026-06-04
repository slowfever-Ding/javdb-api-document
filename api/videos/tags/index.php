<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/services/Response.php';

try {

    $pdo = require __DIR__ . '/../../../app/config/pdo.php';

    /**
     * 获取所有标签及对应影片数量
     */
    $sql = "
    SELECT
        tag,
        COUNT(DISTINCT code) AS video_count
    FROM video_tags
    GROUP BY tag
    ORDER BY
        video_count DESC,
        tag ASC
    ";

    $tags = $pdo
        ->query($sql)
        ->fetchAll(PDO::FETCH_ASSOC);

    services\Response::success(
        $tags,
        '成功'
    );

} catch (Throwable $e) {

    services\Response::error(
        $e->getMessage()
    );
}
