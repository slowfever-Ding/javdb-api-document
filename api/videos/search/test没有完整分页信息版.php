<?php

declare(strict_types=1);

/**
 * 统一 Response
 */
require_once __DIR__ . '/../../../app/services/Response.php';

try {

    /**
     * PDO
     */
    $pdo = require __DIR__ . '/../../../app/config/pdo.php';

    /**
     * 参数
     */

    // 搜索关键词
    $q = trim($_GET['q'] ?? '');

    // 类别筛选
    $tag = trim($_GET['tag'] ?? '');

    // 当前页码，默认第一页
    $page = max(
        1,
        (int)($_GET['page'] ?? 1)
    );

    // 每页数量，默认100
    $limit = (int)($_GET['limit'] ?? 100);

    /**
     * 偏移量
     */
    $offset = ($page - 1) * $limit;

    /**
     * 基础 SQL
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
        ) AS tags

    FROM videos v

    LEFT JOIN video_tags t
    ON v.code = t.code

    WHERE 1 = 1
    ";

    /**
     * SQL 参数
     */
    $params = [];

    /**
     * q 模糊搜索
     *
     * 搜索：
     * - 番号
     * - 标题
     * - 标签
     */
    if ($q !== '') {

        $sql .= "
        AND (

            v.code LIKE :q

            OR v.title LIKE :q

            OR t.tag LIKE :q

        )
        ";

        $params[':q'] = "%{$q}%";
    }

    /**
     * tag 标签筛选
     */
    if ($tag !== '') {

        $sql .= "
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
     * 排序 + 分页
     */
    $sql .= "
    GROUP BY v.code

    ORDER BY
        v.publish_date DESC,
        v.id DESC

    LIMIT :limit

    OFFSET :offset
    ";

    /**
     * 预处理
     */
    $stmt = $pdo->prepare($sql);

    /**
     * 绑定搜索参数
     */
    foreach ($params as $key => $value) {

        $stmt->bindValue(
            $key,
            $value,
            PDO::PARAM_STR
        );
    }

    /**
     * 绑定分页参数
     */
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

    /**
     * 执行 SQL
     */
    $stmt->execute();

    /**
     * 查询结果
     */
    $list = $stmt->fetchAll(
        PDO::FETCH_ASSOC
    );

    /**
     * tags 转数组
     */
    foreach ($list as &$item) {

        $item['tags'] = empty(
        $item['tags']
        )

            ? []

            : explode(
                ',',
                $item['tags']
            );
    }

    /**
     * 成功返回
     */
    services\Response::success(
        $list,
        '成功',
        ['page' => $page] // page 参数
    );

} catch (Throwable $e) {

    /**
     * 异常响应
     */
    services\Response::error($e->getMessage());
}
