<div class="welcome__block__datetime row my-2">
    <div class="col-12">
        <div class="time">
            <span data-element-name="hour" class="time__label hour">00</span>
            <span data-element-name="min" class="time__label min">00</span>
            <span data-element-name="sec" class="time__label sec">00</span>
        </div>
    </div>
    <div class="col-12">
        <div class="location">
            <div class="w-100e d-flex">
                <span class="text-transform-uppercase">Location: </span>
                <span class="mx-1">{{$location['region'] ?? ""}}</span>
                <div><span data-element-name="day">00</span>/<span data-element-name="month">00</span>/<span data-element-name="year">0000</span></div>
            </div>
            <div class="w-100">
                <span>TIMEZONE: {{$location['timezone'] ?? ""}}</span>
            </div>
        </div>
    </div>
</div>

<script>
    var today;
    window.onload = function () {
        let year = document.querySelector('[data-element-name="year"]');
        let month = document.querySelector('[data-element-name="month"]');
        let day = document.querySelector('[data-element-name="day"]');
        let hour = document.querySelector('[data-element-name="hour"]');
        let min = document.querySelector('[data-element-name="min"]');
        let sec = document.querySelector('[data-element-name="sec"]');
        setInterval(() => {
            today = new Date();
            hour.innerHTML = today.getHours().toString().length == 1 ? `0${today.getHours()}` : today.getHours();
            min.innerHTML = today.getMinutes().toString().length == 1 ? `0${today.getMinutes()}` : today.getMinutes();
            sec.innerHTML = today.getSeconds().toString().length == 1 ? `0${today.getSeconds()}` : today.getSeconds();

            year.innerHTML = today.getFullYear().toString().length == 1 ? `0${today.getFullYear()}` : today.getFullYear();
            month.innerHTML = today.getMonth().toString().length == 1 ? `0${today.getMonth() + 1}` : today.getMonth() + 1;
            day.innerHTML = today.getDate().toString().length == 1 ? `0${today.getDate()}` : today.getDate();
        }, 1000);
    }
</script>