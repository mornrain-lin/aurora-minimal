# Changelog

本项目遵循 [Semantic Versioning](https://semver.org/lang/zh-CN/) 规范。

## [Unreleased]

### 修复

- `header.php` 中sticky_header 的 class 拼接改为先拼字符串再 `esc_attr()`，避免裸 `echo`。
- `--am-color-muted` 由 `#8a8a92` 调整为 `#75757c`，正文对比度从 3.43:1 提升到 4.57:1（WCAG AA）。
- 资源版本号改用 `filemtime()`，改CSS / JS 后不再依赖手动改版本号刷新缓存。


## [1.0.0] - 2026-10-02

### 新增

- 完整模板层级：`index.php` / `single.php` / `page.php` / `archive.php` / `search.php` / `404.php` / `comments.php` / `sidebar.php` / `front-page.php` / `searchform.php` / `header.php` / `footer.php`。
- `theme.json`：定义 7 组配色、系统字体栈、4 档字号、布局宽度，与 Customizer 双通道协作。
- Customizer「主题设置」面板：外观与配色、布局、阅读体验三个分区，共 12 个设置项。
- 「极简模式」开关：隐藏侧栏、放大留白，可通过 `aurora_minimal_minimal_mode` 过滤器覆盖。
- 文章页阅读进度条（原生 JS，页脚 defer 加载，带 `aria-valuenow` 同步）。
- 归档页分类筛选条 + 面包屑导航 + 三种归档列表布局（纵向 / 横向图文 / 网格卡片）。
- Widget 区域：侧栏 2 个 + 页脚 3 个，支持 `customize-selective-refresh-widgets`。
- `inc/` 目录拆分：`extras.php`（纯逻辑）、`template-tags.php`（模板标签）、`nav-walker.php`（导航 Walker）、`customizer.php`（自定义器）。
- `Aurora_Minimal_Nav_Walker`：两级下拉菜单，移动端点击父项折叠展开。
- 回到顶部按钮、404 页站内搜索聚焦、文章页相关阅读推荐。
- 打印友好样式、`prefers-reduced-motion` 降级、`:focus-visible` 键盘焦点样式。
- 全站转义输出，表单带 Nonce（评论表单由 WordPress 核心处理），零外部资源依赖。
