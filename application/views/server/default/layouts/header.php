<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Demo</title>
    <link rel="stylesheet" href=<?php echo staticUrl("css/reset.css");?>>
    <link rel="stylesheet" href=<?php echo staticUrl("css/index.css");?>>
    <link rel="stylesheet" href=<?php echo staticUrl("font/iconfont.css");?>>
    <script src="<?php echo staticUrl('js/lib/template-web.js');?>" type="application/javascript"></script>
</head>


<body style=" height: 2000px; ">
<!-- 顶部页面 -->
<div id="header">
    <!-- 最顶部 -->
    <div class="header-top">
        <div class="auto-width">
            <h1 class="logo fl"><a href="#"><img src=<?php echo staticUrl("images/logo.png");?> alt="#"></a></h1>
            <div class="search-box fl">
                <form action="#">
                    <input type="text" class="fl search-text" placeholder="Search here...">
                    <button class="fl search-btn">
                        <i class="iconfont icon-search"></i>
                    </button>
                </form>
                <div class="search-feedback">
                    <span class="search-hot">今日热搜</span>
                    <div class="search-menu">
                        <a href="#" class="item item-cur">
                            <span>1</span>
                            <em>OverWatch</em>
                        </a>
                        <a href="#" class="item item-cur">
                            <span>2</span>
                            <em>电影知道答案</em>
                        </a>
                        <a href="#" class="item item-cur">
                            <span>3</span>
                            <em>S6</em>
                        </a>
                        <a href="#" class="item">
                            <span>4</span>
                            <em>夏目友人帐</em>
                        </a>
                        <a href="#" class="item">
                            <span>5</span>
                            <em>微小而确实的幸福</em>
                        </a>
                        <a href="#" class="item">
                            <span>6</span>
                            <em>神盾局特工</em>
                        </a>
                        <a href="#" class="item">
                            <span>7</span>
                            <em>天凉好个秋</em>
                        </a>
                    </div>
                </div>
            </div>
            <ul class="header-guide fr">
                <li class="guide-item download">
                    <a href="#">
                        <i class="iconfont icon-app"></i>
                        <span>下载客户端</span>
                    </a>
                    <div class="guide-hover">
                        <img src=<?php echo staticUrl("images/app-download.png")?> alt="#">
                    </div>
                </li>
                <li class="guide-item login">
                    <a href="#">登录/注册</a>
                </li>
                <li class="guide-item history">
                    <a href="#">
                        <i class="iconfont icon-history"></i>
                    </a>
                    <div class="guide-hover">
                        <span class="text">尚未记录任何历史信息。</span>
                        <a href="#" class="more">More</a>
                    </div>
                </li>
                <li class="guide-item upload">
                    <a href="#">
                        <i class="iconfont icon-upload"></i>
                    </a>
                    <div class="guide-hover">
                        <ul>
                            <li><a href="#">投视频</a></li>
                            <li><a href="#">投文章</a></li>
                        </ul>
                    </div>
                </li>
                <li class="guide-item collect">
                    <a href="#">
                        <i class="iconfont icon-collect"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- 焦点图 -->
    <div class="header-banner">
        <a href="#" class="bg"></a>
        <span class="text">这辆车……到底能不能上啊！！！</span>
        <a href="#" class="link"></a>
    </div>

    <?php import('/layouts/nav.php')?>
</div>