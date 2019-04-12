<?php load_header(); ?>
    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message(); ?>

    <div class="detail_content">
        <h2 class="sub_title border_blue">
            <a href="<?php echo $blog['full_url'] ?>" target="_blank"><?php echo $blog['name'] ?></a>
        </h2>

        <div class="tip">
            <?php if (isset($_GET['content'])): ?>
                Bạn đang xem ảnh lấy từ nội dung bài viết
                <b><u>(<a href="<?php echo _HOME ?>/blog/manager/images/<?php echo $blog['id'] ?>/step2">Xem ảnh tải
                            lên</a>)</u></b>
            <?php else: ?>
                Bạn đang xem ảnh tải lên
                <b><u>(<a href="<?php echo _HOME ?>/blog/manager/images/<?php echo $blog['id'] ?>/step2?content">Lọc
                            ảnh lấy từ bài viết</a>)</u></b>
            <?php endif; ?>
        </div>

        <div class="item_non_border">
            <a href="<?php echo $blog['full_url'] ?>" class="button BgRed">Trở lại bài viết</a>
            <a href="<?php echo _HOME ?>/blog/manager/images/<?php echo $blog['id'] ?>/step2/add"
               class="button BgBlue">Tải ảnh từ máy</a>
            <a href="<?php echo _HOME ?>/blog/manager/images/<?php echo $blog['id'] ?>/step2/add?remote"
               class="button BgGreen">Nhập khẩu ảnh</a><br/>
        </div>
    </div>

    <div class="detail_content">

        <h2 class="sub_title border_orange">
            <?php if (isset($_GET['content'])): ?>
                Danh sách ảnh lấy từ nội dung bài viết
                <span class="text_smaller">
                    (<a href="<?php echo _HOME ?>/blog/manager/images/<?php echo $blog['id'] ?>/step2">Xem ảnh tải
                        lên</a>)
                </span>
            <?php else: ?>
                Danh sách ảnh tải lên
                <span class="text_smaller">
                    (<a href="<?php echo _HOME ?>/blog/manager/images/<?php echo $blog['id'] ?>/step2?content">Lọc ảnh
                        lấy từ bài viết</a>)
                </span>
            <?php endif; ?>
        </h2>

        <?php if (!count($blog['images'])): ?>
            <div class="tip">Hiện tại chưa có ảnh nào</div>
        <?php else: ?>

            <form method="POST">

                <?php foreach ($blog['images'] as $image): ?>
                    <div class="item">

                        <input type="checkbox" name="delete[]" value="<?php echo $image['id'] ?>"/>
                        <a href="<?php echo $image['full_url'] ?>" target="_blank"><img
                                src="<?php echo $image['full_url'] ?>" width="80px"/></a>
                        <span class="text_smaller text_gray"></span>

                    </div>
                <?php endforeach; ?>

                <div class="item">
                    <input type="submit" name="sub_delete" value="Xóa ảnh đã chọn" class="button BgRed"/>
                </div>
            </form>

        <?php endif; ?>
    </div>

<?php load_footer(); ?>