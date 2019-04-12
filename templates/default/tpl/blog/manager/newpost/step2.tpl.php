<?php load_header(); ?>
<h1 class="title">Quản lí</h1>
<div class="breadcrumb"><?php echo $display_tree; ?></div>

<?php load_message(); ?>

<div class="detail_content">
    <h2 class="sub_title border_blue">Chọn kiểu dữ liệu đầu vào</h2>

    <div class="tip">ZenCMS hỗ trợ 2 kiểu dữ liệu đầu vào là HTML và BBcode</div>
    <div class="content" style="text-align: center">
        <form method="post">
            <input type="checkbox" name="step2_dont_ask_again" value="1"/> Đừng hỏi lại điều này<br/>
            <input type="submit" name="sub_type_data" class="button BgRed" value="HTML"/>
            <input type="submit" name="sub_type_data" class="button BgRed" value="BBcode"/>
        </form>
    </div>
</div>

<?php load_footer(); ?>
