# 随机推荐 API

返回随机视频，并提供相关推荐内容。

## 请求地址

```http
GET /api/videos/random/
```

## 请求参数

| 参数   | 类型 | 必填 | 默认值 | 说明 |
|--------|------|------|--------|------|
| related| int  | 否   | 5      | 相关推荐数量（最大 20） |

## 请求示例

```http
GET /api/videos/random?related=5
```

## 成功响应

```json
{
  "code": 200,
  "message": "成功",
  "data": {
    "video": {
      "code": "ABP-001",
      "title": "影片标题",
      "url": "https://example.com/video",
      "cover": "https://example.com/cover.jpg",
      "publish_date": "2025-01-01",
      "tags": ["人妻","巨乳"]
    },
    "related": [
      {
        "code": "ABP-002",
        "title": "相关推荐标题",
        "url": "https://example.com/video2",
        "cover": "https://example.com/cover2.jpg",
        "publish_date": "2025-02-01",
        "tags": ["人妻","巨乳"]
      }
    ]
  }
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
related 参数用于限制返回的相关推荐数量，默认 5 条。
:::

::: info
随机视频不重复当前视频。
:::

::: warning
related 最大值为 20。
:::