<?php load_header(); ?>
    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message(); ?>

    <div class="detail_content">
        <h2 class="sub_title border_blue">
            <a href="<?php echo $blog['full_url'] ?>" target="_blank"><?php echo $blog['name'] ?></a>
        </h2>
        <div class="tip">
            Thống kê: Bài này chứa <b><?php echo $blog['stat']['num_files']; ?></b> file<br/>
            Và <b><?php echo $blog['stat']['num_links']; ?></b> link
            <i><u><a href="<?php echo _HOME ?>/blog/manager/links/<?php echo $blog['id'] ?>/step2">Quản lí link</a></u></i>
        </div>
        <div class="tip">
            Click vào hình "Bút chì" để sửa hoặc xóa file
        </div>
        <div class="item_non_border">
            <a href="<?php echo $blog['full_url'] ?>" class="button BgRed">Trở lại bài viết</a>
            <a href="<?php echo _HOME ?>/blog/manager/files/<?php echo $blog['id'] ?>/step2/add"
               class="button BgBlue">Thêm file từ máy</a>
            <a href="<?php echo _HOME ?>/blog/manager/files/<?php echo $blog['id'] ?>/step2/add?remote"
               class="button BgGreen">Nhập khẩu file</a><br/>
        </div>
    </div>

    <div class="detail_content">

        <h2 class="sub_title border_orange">Danh sách file</h2>

        <?php if (!count($blog['files'])): ?>
            <div class="tip">Hiện tại chưa có file nào</div>
        <?php else: ?>

            <?php foreach ($blog['files'] as $file): ?>
                <div class="item">
                    <span class="button_manager">
                        <a href="<?php echo _HOME; ?>/blog/manager/files/<?php echo $blog['id'] ?>/step2/edit/<?php echo $file['id']; ?>"
                           title="Chỉnh sửa"><?php echo icon('manager_edit') ?></a>
                    </span>
                    <a href="<?php echo $file['full_url'] ?>" target="_blank"><?php echo $file['name'] ?></a>
                    <span class="text_smaller text_gray"><?php echo get_size($file['size']); ?>, <?php echo $file['down'] ?> down</span>

                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>

<?php load_footer(); ?>