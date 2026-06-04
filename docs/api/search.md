# 视频搜索 API

支持番号、标题、类别标签搜索，并可分页。

## 请求地址

```http
GET /api/videos/search/
```

## 请求参数

| 参数 | 类型 | 必填 | 默认值 | 说明                  |
|------|------|------|--------|---------------------|
| q    | string | 否 | - | 模糊搜索关键词（番号、标题、类别标签） |
| tag  | string | 否 | - | 精准筛选类别标签            |
| page | int    | 否 | 1 | 页码                  |
| limit| int    | 否 | 100 | 每页数量（最大 500）        |

## 请求示例

```http
GET /api/videos/search?q=ABP&tag=巨乳&page=1&limit=20
```

## 成功响应

```json
{
  "code": 200,
  "message": "成功",
  "page": 1,
  "limit": 20,
  "total": 128,
  "pages": 7,
  "data": [
    {
      "code": "ABP-001",
      "title": "影片标题",
      "url": "https://example.com/video",
      "cover": "https://example.com/cover.jpg",
      "publish_date": "2025-01-01",
      "tags": ["人妻","巨乳"]
    }
  ]
}
```

## 失败响应

```json
{
  "code": 500,
  "message": "错误信息",
  "data": null
}
```

## 说明

::: tip
q 参数会同时匹配番号、标题和类别标签。
:::

::: info
支持通过 page 与 limit 进行分页查询。
:::

::: warning
limit 最大值为 500，超出将自动限制为 500。
:::