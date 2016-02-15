# <?php die(); ?>

## 注意：书写时，缩进不能使用 Tab，必须使用空格

#############################
# 运行时环境
#############################

# 是否自动打开 session
runtime_session_start:          true

# 在 cookie 中使用什么名字保存应用程序的 session
session_cookie_name:            lc_sims_sess

# 指示 ACL 组件用什么键名在 session 中保存用户数据
acl_session_key:                acl_lc_sims_userdata

# 默认使用的缓存服务
runtime_cache_backend:          QCache_File

# 数据库元信息要使用的缓存服务
db_meta_cache_backend:          QCache_File

# 是否自动输出 Content-Type: text/html; charset=%i18n_response_charset%
runtime_response_header:        true

# 是否使用日志功能
log_enabled:                    true

# 运行时信息的缓存目录
runtime_cache_dir:              %ROOT_DIR%/_tmp/runtime_cache


#############################
# 调度器和访问控制
#############################

# url 参数的传递模式，可以是标准、PATHINFO、URL 重写等模式，分别对应 standard、pathinfo、rewrite 设置值
dispatcher_url_mode:            rewrite

# 指示当没有为控制器提供 ACT 时，要使用的默认 ACT
acl_default:
  allow: ACL_EVERYONE


#############################
# 视图、国际化和本地化
#############################

# 指示 QeePHP 应用程序内部处理数据和输出内容要使用的编码
i18n_response_charset:          utf-8

# 指示是否启用多语言支持
i18n_multi_languages:           false

# 默认的时区设置
l10n_default_timezone:          Asia/Shanghai

