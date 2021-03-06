<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Role;
use App\Model\SchoolYear;
use App\Model\Course;
use App\Model\ProfileUser;
use Yajra\Datatables\Datatables;
use Form;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Laravolt\Avatar\Avatar;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected $folder     = 'admin.teachers';
    protected $rdr        = '/teacher';

    public function index()
    {
        if (Gate::allows('index-teacher')) {
            $ajax     = route('teacher.dbtb');
            return view('admin.teachers.index', compact('ajax'));
        }else {
            abort(403);
        }
    }

    public function dbTables(Request $request)
    {
        $data     = User::where([['role_id',4],['status',1]])->get();
        return Datatables::of($data)
        ->editColumn('avatar', function($index){
            if ($index->avatar) {
                return "<img class='rounded-circle' src=".Storage::url($index->avatar)." width='40px' height='40px' />";
            }else {
                $ava     = new Avatar;
                return "<img class='rounded-circle' src=".$ava->create($index->name)->toBase64() ." width='40px' height='40px' />";
            }
        })
        ->editColumn('gender',function($index){
            if ($index->gender == 'L') {
                return "<span class='badge badge-pill badge-primary'>Laki-Laki</span>";
            }else {
                return "<span class='badge badge-pill badge-danger'>Perempuan</span>";
            }
        })
        ->addColumn('action', function($index){
            $tag     = Form::open(["url"=>route('teacher.nonaktif', $index->id), "method" => "PUT"]);
            $tag    .= "<a href=". route('teacher.show', $index->id) ." class='btn btn-xs btn-warning text-white' ><i class='fa fa-search'></i></a> ";
            $tag    .= "<button type='submit' class='btn btn-xs btn-danger' onclick='javascript:return confirm(`Apakah anda yakin ingin menonaktifkan ".$index->name." dari guru?`)' ><i class='fa fa-minus-square'></i></button>";
            $tag    .= Form::close();
            return $tag;
        })
        ->rawColumns([
            'id', 'avatar', 'gender', 'action'
        ])
        ->make(true);
    }

    public function teacher()
    {
        //JSON untuk AutoComplete
        $tc     = User::where('role_id',4)->get();
        return response()->json($tc);
    }

    public function create()
    {
        if (Gate::allows('create-teacher')) {
            return view($this->folder.'.create');
        }else {
            abort(403);
        }
    }

    public function store(Request $request)
    {
        if (Gate::allows('create-teacher')) {
            $data                  = new User;
            $data->role_id         = 4;
            $data->nip             = $request->nip;
            $data->name            = $request->name;
            $data->start_year      = $request->start_year;
            $data->gender          = $request->gender;
            $data->email           = $request->email;
            $data->password        = bcrypt($request->password);
            $ava                   = $request->file('avatar');
            if ($ava) {
                $ava_path          = $ava->store('ava_teacher', 'public');
                $data->avatar      = $ava_path;
            }
            $data->status          = 1;
            $data->save();
            return redirect($this->rdr)->with('notif', 'Data Guru berhasil ditambahkan');
        }else {
            abort(403);
        }
    }

    public function show($id)
    {
        if (Gate::allows('update-teacher')) {
            $admin     = Role::findOrFail(1);
            $op1       = Role::findOrFail(2);
            $op2       = Role::findOrFail(3);
            $data      = User::findOrFail($id);
            $years     = SchoolYear::all();
            $histories = Course::where('user_id', $id)->orderBy('created_at', 'desc')->first();
            $history   = Course::where('user_id', $id)->get();
            return view($this->folder.'.show', compact('data', 'admin', 'op1', 'op2', 'histories', 'history', 'years'));
        }else {
            abort(403);
        }
    }

    public function update(Request $request, $id)
    {
        if (empty($request->password)) {
            User::find($id)->update([
                'email'    => $request->email
            ]);
        }else {
            User::find($id)->update([
                'email'    => $request->email,
                'password'    => bcrypt($request->password)
            ]);
        }
        $guru     = User::findOrFail($id);
        return redirect()->route('teacher.show', [$id])->with('notif', 'Akun Login '.$guru->name.' berhasil diubah');
    }

    public function admin(Request $request, $id)
    {
        $data     = User::findOrFail($id);
        $datu     = User::where('user_id', $id)->exists();
        if ($data->id == $datu) {
            User::where('user_id', $id)->update([
                'role_id'    => $request->role_id,
                'email'      => $request->email,
                'password'   => bcrypt($request->password)
            ]);
            return redirect()->route('teacher.show',[$id])->with('notif', $data->name.' berhasil diubah menjadi Admin');
        }else {
            $user     = new User;
            $user->role_id = 1;
            $user->user_id = $data->id;
            $user->name    = $data->name;
            $user->email   = $data->email;
            $ps       = $request->password;
            if ($ps) {
                $user->password = bcrypt($ps);
            }else {
                $user->password = $data->password;
            }
            $user->avatar = $data->avatar;
            $user->status   = 1;
            $user->save();
            return redirect()->route('teacher.show',[$id])->with('notif', 'Data User berhasil ditambahkan menjadi Admin');
        }
    }
    public function op(Request $request, $id)
    {
        $data     = User::findOrFail($id);
        $datu     = User::where('user_id', $id)->exists();
        if ($data->id == $datu) {
            User::where('user_id', $id)->update([
                'role_id'    => $request->role_id,
                'email'      => $request->email,
                'password'   => bcrypt($request->password)
            ]);
            return redirect()->route('teacher.show',[$id])->with('notif', $data->name.' berhasil diubah menjadi Operator 1');
        }else {
            $user     = new User;
            $user->role_id = 2;
            $user->user_id = $data->id;
            $user->name    = $data->name;
            $user->email   = $data->email;
            $ps       = $request->password;
            if ($ps) {
                $user->password = $ps;
            }else {
                $user->password = $data->password;
            }
            $user->avatar = $data->avatar;
            $user->status   = 1;
            $user->save();
            return redirect()->route('teacher.show',[$id])->with('notif', 'Data User berhasil ditambahkan menjadi Operator 1');
        }
    }

    public function ope(Request $request, $id)
    {
        $data     = User::findOrFail($id);
        $datu     = User::where('user_id', $id)->exists();
        if ($data->id == $datu) {
            User::where('user_id', $id)->update([
                'role_id'    => $request->role_id,
                'email'      => $request->email,
                'password'   => bcrypt($request->password)
            ]);
            return redirect()->route('teacher.show',[$id])->with('notif', $data->name.' berhasil diubah menjadi Operator 2');
        }else {
            $user     = new User;
            $user->role_id = 3;
            $user->user_id = $data->id;
            $user->name    = $data->name;
            $user->email   = $data->email;
            $ps       = $request->password;
            if ($ps) {
                $user->password = $ps;
            }else {
                $user->password = $data->password;
            }
            $user->avatar = $data->avatar;
            $user->status   = 1;
            $user->save();
            return redirect()->route('teacher.show',[$id])->with('notif', 'Data User berhasil ditambahkan menjadi Operator 2');
        }
    }

    public function nonRole($id)
    {
        User::where('user_id',$id)->update([
            'status'    => 0
        ]);
        $data     = User::where('user_id', $id)->first();
        return redirect()->route('teacher.show',[$id])->with('notif', $data->name.' berhasil dinonaktifkan dari User');
    }

    public function updateProfile(Request $request, $id)
    {
        $g = User::findOrFail($id);
        $data     = User::findOrFail($id)->update([
            'name'    => $request->name,
            'nip'    => $request->nip,
            'gender'    => $g->gender,
            'start_year'    => $request->start_year,
            'status'    => $g->status,
        ]);
        $ps     = ProfileUser::where('user_id', $id)->exists();
        if ($ps) {
            ProfileUser::where('user_id',$g->id)->update([
                'nik'      => $request->nik,
                'address'  => $request->address,
                'religion' => $request->religion,
                'place_of_birth' => $request->place_of_birth,
                'date_of_birth' => $request->date_of_birth,
                'phone_number' => $request->phone_number
            ]);
        }else {
            $prof     = new ProfileUser;
            $prof->user_id = $g->id;
            $prof->nik = $request->nik;
            $prof->address = $request->address;
            $prof->religion = $request->religion;
            $prof->place_of_birth = $request->place_of_birth;
            $prof->date_of_birth = $request->date_of_birth;
            $prof->phone_number = $request->phone_number;
            $prof->save();
        }
            return redirect()->route('teacher.show',[$id])->with('notif', 'Data Informasi '.$g->name.' berhasil diubah');
    }

    public function updateAva(Request $request, $id)
    {
        $data     = User::findOrFail($id);
        $ava      = $request->file('avatar');
        if ($ava) {
            if ($data->avatar && file_exists(storage_path('app/public/'.$data->avatar)) ) {
                \Storage::delete('public/'.$data->avatar);
            }
            $ava_path = $ava->store('ava_teacher', 'public'); //$ava->store('nama_folder', 'bersifat public')
            $data->avatar = $ava_path;
        }
        $data->save();
        return redirect()->route('teacher.show', [$id])->with('notif', 'Poto Profil '.$data->name.' berhasil diubah');
    }

    public function nonCourse(Request $request, $id)
    {
        Course::where('user_id', $id)->update([
            'status'    => 0
        ]);
        return redirect()->route('teacher.show', [$id])->with('notif', 'Riwayat Mata Pelajaran berhasil di akhiri');
    }

    public function onCourse(Request $request, $id)
    {
        Course::where('user_id', $id)->update([
            'status'    => 1
        ]);
        return redirect()->route('teacher.show', [$id])->with('notif', 'Riwayat Mata Pelajaran berhasil di aktifkan');
    }

    public function nonaktif(Request $request, $id)
    {
        User::findOrFail($id)->update([
            'status'    => 0,
        ]);
        $user = User::where('user_id',$id)->exists();
        if ($user) {
            User::where('user_id', $id)->delete();
        }
        Course::where('user_id', $id)->update([
            'status'    => 0
        ]);
        $data = User::findOrFail($id);
        return redirect($this->rdr)->with('notif', $data->name.' berhasil di nonaktifkan!');
    }
}
