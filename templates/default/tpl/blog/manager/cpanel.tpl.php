<?php load_header() ?>

    <h1 class="title">Quản lí</h1>

    <div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_blue"><?php echo $page_title; ?></h2>

        <div class="tip"><?php echo $tip ?></div>

        <div class="manager_navi">
            <?php foreach($manager_navi as $navi): ?>
                <?php echo $navi ?>
            <?php endforeach ?>
        </div>
    </div>

    <div class="detail_content">
        <h3 class="sub_title border_orange">Thư mục</h3>

        <?php if (!count($cats)): ?>

            <div class="tip">
                <b><u><a href="<?php echo _HOME ?>/blog/manager/cpanel/<?php echo $sid; ?>/add/0/<?php echo $sid; ?>">Thêm một mục</a></u></b>
            </div>
            <?php if (isset($DisplayContent[0])): ?>
                <?php echo $DisplayContent[0]; ?>
            <?php endif; ?>

        <?php else: ?>

            <?php foreach ($cats as $id => $cat): ?>

                <div class="item">
                    <?php foreach($cat['navi'] as $navi): ?>
                        <?php echo $navi; ?>
                    <?php endforeach; ?>
                    <a href="<?php echo _HOME ?>/blog/manager/cpanel/<?php echo $id; ?>" title="<?php echo $cat['title']; ?>"><?php echo $cat['name']; ?></a>
                </div>

                <?php if (isset($DisplayContent[$id])): ?>
                    <?php echo $DisplayContent[$id] ?>
                <?php endif; ?>

            <?php endforeach; ?>

            <?php echo $folders_pagination ?>

        <?php endif; ?>
    </div>

    <div class="detail_content">
        <h3 class="sub_title border_orange">Bài viết</h3>

        <?php if (!count($posts)): ?>
            <div class="tip">
                Hiện tại chưa có bài viết nào trong thư mục này.<br/>
                Chỉ có thư mục nào có bài viết rồi mới được hiển thị ở trang chủ
            </div>
        <?php else: ?>
            <?php foreach ($posts as $id => $post): ?>

                <div class="item">
                    <?php foreach($post['navi'] as $navi): ?>
                        <?php echo $navi; ?>
                    <?php endforeach; ?>
                    <a href="<?php echo $post['full_url']; ?>" title="<?php echo $post['title']; ?>"><?php echo $post['name']; ?></a>
                </div>
                <?php if (isset($DisplayContent[$id])): ?>
                    <?php echo $DisplayContent[$id] ?>
                <?php endif; ?>

            <?php endforeach; ?>
            <?php echo $posts_pagination ?>
        <?php endif; ?>
    </div>

<?php load_footer() ?>