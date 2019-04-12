<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta charset="utf-8">
    <style>
        .thumb-img {
            border: 1px #E9DCDC solid;
            border-radius: 3px;
            float: left;
            margin: 15px;
        }
        .thumb-img:hover {
            border: 1px #ffa430 solid;
        }
        .thum-desc {

        }
        .thumb-img img {
            cursor: pointer;
            margin-bottom: -5px;
        }
        .dir {
            padding: 20px;
            text-align: center;
        }
    </style>
    <script type="text/javascript">
        function setLink(url) {
            window.opener.CKEDITOR.tools.callFunction(<?php echo ZenView::$D['CKEditorFuncNum'] ?>, url);
            window.close();
        }
    </script>
</head>
<body>
<div class="dir">
    <form method="POST">
        <select name="dir">
            <?php
            foreach (ZenView::$D['dir_list'] as $dir) {
                echo '<option value="' . $dir['name'] . '" ' . (ZenView::$D['current_dir_name'] == $dir['name'] ? 'selected':'') . '>' . $dir['name'] . '</option>';
            }
            ?>
        </select>
        <input type="submit" value="Go"/>
    </form>
</div>
<div style="display: inline-block">
<?php
foreach (ZenView::$D['image_list'] as $img) {
    echo('<div class="thumb-img">
    <img src="' . $img['full_url'] . '" height="100px" title="' . $img['name'] . ' (' . $img['display_mtime'] . ')" onclick="setLink(\'' . $img['base_url'] . '\')"/>
    </div>');
}
?>
</div>
</body>
</html>