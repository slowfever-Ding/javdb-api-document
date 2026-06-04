<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/services/Response.php';

try {

    $pdo = require __DIR__ . '/../../../app/config/pdo.php';

    /**
     * 当前页码
     */
    $page = max(
        1,
        (int)($_GET['page'] ?? 1)
    );

    /**
     * 每页数量
     * 防止恶意请求超大分页
     */
    $limit = max(
        1,
        min(
            500,
            (int)($_GET['limit'] ?? 100)
        )
    );

    /**
     * 分页偏移量
     */
    $offset = ($page - 1) * $limit;

    /**
     * 查询影片总数
     */
    $total = (int)$pdo
        ->query(
            'SELECT COUNT(DISTINCT code) FROM videos'
        )
        ->fetchColumn();

    /**
     * 总页数
     */
    $pages = (int)ceil(
        $total / $limit
    );

    /**
     * 视频列表
     */
    $sql = "
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
    ORDER BY
        v.publish_date DESC,
        v.id DESC
    LIMIT :limit
    OFFSET :offset
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(
        ':limit',
        $limit,
        PDO::PARAM_INT
    );

    $stmt->bindValue(
        ':offset',
        $offset,
        PDO::PARAM_INT
    );

    $stmt->execute();

    $list = $stmt->fetchAll(
        PDO::FETCH_ASSOC
    );

    /**
     * 标签类别字符串转数组
     */
    foreach ($list as &$item) {

        $item['tags'] = empty(
        $item['tags']
        )
            ? []
            : explode(
                ', ',
                $item['tags']
            );
    }

    unset($item);

    services\Response::success(
        $list,
        '成功',
        [
            // 当前页码
            'page'  => $page,
            // 每页返回数量（动态）
            'limit' => count($list),
            // 返回数据总量
            'total' => $total,
            // 返回总页码
            'pages' => $pages
        ]
    );

} catch (Throwable $e) {

    services\Response::error(
        $e->getMessage()
    );
}
