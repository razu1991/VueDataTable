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
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Display user listing page (previous)
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
        $request = request();

        // pagination limit
        $limit = $request->has('limit') ? $request->limit : 5;

        $users = User::orderBy('id', 'desc');

        if ($request->has('name')) {
            $users = $users->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('email')) {
            $users = $users->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->has('phone')) {
            $users = $users->where('phone', 'like', '%' . $request->phone . '%');
        }

        $users = $users->paginate($limit);

        return $users;

    }

    /**
     * Print user info  in csv, pdf or excel format
     *
     * @param $type
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function printType($type)
    {
        $request = request();

        $name  = '';
        $email = '';
        $phone = '';


        if ($request->name && $request->name != "null") {
            $name = $request->name;
        }

        if ($request->email && $request->email != "null") {
            $email = $request->email;
        }

        if ($request->phone && $request->phone != "null") {
            $phone = $request->phone;
        }


        if ($type == 'csv') {
            return Excel::download(new UsersExport($name, $email, $phone), "users.$type");
        } else if ($type == 'pdf') {
            return Excel::download(new UsersExport($name, $email, $phone), "users.$type");
        } else {
            return Excel::download(new UsersExport($name, $email, $phone), "users.xlsx");
        }
    }
}
