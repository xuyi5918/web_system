<!-- 导航 -->
<div class="header-nav">
    <div class="nav-wrap auto-width clearfix">
        <?php

            $navList = app()::classes('recommend_info_class', 'recommend')->getNavListByMca($mca = 'default', $locName = 'nav', $platform = 'pc');

            $contentList = empty($navList['content_list']) ? array() : $navList['content_list'];
            foreach ($contentList as $item=>$contentRow)
            {
                $parentId = empty($contentRow['parent_id']) ? 0 : intval($contentRow['parent_id']);

                $extClass = empty($contentRow['atter']['ext_class']) ? '' : trim($contentRow['atter']['ext_class']);

                if (empty($parentId))
                {
        ?>
                    <a href="   <?php echo empty($contentRow['link']) ? '' : $contentRow['link'] ?>" class="nav-item <?php echo $extClass;?>">
                        <?php echo empty($contentRow['title']) ? '' : $contentRow['title'] ?>
                    </a>
        <?php
                }
            }
        ?>
    </div>


    <div class="subNav">
        <ul class="auto-width">
            <?php
            foreach ($contentList as $item=>$contentRow)
            {
                $parentId = empty($contentRow['parent_id']) ? 0 : intval($contentRow['parent_id']);
                if (! empty($parentId))
                {
            ?>

                    <li class="subNav-item">
                    <?php
                    foreach ($contentList as $key=>$row)
                    {
                        if($row['content_id'] == $parentId)
                        {
                    ?>
                            <a href="<?php echo $contentRow['link'];?>"><?php echo $contentRow['title'] ?></a>

                    <?php
                        }
                    }
                    ?>
                    </li>
            <?php
                }
            }
            ?>
        </ul>
    </div>
</div>
