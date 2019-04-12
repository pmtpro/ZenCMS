<form method="POST">
    <div class="item_non_border">
        Tên: <input type="text" name="name" value=""/><br/>
        <textarea name="content" id="content"></textarea>
        <input type="hidden" name="token_chat" value="<?php echo $token ?>"/>

        <div class="chat_bar">
            <table>
                <tr>
                    <td>
                        <img src="<?php echo $captcha_src ?>"/>
                    </td>
                    <td>
                        <input type="text" name="captcha_code" style="width: 30px;"/><br/>
                        <input type="submit" name="sub_chat" value="Chat" class="button BgGreen"/>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</form>