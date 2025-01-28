<?php

namespace App\Http\Controllers\AdminController\Employee\AccountSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountDetails extends Controller
{
    protected $isRegisterEmployee = false;

    public function view($rq,$components,$account)
    {
        $isRegisterEmployee = $this->isRegisterEmployee;
        $isSystemAdmin = true;
        $emp_account = $account;
        return view($components.'employee-account', compact('isRegisterEmployee','emp_account','isSystemAdmin'))->render();
    }
}
