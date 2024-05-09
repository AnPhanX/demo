<section class="login pd-section">
    <div class="form-box">
        <div class="form-value h5">
            <form action="site/handle/login.php" autocomplete="on" method="POST">
                <h2 class="login-title">Đăng nhập</h2>
                <div class="inputbox">
                    <!-- <ion-icon name="mail-outline"></ion-icon> -->
                    <!-- <label for="">Email</label> -->
                    <input type="email" placeholder="Email" name="account_email" required>
                </div>
                <div class="inputbox">
                    <!-- <ion-icon name="lock-closed-outline"></ion-icon> -->
                    <!-- <label for="">Password</label> -->
                    <input type="password" placeholder="Password" name="account_password" required>
                </div>
                <div class="forget">
                    <label for=""><input type="checkbox">Remember Me</label>
                </div>
                <button type="submit" name="login">Đăng nhập</button>
                <div class="register">
                    <p>Chưa có tài khoản <a href="index.php?page=register">Đăng ký</a></p>
                </div>
            </form>
        </div>
        <div class="loginform__overlay p-absolute"></div>
    </div>
</section>

<script>
    function showSuccessMessage() {
        toast({
            title: "Success",
            message: "Đăng ký thành công",
            type: "success",
            duration: 3000,
        });
    }

    function showErrorMessage() {
        toast({
            title: "Error",
            message: "Lỗi đăng ký, vui lòng kiểm tra lại",
            type: "error",
            duration: 3000,
        });
    }
</script>
<?php
if (isset($_GET['message']) && $_GET['message'] == 'success') {
    echo '<script>';
    echo 'showSuccessMessage();';
    echo 'window.history.pushState(null, "", "index.php?page=product_detail&product_id=' . $product_id . '");';
    echo '</script>';
} elseif (isset($_GET['message']) && $_GET['message'] == 'error') {
    echo '<script>';
    echo 'showErrorMessage();';
    echo 'window.history.pushState(null, "", "index.php?page=product_detail&product_id=' . $product_id . '");';
    echo '</script>';
}
?>