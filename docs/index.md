---
layout: home

hero:
  name: "JavDB API"
  text: "轻量级、高性能的视频数据接口"
  tagline: 提供视频列表、搜索、标签统计和随机推荐接口
  image:
    src: https://github.com/slowfever-Ding/javdb-api-document/blob/main/docs/logo.png?raw=true
    alt: JavDB API
  actions:
    - theme: brand
      text: 快速开始
      link: /api/list
    - theme: alt
      text: GitHub
      link: https://github.com/slowfever-Ding/

features:
  - title: 视频列表
    details: 支持分页查询，按发行日期倒序返回影片数据。

  - title: 视频搜索
    details: 支持番号、标题、标签搜索。

  - title: 标签统计
    details: 获取全部标签及影片数量。

  - title: 随机推荐
    details: 随机返回影片及相关推荐。

  - title: JSON API
    details: 统一响应格式，前后端都可直接使用。

  - title: 高性能
    details: PDO 预处理 + MySQL 索引优化。
---