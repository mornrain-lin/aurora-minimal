# Aurora Minimal

极简工具 / 内容双模 WordPress 主题。留白大气、细线条、无阴影或极轻阴影，一套主题同时兼顾**工具落地页**与**内容站**两种场景。

- 文本域：`aurora-minimal`
- 版本：1.0.0
- 需要 WordPress：6.0 及以上
- 需要 PHP：7.4 及以上
- 测试到：WordPress 6.6
- 许可证：MIT

---

## 简介

Aurora Minimal 面向两类站点：一类是**工具集合 / SaaS 落地页**（首页是功能介绍，文章很少）；另一类是**个人博客 / 小型内容站**（首页是文章流）。传统的双主题策略要么风格不统一，要么维护两套代码，本主题用一个「极简模式」开关解决：

- **工具模式**（极简模式开启）：侧栏隐藏，区块留白放大，页面聚焦单一任务。
- **内容模式**（极简模式关闭）：文章流 + 侧栏 Widget + 相关阅读，完整博客体验。

主题零外部资源依赖：不加载 Google Fonts、不引用任何 CDN、不请求外部 API。字体使用系统字体栈，图标全部为内联 SVG。

## 特性

### 双模布局

- 独立的「极简模式」开关，隐藏侧栏并放大上下留白。
- 可在 Customizer 中随时切换，也可以用过滤器按页面粒度控制：

```php
// 只在文章页关闭极简模式
add_filter( 'aurora_minimal_minimal_mode', function ( $enabled ) {
	return is_singular( 'post' ) ? false : $enabled;
} );
```

### 配色与排版

- `theme.json` 提供块编辑器配色，Customizer 提供细粒度控制，两条通道互不冲突。
- 自定义强调色、自定义背景色、内容最大宽度（720 - 1600px）均可配置，实时输出为 CSS 变量。

### 阅读体验

- 文章页原生 JS 阅读进度条（页脚 defer 加载，不阻塞首屏），同步 `aria-valuenow`。
- 三种归档布局：纵向列表、横向图文、网格卡片。
- 面包屑导航（自动处理层级页面与自定义分类法）。
- 相关文章推荐（同分类，排除当前文章）。
- 上一篇 / 下一篇导航。
- 回到顶部按钮。

### 工程细节

- 完整模板层级，可覆盖任意模板。
- Widget 区域：侧栏 2 个 + 页脚 3 个，支持 Customizer 部件即时刷新。
- 支持 `title-tag` / `post-thumbnails` / `html5` / `automatic-feed-links` / `custom-logo` / `customize-selective-refresh-widgets` / `responsive-embeds` / `align-wide` / `editor-style`。
- 声明 `woocommerce` 支持（WooCommerce 不会因缺少函数报错，但未做模板适配）。
- 打印友好样式、`prefers-reduced-motion` 降级、`:focus-visible` 键盘焦点样式。
- PHP 7.4 兼容，未使用 PHP 8 独有语法。

## 截图说明

WordPress 后台的主题截图要求尺寸为 **1200 × 900 像素**（PNG 格式）。

- **文件路径**：`screenshot.png`（放在主题根目录）
- **推荐内容**：以首页为主视角，左上角展示带 Logo 的细线条站点头部与主导航，中间是极简模式下的文章流（无侧栏），右下角展示一篇文章的标题与元信息，整体色调为白底 + 细灰线 + 单一强调色。
- **建议分辨率**：1200 × 900 px，24 位色 PNG，文件体积控制在 200KB 以内。

> 本仓库为纯代码分发，未附带二进制截图文件；安装后请按上述说明自行补充 `screenshot.png`。

## 安装

### 从后台安装（推荐）

1. 把主题目录打包成 `aurora-minimal.zip`，确保压缩包内层是 `aurora-minimal/` 目录。
2. 进入 **外观 → 主题 → 添加新主题 → 上传主题**，选择 zip 文件。
3. 点击 **启用**。

