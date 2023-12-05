const loading = {
  html: function() {
    var res = `<i aria-label="icon: loading" class="anticon anticon-loading" style="font-size: 60px;color: rgb(8, 132, 209);"><svg viewBox="0 0 1024 1024" focusable="false" class="anticon-spin" data-icon="loading" width="1em" height="1em" fill="currentColor" aria-hidden="true"><path d="M988 548c-19.9 0-36-16.1-36-36 0-59.4-11.6-117-34.6-171.3a440.45 440.45 0 0 0-94.3-139.9 437.71 437.71 0 0 0-139.9-94.3C629 83.6 571.4 72 512 72c-19.9 0-36-16.1-36-36s16.1-36 36-36c69.1 0 136.2 13.5 199.3 40.3C772.3 66 827 103 874 150c47 47 83.9 101.8 109.7 162.7 26.7 63.1 40.2 130.2 40.2 199.3.1 19.9-16 36-35.9 36z"></path></svg></i>`;
    return res;
  },
  show: function(element) {
    element.addClass('is-loading').addClass('loading-spinner').attr("v-disabled", "");
  },
  remove: function(element) {
    if (element) {
      element.removeClass('is-loading').removeClass('loading-spinner').removeAttr("v-disabled");
    }
    
    Notiflix.Loading.Remove();
  },
  order: {
    show: function(element) {
      Notiflix.Block.Pulse(element, "Đang tải dữ liệu...");
      // element.html('<td colspan="9" class="text-center anticon-loading" style="padding: 40px">' + loading.html() + ' <div style="color: rgb(8, 132, 209); font-size: 17px; margin: 10px 0px">Đang tải dữ liệu...</div></td>');
    },
  },
};

const lib = {
  updateParams: function(key, value) {
    url = window.location.href;
    try {
      newUrl = new URL(url);
      options = newUrl.searchParams.getAll(key);
      if (options.includes(value)) {
        return url;
      }
    } catch {}

    var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
      hash;
    if (re.test(url)) {
      if (typeof value !== "undefined" && value !== null) url = url.replace(re, "$1" + key + "=" + value + "$2$3");
      else {
        hash = url.split("#");
        url = hash[0].replace(re, "$1$3").replace(/(&|\?)$/, "");
        if (typeof hash[1] !== "undefined" && hash[1] !== null) url += "#" + hash[1];
      }
    } else {
      if (typeof value !== "undefined" && value !== null) {
        var separator = url.indexOf("?") !== -1 ? "&" : "?";
        hash = url.split("#");
        url = hash[0] + separator + key + "=" + value;
        if (typeof hash[1] !== "undefined" && hash[1] !== null) url += "#" + hash[1];
      }
    }

    window.history.pushState("", "", url);
    return url;
  },

  removeParams: function(key, value) {
    if (!!value) {
      url = window.location.href;
      url = url.replace("&" + key + "=" + value, "");
      url = url.replace(key + "=" + value, "");
      url = url.replace(key.replace("[]", "") + "=" + value, "");
      url = url.replace("?&", "?");
      try {
        split = url.split("?");
        if (split[1] == "" || !split[1]) {
          url = url.replace("?", "");
        }
      } catch (error) {
        Notify.show.error(error);
      }
    } else {
      sourceURL = window.location.href;
      var url = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = sourceURL.indexOf("?") !== -1 ? sourceURL.split("?")[1] : "";
      if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
          param = params_arr[i].split("=")[0];
          if (param === key) {
            params_arr.splice(i, 1);
          }
        }
        url = url + "?" + params_arr.join("&");
      }
    }
    window.history.pushState("", "", url);
  },
  removeAllParams: function() {
    url = window.location.href;
    try {
      split = url.split("?");
      url = split[0];
    } catch (error) {
      Notify.show.error(error);
    }
    window.history.pushState("", "", url);
  },
  send: {
    get: function(url, callback, params = [], error = null) {
      axios
        .get(url + params)
        .then(function(response) {
          callback(response.data);
        })
        .catch(function(er) {
          if (!!error) {
            error(er);
          } else {
            if (er.response && er.response.status == 401) {
              window.location.reload();
            }
            Notify.show.error(er);
          }
        })
        .finally(function() {
          loading.remove();
        });
    },
    post: function(url, callback, params = [], error = null) {
      axios
        .post(url, params)
        .then(function(response) {
          callback(response.data);
        })
        .catch(function(er) {
          if (!!error) {
            error(er);
          } else {
            console.log(er);
            Notify.show.error(er);
          }
        })
        .finally(function() {
          loading.remove();
        });
    },
  },
  setValueToggle: function(name, value) {
    toggle = $('[name="' + name + '"]');
    if (!!!toggle.length || value == "") return;

    menu = $(toggle.attr("data-target"));
    active = menu.find('[value="' + value + '"]');
    text = active.attr("text");
    if (!!!text) return;
    active.attr("data-selected", "true");
    toggle.val(text);
  },
};

