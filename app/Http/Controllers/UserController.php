<?php

namespace App\Http\Controllers;

use App\Mail\RegMail;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('auth', [
            'except' => ['index', 'show', 'store', 'create', 'confirmEmailToken']
        ]);
        $this->middleware('guest', [
            'only' => ['create', 'store']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $users = User::query()->paginate(10);
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('user.create');
    }


    /**
     ** Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) {

        $data = $this->validate($request, [
            'name'     => 'required|min:3',
            'email'    => 'required|unique:users|email',
            'password' => 'required|min:5|confirmed',
        ]);
        $data['password'] = bcrypt($data['password']);
        // 添加用户
        $user = User::query()->create($data);
        // 自动登录
        //        \Auth::attempt([
        //            'email' => $request->email,
        //            'password' => $request->password
        //        ]);
        // 发送邮件
        \Mail::to($user)->send(new RegMail($user));
        session()->flash('success', '请查看邮箱完成验证');
        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user) {
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) {
        $this->authorize('update', $user);

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user) {

        $this->validate($request, [
            'name'     => 'required|min:3',
            'password' => 'nullable|min:5|confirmed',
        ]);
        $user->name = $request->name;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        session()->flash('success', '修改成功');
        return redirect()->route('user.show', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        $this->authorize('delete', $user);
        $user->delete();
        session()->flash('success', '删除成功');
        return redirect()->route('user.index');
    }

    public function confirmEmailToken($token) {
        $user = User::query()->where('email_token', $token)->first();
        if ($user) {
            $user->email_active = true;
            $user->save();
            session()->flash('验证成功');
            // 自动登录
            //\Auth::attempt($user->toArray());
            \Auth::login($user);

            return redirect('/');
        }
        session()->flash('验证失败');
        return redirect('/');
    }
}
