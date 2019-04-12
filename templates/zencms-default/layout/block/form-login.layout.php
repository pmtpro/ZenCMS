<form id="form-login" method="POST" name="form-login" action="<?php echo HOME ?>/login" role="form">
    <div class="form-group">
        <label for="form-login-username">Username</label>
        <input type="text" class="form-control" id="form-login-username" name="username" placeholder="Username" required/>
    </div>
    <div class="form-group">
        <label for="form-login-password">Password</label>
        <input type="password" class="form-control" id="form-login-password" name="password" placeholder="Password" required/>
    </div>
    <div class="form-group">
        <input type="checkbox" id="form-login-remember-me" name="remember_me" value="1"/>
        <label for="form-login-remember-me">Ghi nhớ tôi</label>
    </div>
    <button type="submit-login" class="btn btn-success">Đăng Nhập</button>
    <a href="<?php echo HOME ?>/register" title="Đăng kí tài khoản" class="btn btn-primary">Đăng kí</a>
</form>