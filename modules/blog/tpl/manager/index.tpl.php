<?php load_header() ?>

    <h1 class="title">Quản lí</h1>
    <div class="breadcrumb"> <?php echo $display_tree; ?></div>

<?php load_message() ?>

    <div class="detail_content">
        <h2 class="sub_title border_orange"><?php echo $page_title; ?></h2>
        <?php foreach ($menus as $menu): ?>
            <div class="item" style="padding:10px;">
                <span class="icon"><?php echo icon($menu['icon']); ?></span>
                <a href="<?php echo $menu['full_url'] ?>"><?php echo $menu['name']; ?></a>
            </div>
        <?php endforeach; ?>
    </div>
<?php load_footer() ?>