### 通过 FTP / SSH 上传

1. 将 `aurora-minimal` 目录上传到 `wp-content/themes/`。
2. 进入 **外观 → 主题**，点击 **启用**。

### 本地开发

```bash
git clone https://github.com/mornrain-lin/aurora-minimal.git
cd aurora-minimal
php -l functions.php   # 语法自检
```

## 主题配置项

进入 **外观 → 自定义 → 主题设置**。

### 外观与配色

| 配置项 | 类型 | 默认值 | 说明 |
| --- | --- | --- | --- |
| 强调色 | 颜色选择器 | `#2b6cb0` | 链接、按钮、进度条、焦点描边使用此色。留空使用主题默认。 |
| 自定义背景色 | 颜色选择器 | `#ffffff` | 覆盖站点背景色。留空保持纯白。 |
| 内容最大宽度 | 数字 | `1120` | 可选范围 720 - 1600，输出为 `--am-container`。 |
| 显示站点描述 | 复选框 | 勾选 | 关闭后头部不再显示副标题。 |

### 布局

| 配置项 | 类型 | 默认值 | 说明 |
| --- | --- | --- | --- |
| 极简模式 | 复选框 | 不勾选 | 开启后隐藏侧栏、放大留白。 |
| 侧栏位置 | 下拉 | `右侧` | 可选 `右侧` / `左侧` / `不显示`。 |
| 归档列表布局 | 下拉 | `纵向列表` | 可选 `纵向列表` / `横向图文` / `网格卡片`。 |
| 显示页脚 Widget 区域 | 复选框 | 勾选 | 关闭后页脚三栏不再渲染。 |
| 吸顶头部 | 复选框 | 不勾选 | 打开后头部固定在视口顶部。 |

### 阅读体验

| 配置项 | 类型 | 默认值 | 说明 |
| --- | --- | --- | --- |
| 显示阅读进度条 | 复选框 | 勾选 | 仅在单篇文章页输出。 |
| 摘要长度 | 数字 | `42` | 归档列表中自动截取的字数（词）。 |
| 显示回到顶部按钮 | 复选框 | 勾选 | 滚动超过 400px 后出现。 |

### 菜单与 Widget

- **菜单位置**：主导航、页脚导航、社交链接（均在「外观 → 菜单」中分配）。
- **Widget 区域**：侧栏 1（主侧栏）、侧栏 2（附加）、页脚 1 / 2 / 3。

### 图片尺寸

| 名称 | 尺寸 | 用途 |
| --- | --- | --- |
| `aurora-card` | 1120 × 630 | 归档卡片封面 |
| `aurora-wide` | 1600 × 500 | 文章页顶部大图 |
| `aurora-thumb` | 320 × 200 | 横向卡片缩略图 |

## 模板层级

```
aurora-minimal/
├── style.css                # 主题头注释 + 全部样式
├── functions.php            # 装配入口：主题支持、Widget、脚本、过滤器
├── theme.json               # 块编辑器配置（WP 6.0+）
├── index.php                # 通用回退模板
├── front-page.php           # 首页：静态首页区块式渲染 / 文章流
├── single.php               # 单篇文章
├── page.php                 # 单页面
├── archive.php              # 归档（分类/标签/日期/作者/自定义分类法）
├── search.php               # 搜索结果
├── 404.php                  # 未找到
├── comments.php             # 评论列表 + 评论表单
├── sidebar.php              # 独立侧栏模板
├── searchform.php           # 搜索表单
├── header.php               # <head> + 站点头部
├── footer.php               # 站点页脚 + wp_footer
├── inc/
│   ├── extras.php           # 选项读取、动态 CSS、纯判断逻辑
│   ├── template-tags.php    # 所有输出型模板标签
│   ├── nav-walker.php       # Aurora_Minimal_Nav_Walker
│   └── customizer.php       # Customizer 面板与设置项
├── assets/
│   ├── css/editor-style.css # 区块编辑器内样式
│   └── js/
│       ├── navigation.js    # 移动端导航、回到顶部、搜索聚焦
│       ├── reading-progress.js # 阅读进度条
│       └── customizer-preview.js # Customizer 实时预览
```

