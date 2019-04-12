<?php load_header() ?>

    <h1 class="title">Admin CP</h1>

    <div class="breadcrumb"><?php echo $display_tree ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_orange"><?php echo $page_title ?></h2>

        <div class="tip">Nội dung widget ở bên dưới</div>

        <?php echo h_decode($widget_data['content']) ?>

    </div>

<?php load_footer() ?>