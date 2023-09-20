$(function () {
    $('.btn-login-js').click(function () {
        login($(this));
    });

    $('.login').on('keydown', function (e) {
        if (e.key === "Enter") {
            login($(e.target));
        }
    });

    $(document).on('click', '.click-remove-ajax', function() {
        href = $(this).attr('data-href') || $(this).attr('href');

        Notify.show.confirm(function() {
            lib.send.post(href, function(res) {
                if (res.success){
                    Notify.show.success(res.msg);
                    window.location.reload();
                }else{
                    Notify.show.error(res.msg);
                }
                Notiflix.Loading.Remove();
            });
        });
    });
});

var login = function (self) {
    form = self.closest('form');
    username = form.find('input[name="username"]');
    password = form.find('input[name="password"]');
    if (!!!username.val()) {
        Notify.show.error('Vui lòng điền tên tài khoản');
        return;
    }

    if (!!!password.val()) {
        Notify.show.error('Vui lòng điền mật khẩu');
        return;
    }
    form.submit();
}