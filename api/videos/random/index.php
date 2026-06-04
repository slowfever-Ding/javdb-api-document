<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/services/Response.php';

try {

    $pdo = require __DIR__ . '/../../../app/config/pdo.php';

    /**
     * 相关推荐数量
     */
    $relatedCount = max(
        1,
        min(
            20,
            (int)($_GET['related'] ?? 5)
        )
    );

    /**
     * 随机获取一部影片
     */
    $randomSql = "
    SELECT
        v.code,
        v.title,
        v.url,
        v.cover,
        v.publish_date,
        GROUP_CONCAT(
            DISTINCT t.tag
            ORDER BY t.tag
            SEPARATOR ', '
        ) AS tags
    FROM videos v
    LEFT JOIN video_tags t
        ON v.code = t.code
    GROUP BY v.code
    ORDER BY RAND()
    LIMIT 1
    ";

    $video = $pdo
        ->query($randomSql)
        ->fetch(PDO::FETCH_ASSOC);

    /**
     * 没有影片数据
     */
    if (!$video) {

        services\Response::error(
            '暂无视频'
        );
    }

    /**
     * 标签转数组
     */
    $video['tags'] = empty($video['tags'])
        ? []
        : explode(
            ', ',
            $video['tags']
        );

    /**
     * 没有标签则不查询相关推荐
     */
    if (empty($video['tags'])) {

        services\Response::success(
            [
                'video' => $video,
                'related' => []
            ],
            '成功'
        );
    }

    /**
     * 构造 IN (?, ?, ?)
     */
    $placeholders = implode(
        ',',
        array_fill(
            0,
            count($video['tags']),
            '?'
        )
    );

    /**
     * 查询相关推荐
     */
    $relatedSql = "
    SELECT
        v.code,
        v.title,
        v.url,
        v.cover,
        v.publish_date,
        GROUP_CONCAT(
            DISTINCT t.tag
            ORDER BY t.tag
            SEPARATOR ', '
        ) AS tags
    FROM videos v
    LEFT JOIN video_tags t
        ON v.code = t.code
    WHERE
        t.tag IN ($placeholders)
        AND v.code != ?
    GROUP BY v.code
    ORDER BY RAND()
    LIMIT ?
    ";

    $relatedStmt = $pdo->prepare(
        $relatedSql
    );

    /**
     * 绑定标签参数
     */
    $index = 1;

    foreach ($video['tags'] as $tag) {

        $relatedStmt->bindValue(
            $index++,
            $tag,
            PDO::PARAM_STR
        );
    }

    /**
     * 排除当前影片
     */
    $relatedStmt->bindValue(
        $index++,
        $video['code'],
        PDO::PARAM_STR
    );

    /**
     * 相关推荐数量
     */
    $relatedStmt->bindValue(
        $index,
        $relatedCount,
        PDO::PARAM_INT
    );

    $relatedStmt->execute();

    $related = $relatedStmt->fetchAll(
        PDO::FETCH_ASSOC
    );

    /**
     * 标签转数组
     */
    foreach ($related as &$item) {

        $item['tags'] = empty($item['tags'])
            ? []
            : explode(
                ', ',
                $item['tags']
            );
    }

    unset($item);

    services\Response::success(
        [
            'video' => $video,
            'related' => $related
        ],
        '成功'
    );

} catch (Throwable $e) {

    services\Response::error(
        $e->getMessage()
    );
}
