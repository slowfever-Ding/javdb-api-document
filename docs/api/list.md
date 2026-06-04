# 视频列表 API

获取视频列表数据，支持分页查询。

## 请求地址

```http
GET /api/videos/list/
```

## 请求参数

| 参数 | 类型 | 必填 | 默认值 | 说明          |
|------|------|------|--------|-------------|
| page | int | 否 | 1 | 页码          |
| limit | int | 否 | 100 | 每页数量，最大 500 |

## 请求示例

```http
GET /api/videos/list/?page=1&limit=20
```

## 成功响应

```json
{
  "code": 200,
  "message": "成功",
  "page": 1,
  "limit": 20,
  "total": 3371,
  "pages": 169,
  "data": [
    {
      "code": "ABP-123",
      "title": "影片标题",
      "url": "https://example.com/video",
      "cover": "https://example.com/cover.jpg",
      "publish_date": "2025-01-01",
      "tags": [
        "剧情",
        "无码"
      ]
    }
  ]
}
```

## 响应字段

| 字段 | 类型 | 说明 |
|--------|--------|--------|
| code | int | 状态码 |
| message | string | 响应消息 |
| page | int | 当前页 |
| limit | int | 当前返回数量 |
| total | int | 数据总数 |
| pages | int | 总页数 |
| data | array | 视频列表 |

## 视频对象

| 字段 | 类型 | 说明 |
|--------|--------|--------|
| code | string | 番号 |
| title | string | 标题 |
| url | string | 详情页 |
| cover | string | 封面 |
| publish_date | string | 发行日期 |
| tags | array | 标签列表 |

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
列表按发行日期倒序排序。
:::

::: info
支持通过 page 与 limit 进行分页查询。
:::

::: warning
limit 最大值为 500，超出将自动限制为 500。
:::