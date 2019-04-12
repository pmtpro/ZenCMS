<?php load_header() ?>

    <h1 class="title"><?php echo icon('title'); ?> Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h2 class="title border_blue">Link liên kết</h2>
        <div class="tip">
            Friend link là những liên kết giữa những website với nhau. Đặt ở footer<br/>
            Còn hot link chủ yếu để quảng cáo. Đặt ở phần phần đầu website
        </div>
        <div class="item_non_border">
            <a href="<?php echo _HOME ?>/admin/tools/friendLink/new" class="button BgGreen">Thêm link</a>
        </div>
        <div class="item_non_border">
            <form method="POST">
                <select name="filter_link">
                    <option value="friend_link" <?php if (gFormCache('filter_link') == 'friend_link') echo 'selected' ?>>Friend link</option>
                    <option value="" <?php if (gFormCache('filter_link') == '') echo 'selected' ?>>Hot Link</option>
                </select>
                <input type="submit" name="sub_filter" value="Lọc" class="button BgBlue"/>
            </form>
        </div>
    </div>

    <div class="detail_content">
        <h2 class="title border_blue"><?php echo $page_title ?></h2>

        <?php load_message() ?>

        <?php if (empty($link_list)): ?>
            <div class="tip">
                Không có liên kết nào
            </div>
        <?php else: ?>

            <?php foreach ($link_list as $link): ?>

                <div class="item">

                    <a href="<?php echo $link['link'] ?>" rel="<?php echo $link['rel'] ?>"
                       title="<?php echo $link['title'] ?>" target="_blank">
                        <?php echo $link['name'] ?>
                    </a>
                <span class="text_smaller gray">
                    <i>(<?php echo get_time($link['time']) ?>)</i>
                </span>
                <span class="text_smaller gray">
                    <a href="<?php echo _HOME ?>/admin/tools/friendLink/edit/<?php echo $link['id'] ?>">Sửa</a>,
                    <a href="<?php echo _HOME ?>/admin/tools/friendLink/delete/<?php echo $link['id'] ?>" <?php echo cfm('Bạn chắc chắn xóa link này?') ?>>Xóa</a>
                </span>

                </div>

            <?php endforeach ?>

        <?php endif ?>
    </div>

<?php load_footer() ?>