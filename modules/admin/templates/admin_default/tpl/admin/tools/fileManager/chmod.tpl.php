<?php load_header() ?>

    <h1 class="title">Admin cpanel</h1>
    <div class="breadcrumb"><?php echo $display_tree ?></div>

    <div class="detail_content">
        <div class="sub_title border_blue">Quản lí file</div>
        <div class="item_non_border">
            <?php foreach ($public_cpanel as $url => $cp): ?>
                <a href="<?php echo $url ?>" class="button BgBlue"><?php echo $cp ?></a>
            <?php endforeach ?>
        </div>
    </div>

    <div class="detail_content">
        <div class="sub_title border_blue">Chmod</div>

        <div class="path">
            <?php echo $file ?>
        </div>

        <?php load_message() ?>

        <div class="content">

            <form method="POST">

                <?php foreach ($selected as $item): ?>

                    <?php if (isset($failure[$item])): ?>
                        <div class="mini_notice">
                            <?php echo $failure[$item] ?>
                        </div>
                    <?php endif ?>

                    <code class="ad_list">
                        <?php echo base64_decode($item) ?>:
                        <?php echo $info[$item]['perms'] ?>
                    </code>

                <?php endforeach ?>

                <table class="reference notranslate" border="0" cellpadding="2" cellspacing="2">
                    <tbody>
                    <tr>
                        <th align="left" valign="top">Mode</th>
                        <th align="left" valign="top">User</th>
                        <th align="left" valign="top">Group</th>
                        <th align="left" valign="top">World</th>
                    </tr>
                    <tr>
                        <td>Read</td>
                        <td><input type="checkbox" name="ur" id="ur" value="4" onclick="calcperm();"></td>
                        <td><input type="checkbox" name="gr" id="gr" value="4" onclick="calcperm();"></td>
                        <td><input type="checkbox" name="wr" id="wr" value="4" onclick="calcperm();"></td>
                    </tr>
                    <tr>
                        <td>Write</td>
                        <td><input type="checkbox" name="uw" id="uw" value="2" onclick="calcperm();"></td>
                        <td><input type="checkbox" name="gw" id="gw" value="2" onclick="calcperm();"></td>
                        <td><input type="checkbox" name="ww" id="ww" value="2" onclick="calcperm();"></td>
                    </tr>
                    <tr>
                        <td>Execute</td>
                        <td><input type="checkbox" name="ux" id="ux" value="1" onclick="calcperm();"></td>
                        <td><input type="checkbox" name="gx" id="gx" value="1" onclick="calcperm();"></td>
                        <td><input type="checkbox" name="wx" id="wx" value="1" onclick="calcperm();"></td>
                    </tr>
                    <tr>
                        <td>Permission</td>
                        <td><input type="text" name="u" id="u" size="1" onkeypress="calupperm(event, 'u');"
                                   onkeydown="cleanperm(event, 'u');"></td>
                        <td><input type="text" name="g" id="g" size="1" onkeypress="calupperm(event, 'g');"
                                   onkeydown="cleanperm(event, 'g');"></td>
                        <td><input type="text" name="w" id="w" size="1" onkeypress="calupperm(event, 'w');"
                                   onkeydown="cleanperm(event, 'w');"></td>
                    </tr>
                    </tbody>
                </table>

                <input type="submit" name="sub_do_chmod" value="Chmod" class="button BgBlack"/>
                <a href="<?php echo _HOME ?>/admin/tools/fileManager?file=<?php echo $file ?>" class="button BgBlack">Hủy</a>
            </form>

        </div>
    </div>

<?php load_footer() ?>