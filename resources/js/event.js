$(function () {
  $(".btn-login-js").click(function () {
    login($(this));
  });

  $(".login").on("keydown", function (e) {
    if (e.key === "Enter") {
      login($(e.target));
    }
  });

  $(document).on("click", ".click-remove-ajax", function () {
    href = $(this).attr("data-href") || $(this).attr("href");

    Notify.show.confirm(function () {
      lib.send.post(href, function (res) {
        if (res.success) {
          Notify.show.success(res.msg);
          window.location.reload();
        } else {
          Notify.show.error(res.msg);
        }
        Notiflix.Loading.Remove();
      });
    });
  });
  
  $(document).on("click", "[v-click=" + window.event.callAjaxModal + "]", function (e) {
    e.preventDefault();
    href = $(this).attr("data-href") || $(this).attr("href");
    self = $(this);
    width = self.attr("width") || "80%";
    params = "?popup=true";
    if (isDisabled(self)) return;
    
    options = {
      width: self.attr("width") || "80%",
      align: self.attr("v-modal-align") || "right",
      isUpdate: self.attr("v-display-mode") == "update" ? true : false
    };

    if (href.includes("?")) {
      params = "&popup=true";
    }

    loading.show(self);
    $.ajax({
      url: href + params,
      type: "GET",
      dataType: "html",
    })
      .done(function (res, status, xhr) {
        loading.remove(self);
        if (xhr.status == 200) {
          openModal(res, options);
        }
      })
      .fail(function (res) {
        if (res.status == 401) {
          window.location.href = window.loginURI + "?callback=" + window.location.href;
        }
      });
  });

  $(document).on("click", "[v-click]", function (e) {
    self = $(this);
    if (self.attr("v-disabled") == "") {
      e.preventDefault();
      return;
    };

    if (self.attr("v-active-element")) {
      $(self.attr("v-active-element")).removeClass("disabled").addClass("active");
    }
  });

  $(document).on("click", '[data-event="save"]', function () {
    form = $($(this).attr("data-form-target"));
    hasContinue = true;

    $.each(form.find("[required]"), function (_, formItem) {
      jqueryFormItem = $(formItem);
      if ((jqueryFormItem.is("input") && !jqueryFormItem.val()) || (jqueryFormItem.find("input").length > 0 && !jqueryFormItem.find('input[type="hidden"]').val())) {
        jqueryFormItem.addClass("invalid");
        hasContinue = false;
      } else {
        jqueryFormItem.removeClass("invalid");
      }
    });
    if (!hasContinue) {
      Notify.show.error("Vui lòng điền đầy đủ dữ liệu");
      return;
    }

    Notiflix.Loading.Dots("Đang tạo dữ liệu...");
    params = form.serialize();
    lib.send.post(
      form.attr("action"),
      function (res) {
        Notiflix.Loading.Remove();
        if (res.success) {
          Notify.show.success(res.msg);
          if (typeof res.data.redirect_url == "string" && res.data.redirect_url) {
            window.location.href = res.data.redirect_url;
          } else {
            setTimeout(function () {
              window.location.reload();
            }, 1000);
          }
        } else {
          Notify.show.error(res.msg);
        }
      },
      params
    );
  });
});

var login = function (self) {
  form = self.closest("form");
  username = form.find('input[name="username"]');
  password = form.find('input[name="password"]');
  if (!!!username.val()) {
    Notify.show.error("Vui lòng điền tên tài khoản");
    return;
  }

  if (!!!password.val()) {
    Notify.show.error("Vui lòng điền mật khẩu");
    return;
  }
  form.submit();
};

openModal = (html = "", options = null, add = null) => {

  modal = $("#modal");
  modal.find(".modal-dialog").css("width", options.width).css("max-width", "100%");
  if (!!add) {
    modal.find(".button-js").prepend("<a href='" + add.href + "' class='btn btn-primary add'>" + add.text + "</a>");
  }
  if (options.align != "right") {
    modal.removeClass("right").addClass(options.align);
  }
  modal.find(".modal-body").html(html);
  if (!options.isUpdate) {
    modal.modal("toggle");
  }
};
