import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  base: '/',
  title: "JavDB API",
  description: "JavDB 影视数据接口开发文档",
  lang: 'zh-CN',

  lastUpdated: true,
  cleanUrls: true,

  locales: {
    root: {
      label: '简体中文',
      lang: 'zh-CN',
      themeConfig: {
        outlineTitle: '页面目录',
        returnToTopLabel: '返回顶部',
      }
    }
  },
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: '首页', link: '/' },
      { text: '接口文档', link: '/api/list' }
    ],

    sidebar: {
      '/api/': [
        {
          text: 'API 文档',
          items: [
            {text: '视频列表', link: '/api/list'},
            {text: '视频搜索', link: '/api/search'},
            {text: '标签列表', link: '/api/tags'},
            {text: '随机推荐', link: '/api/random'}
          ]
        }
      ]
    },

    socialLinks: [
      { icon: 'github', link: 'https://github.com/vuejs/vitepress' }
    ],

    // footer: {
    //   message: 'Released under the MIT License.',
    //   copyright: 'Copyright © 2019-present Evan You'
    // }
  }
})
