<div class="search-filter fs-14 mb-3">
    <div class="row">
        <div class="col-md-12">
            <div class="filter-area flex">
                <div class="filter-item w-25">
                    <div class="title text-center">
                        <i class="fal fa-calendar"></i>
                        <span class="text">Thời gian</span>
                    </div>
                    <div class="item-search item-search-time mix-scrollbar">
                        <ul class="content-item-filter item-filter-radio">
                            <li>
                                <input checked id="r1" name="filter_date" ng-control="options" checked
                                    value="created_at" type="radio" class="ng-untouched ng-pristine ng-valid">
                                <label class="label-radio" for="r1">
                                    <span>Tạo đơn</span></label>
                            </li>
                            <li>
                                <input id="r2" name="filter_date" ng-control="options" type="radio" value="date_reciver"
                                    class="ng-untouched ng-pristine ng-valid">
                                <label class="label-radio" for="r2">
                                    <span>Nhận đơn</span></label>
                            </li>
                            <li>
                                <input id="r3" name="filter_date" ng-control="options" type="radio" value="date_send"
                                    class="ng-untouched ng-pristine ng-valid">
                                <label class="label-radio" for="r3">
                                    <span>Chuyển hàng</span></label>
                            </li>
                            <li>
                                <input id="r4" name="filter_date" ng-control="options" type="radio"
                                    value="date_accountant" class="ng-untouched ng-pristine ng-valid">
                                <label class="label-radio" for="r4">
                                    <span>Chuyển kế toán</span></label>
                            </li>
                        </ul>
                        <div class="search-time">
                            <div class="input-group mb-2">
                                <input id="timeDateRange1" readonly timepicker="true" autocomplete="new-password"
                                    autocorrect="off" autocapitalize="none" spellcheck="false"
                                    class="form-control fs-14" name="time_start" placeholder="Thời gian bắt đầu"
                                    type="search">
                                <div class="input-group-append pointer btn-click-remove-time-start-js">
                                    <div class="input-group-text fs-14"><i class="fal fa-times bold"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="search-time mt-2">
                            <div class="input-group mb-2">
                                <input id="timeDateRange" readonly timepicker="true" autocomplete="nope"
                                    autocorrect="off" autocapitalize="none" spellcheck="false"
                                    class="form-control fs-14" name="time_end" placeholder="Thời gian kết thúc"
                                    type="search">
                                <div class="input-group-append pointer btn-click-remove-time-end-js">
                                    <div class="input-group-text fs-14"><i class="fal fa-times bold"></i></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="filter-item w-30">
                    <div class="title text-center">
                        <i class="fal fa-book mr-1"></i>
                        <span class="text">Tình trạng đơn hàng</span>
                    </div>
                    <div class="item-search item-search-time mix-scrollbar">
                        <ul class="content-item-filter item-filter-radio">
                            @foreach($actions as $key => $action)
                            @if ($action->type == "filter_status")
                            <li class="checkbox_acount">
                                <input class="filter-filter_status-js" type="checkbox" value="{{$action->_id}}"
                                    id="{{$action->_id}}" name="filter_status">
                                <label class="label-checkbox" for="{{$action->_id}}">{{$action->text}}</label>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="filter-item w-15">
                    <div class="title text-center">
                        <i class="fal fa-truck mr-1" style="transform: scaleX(-1);"></i>
                        <span class="text">Vận chuyển</span>
                    </div>
                    <div class="item-search item-search-ship mix-scrollbar">
                        <ul class="content-item-filter item-filter-radio">
                            @foreach($actions as $key => $action)
                            @if ($action->type == "filter_ship")
                            <li class="checkbox_acount">
                                <input class="filter-filter_ship-js" type="checkbox" value="{{$action->_id}}"
                                    id="{{$action->_id}}" name="filter_ship">
                                <label class="label-checkbox" for="{{$action->_id}}">{{$action->text}}</label>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="filter-item w-15">
                    <div class="title text-center">
                        <i class="fal fa-file-check mr-1"></i>
                        <span class="text">Vận đơn & Kế toán</span>
                    </div>
                    <div class="item-search item-search-ship mix-scrollbar">
                        <ul class="content-item-filter item-filter-radio">
                            @foreach($actions as $key => $action)
                            @if ($action->type == "filter_confirm")
                            <li class="checkbox_acount">
                                <input class="filter-filter_status-js" type="checkbox" value="{{$action->_id}}"
                                    id="{{$action->_id}}" name="filter_status">
                                <label class="label-checkbox" for="{{$action->_id}}">{{$action->text}}</label>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </div>
                    <div class="title text-center">
                        <i class="fal fa-user mr-1"></i>
                        <span class="text">Lọc nhân viên tạo</span>
                    </div>
                    <div class="item-search item-search-time h-40 mix-scrollbar">
                        <ul class="content-item-filter item-filter-radio m-0">
                            <li class="checkbox_acount w-100 p-0">
                                <select name="user_id" class="input-control h-30 filter-user_id-js" id="">
                                    <option selected value="-1">Tất cả</option>
                                    @foreach($userMKT as $customer)
                                    <option value="{{$customer->_id}}">{{$customer->username}}</option>
                                    @endforeach
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="filter-item w-15">
                    <div class="title text-center">
                        <i class="fal fa-filter mr-1"></i>
                        <span class="text">Trạng thái đơn</span>
                    </div>
                    <div class="item-search item-search-ship mix-scrollbar">
                        <ul class="content-item-filter item-filter-radio">
                            @if ($user->type_account == "mkt")
                            <li class="checkbox_acount">
                                <input class="filter-user-reciver-js" type="checkbox" value="-1" id="user_reciver-1"
                                    name="user_reciver_id">
                                <label class="label-checkbox" for="user_reciver-1">Số chưa được chia</label>
                            </li>
                            @endif
                            <li class="checkbox_acount">
                                <input class="filter-reason-js" type="checkbox" value="-1" id="reason-1"
                                    name="reason[]">
                                <label class="label-checkbox" for="reason-1">Đơn hàng mới</label>
                            </li>
                            <li class="checkbox_acount">
                                <input class="filter-reason-js" type="checkbox" value="success" id="reasonsuccess"
                                    name="reason[]">
                                <label class="label-checkbox" for="reasonsuccess">Đơn đã chốt</label>
                            </li>
                            <li class="checkbox_acount">
                                <input class="filter-reason-js" type="checkbox" value="wait" id="reasonwait"
                                    name="reason[]">
                                <label class="label-checkbox" for="reasonwait">Chưa chốt được</label>
                            </li>
                            <li class="checkbox_acount">
                                <input class="filter-reason-js" type="checkbox" value="cancel" id="reasoncancel"
                                    name="reason[]">
                                <label class="label-checkbox" for="reasoncancel">Đơn huỷ</label>
                            </li>
                        </ul>
                    </div>
                    <div class="title text-center">
                        <i class="fal fa-user mr-1"></i>
                        <span class="text">Lọc nhân viên nhận</span>
                    </div>
                    <div class="item-search item-search-time h-40 mix-scrollbar">
                        <ul class="content-item-filter item-filter-radio m-0">
                            <li class="checkbox_acount w-100 p-0">
                                <select name="user_reciver_id" class="input-control h-30 filter-user_reciver_id-js"
                                    id="">
                                    <option selected value="-1">Tất cả</option>
                                    @foreach($userSale as $customer)
                                    <option value="{{$customer->_id}}">{{$customer->username}}</option>
                                    @endforeach
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>