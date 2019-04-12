<?php load_header() ?>

    <h1 class="title">Quản lí</h1>

    <div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_blue"><?php echo $page_title; ?></h2>
        <div class="item_non_border">
            <a href="<?php echo _HOME ?>/blog/manager" class="button BgRed">Trở về trang quản trị</a>
        </div>
    </div>

    <div class="detail_content">
        <h3 class="sub_title border_orange">Thư mục</h3>

        <?php if (!count($cats)): ?>

            <?php if ($count_cats): ?>
                <div class="tip">Hãy xóa bài viết trước rồi mới xóa được thư mục</div>
            <?php else: ?>
                <div class="tip">Không có mục nào trong thùng rác</div>
            <?php endif ?>

        <?php else: ?>

            <?php foreach ($cats as $id => $cat): ?>

                <div class="item">

                    <a href="<?php echo $cat['full_url'] ?>?_review_recycleBin_"><?php echo $cat['name']; ?></a>
                    <span class="text_smaller gray">
                            <?php echo implode(', ', $cat['manager_bar']) ?>
                    </span>

                </div>

            <?php endforeach; ?>

            <?php echo $folders_pagination ?>

        <?php endif; ?>
    </div>

    <div class="detail_content">
        <h3 class="sub_title border_orange">Bài viết</h3>

        <?php if (!count($posts)): ?>
            <div class="tip">Thư mục này không có bài viết nào</div>
        <?php else: ?>

            <?php foreach ($posts as $id => $post): ?>

                <div class="item">

                    <a href="<?php echo $post['full_url']; ?>?_review_recycleBin_"><?php echo $post['name']; ?></a>

                    <span class="text_smaller gray">
                            <?php echo implode(', ', $post['manager_bar']) ?>
                    </span>

                </div>

            <?php endforeach; ?>
            <?php echo $posts_pagination ?>
        <?php endif; ?>
    </div>

<?php load_footer() ?>