const element = {
  table: function() {
    return $("#table-body-data");
  },
  paginate: function() {
    return $("#paginate");
  },
  column: function(element) {
    return $(element);
  },
};

const Notify = {
  show: {
    error: function(msg) {
      Notiflix.Notify.Failure(msg);
    },
    success: function(msg) {
      Notiflix.Notify.Success(msg);
    },
    confirm: function(callback, title = "Xác nhận", des = "Bạn có chắc chắn thực hiện thao tác này?", yes = "Có", no = "Không", rollback = undefined) {
      Notiflix.Confirm.Show(
        title,
        des,
        yes,
        no,
        function() {
          callback();
        },
        function() {
          if (!!rollback) {
            rollback();
          }
        }
      );
    },
  },
};

function number(evt) {
  var theEvent = evt || window.event;
  if (theEvent.type === "paste") {
    key = event.clipboardData.getData("text/plain");
  } else {
    // Handle key press
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
  }
  var regex = /[0-9]|\./;
  if (!regex.test(key)) {
    theEvent.returnValue = false;
    if (theEvent.preventDefault) theEvent.preventDefault();
  }
}

var searchData = function(object) {
  $obj = object.parents(".form-group").find("li");
  $search = object.val().toUpperCase();
  $.each($obj, function(index, value) {
    $item = $(value).text();
    if ($item.toUpperCase().indexOf($search) > -1) {
      $(value).addClass("d-flex");
    } else {
      $(value)
        .removeClass("d-flex")
        .removeClass("d-block")
        .hide();
    }
  });
};
function format_curency(a) {
  return a.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

$(function() {
  $(document).on("click", '[data-toggle="menu"]', function() {
    self = $(this);
    menu = self.attr("data-target");
    name = self.attr("name");
    $(menu).show();

    $(document).on("click", menu + " li", function() {
      text = $(this).attr("text");
      val = $(this).attr("value");
      self.val(text);
      self
        .parent()
        .find("*")
        .removeAttr("data-selected");
      $(this).attr("data-selected", "true");
      lib.updateParams(name, val);
      if (!!!self.attr("data-toggle-multi")) {
        $(menu).hide();
      }
    });
  });
  toggle();
});

var toggle = function() {
  menu = $('[data-toggle="menu"]');
  menu.css("position", "relative");
  for (let index = 0; index < menu.length; index++) {
    target = $($(menu[index]).attr("data-target"));
    target.css("position", "absolute");
    target.addClass("list-menu-dropdown parent-list-dropdown");
    target.hide();
    textActive = target.find('[data-selected="true"]').attr("text");
    if (!!textActive) {
      $(menu[index])
        .parent()
        .find("*")
        .removeAttr("data-selected");
      $(menu[index]).val(textActive);
    }
  }

  $(document).on("click", "body", function(e) {
    parent = $(e.target).closest(".list-menu-dropdown");

    if (!!parent.html() || !!$(e.target).attr("data-toggle")) {
      if (!!$(e.target).attr("data-target")) {
        $(".list-menu-dropdown").hide();
        $($(e.target).attr("data-target")).show();
      }
      return;
    }
    $(".list-menu-dropdown").hide();
  });
};
