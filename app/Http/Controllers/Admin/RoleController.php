<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Role;
use App\Model\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $folder     = 'admin.roles';
    public function index()
    {
        if (Gate::allows('index-role')) {
            $roles     = Role::all();
            return view($this->folder.'.index', compact('roles'));
        }else {
            abort(403);
        }
    }
    public function update(Request $request, $id)
    {
        if (Gate::allows('update-role')) {
            Role::findOrFail($id)->update([
                'name'     => $request->name,
                'slug'     => Str::slug($request->name,'-')
            ]);
            return redirect('/role')->with('notif', 'Role berhasil diubah');
        }else {
            abort(403);
        }
    }
    public function home()
    {
        if (Gate::allows('index-permission')) {
            $roles     = Role::get();
            $perms     = Permission::get();
            return view($this->folder.'.perm-index', compact('roles', 'perms'));
        }else {
            abort(403);
        }
    }
    public function ubah(Request $request, $id)
    {
        if (Gate::allows('update-permission')) {
            $role = Role::findOrFail($id);
            $role->permissions()->sync($request->permissions);
            return redirect('/perm')->with('notif', 'Hak Akses berhasil diubah');
        }else {
            abort(403);
        }
    }
}
