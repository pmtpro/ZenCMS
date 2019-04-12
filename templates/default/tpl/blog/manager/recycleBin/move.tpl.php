<?php load_header() ?>

    <h1 class="title">Quản lí</h1>

    <div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_blue"><?php echo $page_title; ?></h2>

        <div class="tip">
            Bạn đang chuyển bài viết <b><a href="<?php echo $blog['full_url'] ?>?_review_recycleBin_"
                                           target="_blank"><?php echo $blog['name'] ?></a></b><br/>
            Chọn thư mục bạn muốn chuyển đến. Khi chuyển, bài viết sẽ được xóa khỏi thùng rác
        </div>

        <form method="POST">

            <div class="item">

                <select name="to">
                    <?php foreach ($tree_folder as $id => $name): ?>
                        <option value="<?php echo $id ?>"><?php echo $name ?></option>
                    <?php endforeach ?>
                </select>

            </div>
            <div class="item">
                <input type="submit" name="sub_move" value="Di chuyển" class="button BgBlue"/>
            </div>

        </form>
    </div>


<?php load_footer() ?>