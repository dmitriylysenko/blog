<?php
/**
 * Created by PhpStorm.
 * User: Dmitriy
 * Date: 16.05.2018
 * Time: 23:46
 */

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        return view('pages.profile', ['user' => $user]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'   => 'required',
            'email'  => [
                'required',
                'email',
                Rule::unique('users')->ignore(\Auth::user()->id),
            ],
            'avatar' => 'nullable|image'
        ]);


        $user = \Auth::user();
        $user->edit($request->all());
        $user->generatePassword($request->get('password'));
        $user->uploadAvatar($request->file('avatar'));

        return redirect()->back()->with('status', 'Профиль успешно обновлён');
    }
}