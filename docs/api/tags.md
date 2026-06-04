# 标签列表 API

获取所有标签及对应视频数量。

## 请求地址

```http
GET /api/videos/tags/
```

## 请求参数

无

## 成功响应

```json
{
  "code": 200,
  "message": "成功",
  "data": [
    { "tag": "巨乳", "count": 1258 },
    { "tag": "人妻", "count": 1032 },
    { "tag": "制服", "count": 865 }
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
返回的标签按视频数量降序排列。
:::