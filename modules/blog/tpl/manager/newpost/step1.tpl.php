<?php load_header(); ?>
<h1 class="title">Quản lí</h1>
<div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message(); ?>

<div class="detail_content">
    <h2 class="sub_title border_blue">Nhập ID chuyên mục</h2>
    <div class="content">

        <div class="tip">Nhập ID chuyên mục bạn cần viết bài vào hoặc nhập trực tiếp Url mục đó vào đây</div>

        <form method="post" action="<?php echo _HOME ?>/blog/manager/newpost">
            <input type="text" name="uri"/><br/>

            Hoặc chọn từ danh sách:<br/>

            <select name="to">

                <?php foreach ($tree_folder as $id => $name): ?>
                    <option value="<?php echo $id ?>"><?php echo $name ?></option>
                <?php endforeach ?>

            </select>

            <input type="submit" name="sub_step1" class="button BgBlue" value="Tiếp tục"/>
        </form>

    </div>
</div>

<?php load_footer(); ?>
