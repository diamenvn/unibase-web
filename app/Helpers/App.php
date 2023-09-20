<?php

namespace App\Helpers;
use App\Helpers\User;

class App
{
    public static function arrayMerge($array)
    {
        if (!empty($array)) {
            return array_unique(array_merge(...$array));
        } else {
            return [];
        }
    }
    public static function filterByPermissionCustomer($customer, $request)
    {
        if ($customer->company->company_type == "all") {
            $request = App::filterByNotConnectCompany($customer, $request);
        }else{
            
        }

        return $request;
    }

    public static function filterByNotConnectCompany($user, $request)
    {
        if (User::isAdminMkt($user) || User::isAdminSale($user) || User::isVandon($user)) {
            $request['company_id'] = $user->company_id;
        } elseif (User::isMkt($user)) {
            $filter = App::getMembersGroupByCustomer($user);
            $request['user_create_id'] = $filter;
        } elseif (User::isSale($user)) {
            if ($user->company->divideOrder == 1) {
                $filter = App::getMembersGroupByCustomer($user);
                $request['user_reciver_id'] = $filter;
            }else{
                $request['user_reciver_id'] = array(null, $user->_id);
            }
        }

        if (User::isCare($user)) {
            $request['user_care_id'] = array(null, $user->_id);
            $request['is_order_care'] = true;
        }

        if (!empty($request['user_id'])) {
            $request['user_create_id'] = $request['user_id'];
        }

        return $request;
    }

    public static function getMembersGroupByCustomer($customer)
    {
        // $group = $this->setting->findGroupByCustomerId($customer->_id)->pluck('members');
        $group = collect();
        $group->push(array($customer->_id));
        $groups = App::arrayMerge($group->toArray());
        return $groups;
    }
}
