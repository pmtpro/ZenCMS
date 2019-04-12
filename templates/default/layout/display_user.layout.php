<div class="member_info">
    <div class="b_avatar float_left">
        <img src="<?php echo $user['full_avatar']; ?>" width="50px"/>
    </div>
    <div class="detail_account">
        <div class="info_name">
            <span><?php echo icon('online', 'vertical-align: text-top;') ?><?php echo show_nick($user) ?></span>
        </div>
        <div class="info_permission">
            <span><?php echo icon($user['sex']) ?> <?php echo show_perm_sign($user) ?></span>
        </div>
        <div class="info_like">
            <span><?php echo icon('like', 'vertical-align: top;') ?> <?php echo $user['like'] ?></span>
        </div>
    </div>
</div>