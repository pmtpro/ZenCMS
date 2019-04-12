<?php load_header() ?>

    <h1 class="title"><?php echo icon('title'); ?> Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <h2 class="title border_blue"><?php echo $page_title ?></h2>
        <?php load_message() ?>

        <form method="POST">
            <div class="item">
                Tên:<br/>
                <input type="text" name="name" value="<?php echo $link['name'] ?>"/>
            </div>
            <div class="item">
                Title:<br/>
                <input type="text" name="title" value="<?php echo $link['title'] ?>"/>
            </div>
            <div class="item">
                Link:<br/>
                <input type="text" name="link" value="<?php echo $link['link'] ?>"/>
            </div>
            <div class="item">
                <select name="rel">
                    <option value="nofollow" <?php if ($link['rel'] == 'nofollow') echo 'selected' ?>>Nofollow</option>
                    <option value="dofollow" <?php if ($link['rel'] == 'dofollow') echo 'selected' ?>>Dofollow</option>
                </select>
            </div>
            <div class="item">
                Style: (Sử dụng CSS)<br/>
                <textarea name="style"><?php echo $link['style'] ?></textarea>
            </div>
            <div class="item">
                Tag bắt đầu: <span class="text_smaller gray">Bạn có thể sử dụng html để đánh dấu tag bắt đầu cho link này. Ví dụ: &lt;b&gt; hoặc &lt;img src="..." /&gt;</span><br/>
                <textarea name="tag_start"><?php echo h($link['tag_start']) ?></textarea><br/>
                Tag kết thúc: <span class="text_smaller gray">Ví dụ: &lt;/b&gt;</span><br/>
                <textarea name="tag_end"><?php echo h($link['tag_end']) ?></textarea>
            </div>
            <div class="item">
                <input type="submit" name="sub_edit" value="Lưu thay đổi" class="button BgGreen"/>
                <a href="<?php echo _HOME ?>/admin/tools/friendLink" class="button BgRed">Quay lại</a>
            </div>
        </form>

    </div>

<?php load_footer() ?>