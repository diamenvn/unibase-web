const loading = {
    html: function () {
        html = `<i aria-label="icon: loading" class="anticon anticon-loading" style="font-size: 60px;color: rgb(8, 132, 209);"><svg viewBox="0 0 1024 1024" focusable="false" class="anticon-spin" data-icon="loading" width="1em" height="1em" fill="currentColor" aria-hidden="true"><path d="M988 548c-19.9 0-36-16.1-36-36 0-59.4-11.6-117-34.6-171.3a440.45 440.45 0 0 0-94.3-139.9 437.71 437.71 0 0 0-139.9-94.3C629 83.6 571.4 72 512 72c-19.9 0-36-16.1-36-36s16.1-36 36-36c69.1 0 136.2 13.5 199.3 40.3C772.3 66 827 103 874 150c47 47 83.9 101.8 109.7 162.7 26.7 63.1 40.2 130.2 40.2 199.3.1 19.9-16 36-35.9 36z"></path></svg></i>`;
        return html;
    },
    show: function (element) {
        element.html(loading.html());
    },
    remove: function () {
        $('.anticon-loading').remove();
    },
}

const lib = {
    updateParams: function (key, value) {
        url = window.location.href;
        try {
            newUrl = new URL(url);
            options = newUrl.searchParams.getAll(key);
            if (options.includes(value)) {
                return url;
            }
        } catch { }

        var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
            hash;
        if (re.test(url)) {
            if (typeof value !== 'undefined' && value !== null)
                url = url.replace(re, '$1' + key + "=" + value + '$2$3');
            else {
                hash = url.split('#');
                url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
                if (typeof hash[1] !== 'undefined' && hash[1] !== null)
                    url += '#' + hash[1];
            }
        } else {
            if (typeof value !== 'undefined' && value !== null) {
                var separator = url.indexOf('?') !== -1 ? '&' : '?';
                hash = url.split('#');
                url = hash[0] + separator + key + '=' + value;
                if (typeof hash[1] !== 'undefined' && hash[1] !== null)
                    url += '#' + hash[1];
            }
        }

        window.history.pushState('', '', url);
        return url;

    },

    removeParams: function (key, value) {
        url = window.location.href;
        url = url.replace('&' + key + '=' + value, '');
        url = url.replace(key + '=' + value, '');
        url = url.replace(key.replace('[]', '') + '=' + value, '');
        url = url.replace('?&', '?');
        try {
            split = url.split('?');
            if (split[1] == "" || !split[1]) {
                url = url.replace('?', '');
            }
        } catch (error) { console.log(error); }
        window.history.pushState('', '', url);
    },
    send: {
        get: function (url, callback, params = [], error = null) {
            loading.show(element.table());

            axios.get(url + params)
                .then(function (response) {
                    callback(response.data);
                })
                .catch(function (er) {
                    if (!!error) {
                        error(er);
                    } else {
                        console.log(er);
                    }
                })
                .finally(function () {
                    loading.remove();
                });
        },
        post: function () {

        }
    },
}

const element = {
    table: function () {
        return $('#table-body-data');
    }
}

function number(evt) {
    var theEvent = evt || window.event;
    if (theEvent.type === 'paste') {
        key = event.clipboardData.getData('text/plain');
    } else {
    // Handle key press
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
    }
    var regex = /[0-9]|\./;
    if( !regex.test(key) ) {
      theEvent.returnValue = false;
      if(theEvent.preventDefault) theEvent.preventDefault();
    }
  }
