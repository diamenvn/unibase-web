<div class="menu__block">
    <div class="d-flex align-items-center">
        <div class="col p-0">
            <a href="{{ $uri ?? '#' }}"
                class="menu__item d-flex align-items-center {{ strpos(Route::current()->getName(), str_replace('/', '.', $uri)) !== false ? 'active' : '' }}">
                <div class="d-flex flex-1"><i class="mr-2 menu__item__icon {{ $icon ?? '' }}"></i>
                    <h4 class="menu__title m-0">{{ $title }}</h4>
                </div>
                <small>{{ $number ?? "" }}</small>
            </a>
        </div>
        @if (isset($items) && count($items) > 0)
            <div class="col-auto">
                <svg width="10px" height="10px" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Dribbble-Light-Preview" transform="translate(-220.000000, -6684.000000)" fill="#fff">
                            <g id="icons" transform="translate(56.000000, 160.000000)">
                                <path
                                    d="M164.292308,6524.36583 L164.292308,6524.36583 C163.902564,6524.77071 163.902564,6525.42619 164.292308,6525.83004 L172.555873,6534.39267 C173.33636,6535.20244 174.602528,6535.20244 175.383014,6534.39267 L183.70754,6525.76791 C184.093286,6525.36716 184.098283,6524.71997 183.717533,6524.31405 C183.328789,6523.89985 182.68821,6523.89467 182.29347,6524.30266 L174.676479,6532.19636 C174.285736,6532.60124 173.653152,6532.60124 173.262409,6532.19636 L165.705379,6524.36583 C165.315635,6523.96094 164.683051,6523.96094 164.292308,6524.36583"
                                    id="arrow_down-[#338]">

                                </path>
                            </g>
                        </g>
                    </g>
                </svg>
            </div>
        @endif
    </div>
    @if (isset($items))
        <ul class="menu__list">
            @foreach ($items as $item)
                <div
                    class="menu__item {{ strpos(Route::current()->getName(), str_replace('/', '.', $item['uri'])) !== false ? 'active' : '' }}">
                    <ul>
                        <li><a class="text-one-line" href="{{ $item['uri'] }}">{{ $item['label'] }}</a></li>
                    </ul>
                </div>
            @endforeach
        </ul>
    @endif
</div>
