<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../app/services/Response.php';

try {

    $pdo = require __DIR__ . '/../../../app/config/pdo.php';

    /**
     * 搜索关键词
     * 支持：
     * - 番号
     * - 标题
     * - 标签
     */
    $q = trim($_GET['q'] ?? '');

    /**
     * 标签类别筛选
     */
    $tag = trim($_GET['tag'] ?? '');

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
     * SQL绑定参数
     */
    $params = [];

    /**
     * 主查询SQL
     */
    $baseSql = "
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
    ";

    /**
     * 总数统计SQL
     */
    $countBaseSql = "
    SELECT COUNT(DISTINCT v.code)
    FROM videos v
    LEFT JOIN video_tags t
        ON v.code = t.code
    ";

    /**
     * 公共查询条件
     */
    $where = " WHERE 1 = 1 ";

    /**
     * 关键词搜索
     *
     * 支持：
     * - 番号
     * - 标题
     * - 标签
     */
    if ($q !== '') {

        $where .= "
        AND (
            v.code LIKE :q
            OR v.title LIKE :q
            OR EXISTS (
                SELECT 1
                FROM video_tags vt
                WHERE vt.code = v.code
                AND vt.tag LIKE :q
            )
        )
        ";

        $params[':q'] = "%{$q}%";
    }

    /**
     * 标签类别筛选
     */
    if ($tag !== '') {

        $where .= "
        AND EXISTS (
            SELECT 1
            FROM video_tags vt
            WHERE vt.code = v.code
            AND vt.tag LIKE :tag
        )
        ";

        $params[':tag'] = "%{$tag}%";
    }

    /**
     * 统计总记录数
     */
    $countSql = $countBaseSql . $where;

    /**
     * 主查询SQL
     */
    $sql = $baseSql . $where . "
    GROUP BY v.code
    ORDER BY
        v.publish_date DESC,
        v.id DESC
    LIMIT :limit
    OFFSET :offset
    ";

    /**
     * 查询总数
     */
    $countStmt = $pdo->prepare($countSql);

    foreach ($params as $key => $value) {

        $countStmt->bindValue(
            $key,
            $value,
            PDO::PARAM_STR
        );
    }

    $countStmt->execute();

    $total = (int)$countStmt->fetchColumn();

    /**
     * 总页数
     */
    $pages = (int)ceil($total / $limit);

    /**
     * 查询数据
     */
    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {

        $stmt->bindValue(
            $key,
            $value,
            PDO::PARAM_STR
        );
    }

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

    $list = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