> 翻译文件（`.pot` / `.mo` / `.l10n`）放在 `languages/` 目录，该目录在首次翻译时创建。主题已调用 `load_theme_textdomain()`，放入语言包后即可生效。

### 可覆盖的模板

在子主题中创建同名文件即可覆盖，例如 `single.php`、`archive.php`、`404.php`。

## 子主题制作

1. 在 `wp-content/themes/` 下创建目录，例如 `my-aurora-child`。
2. 目录内只放两个文件：

```php
<?php
/**
 * My Aurora 子主题样式。
 */

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'my-aurora-child',
		get_stylesheet_uri(),
		array( 'aurora-minimal-style' ),
		'1.0.0'
	);
} );
```

```css
/*
Theme Name: My Aurora
Description: Aurora Minimal 的子主题。
Template: aurora-minimal
Version: 1.0.0
*/
```

3. 启用子主题。

常用钩子：

| 钩子 | 类型 | 说明 |
| --- | --- | --- |
| `aurora_minimal_minimal_mode` | filter | 覆盖极简模式状态 |
| `aurora_minimal_content_width` | filter | 覆盖正文宽度（像素） |
| `aurora_minimal_get_option` | — | 读取主题设置的封装函数 |
| `wp_body_open` | action | 头部 <body> 之后插入内容 |

## FAQ

**Q：极简模式开启后侧栏不见了，怎么临时显示？**
把「极简模式」关掉即可。若只想在部分页面关闭，用上面的 `aurora_minimal_minimal_mode` 过滤器按条件判断。

**Q：为什么中文站点的摘要长度是「词」而不是「字」？**
WordPress 的 `excerpt_length` 过滤器基于词数，中文按字符切分。主题默认 42，实际显示约 42 个汉字。如需更短，直接在 Customizer 里调小数值。

**Q：能装 Elementor / Divi 之类的页面构建器吗？**
可以。本主题不使用任何页面构建器，也没有捆绑第三方库。但主题只声明了 `align-wide` 与基础 `html5` 支持，没有为构建器做容器级适配，复杂布局建议用子主题自行调整。

**Q：支持 WooCommerce 吗？**
声明了 `woocommerce` 支持，启用 WooCommerce 不会因缺少函数而报错。但本主题**没有**做 WooCommerce 模板适配，商城页面样式会不完整。需要完整商城支持请选择专门的电商主题。

**Q：主题会请求外部资源吗？**
不会。没有 Google Fonts、没有 CDN、没有外部 API 请求、没有第三方脚本。所有字体走系统字体栈，图标是内联 SVG。

**Q：如何修改默认配色？**
两条路径：编辑 `theme.json` 的 `settings.color.palette`（影响块编辑器色板），或在 Customizer 里设置强调色 / 背景色（影响前台 CSS 变量）。建议用 Customizer，避免升级时冲突。

**Q：归档页顶部那条分类栏怎么去掉？**
CSS 里隐藏 `.am-term-list` 即可，或者在归档页模板中直接不调用 `aurora_minimal_archive_term_bar()`。

**Q：screenshot.png 必须吗？**
上架 WordPress.org 目录必须提供，尺寸 1200 × 900。本地自用可以不放。

**Q：报 PHP 错误怎么办？**
本主题要求 PHP 7.4 及以上。请在 `wp-config.php` 中开启调试定位：

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
```

## License

MIT License

Copyright (c) 2026 MornRain

详细条款见 [LICENSE](LICENSE) 文件。

本主题为 MornRain 独立开发，不捆绑任何第三方库。所有字体使用系统字体栈，所有图标为内联 SVG，无任何外部资源请求。
