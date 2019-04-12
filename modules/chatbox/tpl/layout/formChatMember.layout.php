<form method="POST">
    <textarea name="content" id="content"></textarea>
    <input type="hidden" name="token_chat" value="<?php echo $token ?>"/>
    <input type="submit" name="sub_chat" value="Chat" class="button BgGreen"/>
    <input type="button" class="button BgBlue" onclick="window.location.reload()" value="Làm mới">
</form>