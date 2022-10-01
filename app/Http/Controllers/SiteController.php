<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SiteController extends Controller
{
    /**
     * Display user listing page
     *
     * @return  \Illuminate\Contracts\View\View
     */
    public function userList()
    {
        return view('users');
    }

    /**
     * Return user data in json format
     *
     * @return mixed
     */
    public function userJsonData()
    {
        $users = User::get();
        return response(['users' => $users]);
    }

    public function printType($type)
    {
        if ($type == 'csv') {
            return Excel::download(new UsersExport(),"users.$type");
        } else if($type == 'pdf'){
            return Excel::download(new UsersExport(),"users.$type");
        } else{
            return Excel::download(new UsersExport(),"users.xlsx");
        }
    }
}
