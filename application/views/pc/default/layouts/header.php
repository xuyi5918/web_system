<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        <?php echo empty($result['recommend_page_row']['page_title']) ? '' : $result['recommend_page_row']['page_title']?>
    </title>

    <script type="text/javascript">
        // 基础配置
        var config = {
            "static_url"      : "<?php echo trim(STATIC_URL,'/').'/src/'; ?>",    // 前端静态资源地址
            "static_ver"      : "<?php echo STATIC_VER; ?>",    // 静态资源版本号

            "site_basic"      : "<?php echo SITE_BASIC; ?>",    // 默认主站接口地址
            "site_novel"      : "<?php echo SITE_NOVEL; ?>",    // 小说站点接口地址
            "site_music"      : "<?php echo SITE_MUSIC; ?>",    // 音乐站点接口地址
            "site_comic"      : "<?php echo SITE_COMIC; ?>",    // 漫画站点接口地址
            "site_anime"      : "<?php echo SITE_ANIME; ?>",    // 动漫站点接口地址
            "site_users"      : "<?php echo SITE_USERS; ?>",    // 用户站点接口地址

            "version"         : "",
            "site_mode"       : 'pc',                           // 网站页面当前运行模式 PC / Mobile
            "request_type"    : 'json',                         // 接口请求数据方式 JSON
            "recommend_page"  : '<?php echo empty($result['recommend_page']) ? '' : $result['recommend_page'];?>'
        };

        // 推介位置配置
        var recommend = <?php echo json_encode($result); ?>;


    </script>


