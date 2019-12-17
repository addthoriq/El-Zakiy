<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Teacher;
use App\Model\ProfileTeacher;
use App\Model\Course;
use Yajra\Datatables\Datatables;
use Form;
use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Avatar;

class UnonController extends Controller
{
    protected $folder     = 'pages.unons';
    protected $rdr        = '/unon';

    public function index()
    {
        $ajax     = route('unon.dbtb');
        return view('pages.unons.index', compact('ajax'));
    }

    public function dbTables(Request $request)
    {
        $data     = Teacher::where('status',0)->get();
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
                return "<span class='label label-primary'>Laki-Laki</span>";
            }else {
                return "<span class='label label-success'>Perempuan</span>";
            }
        })
        ->addColumn('action', function($index){
            $tag     = Form::open(["url"=>route('unon.aktif', $index->id), "method" => "PUT"]);
            $tag    .= "<a href=". route('unon.show', $index->id) ." class='btn btn-xs btn-info' ><i class='fa fa-search'></i> Detail</a> ";
            $tag    .= "<button type='submit' class='btn btn-xs btn-success' onclick='javascript:return confirm(`Apakah anda yakin ingin mengaktifkan ".$index->name." ?`)' ><i class='fa fa-check'></i> Aktifkan</button>";
            $tag    .= Form::close();
            return $tag;
        })
        ->rawColumns([
            'id', 'avatar', 'gender', 'action'
        ])
        ->make(true);
    }

    public function show($id)
    {
        $data      = Teacher::findOrFail($id);
        $histories = Course::where('teacher_id', $id)->get();
        return view($this->folder.'.show', compact('data', 'histories'));
    }

    public function aktif(Request $request, $id)
    {
        Teacher::findOrFail($id)->update([
            'status'    => 1
        ]);
        $data     = Teacher::findOrFail($id);
        return redirect('/teacher')->with('notif', $data->name.' berhasil di aktifkan kembali');
    }

    public function update(Request $request, $id)
    {
        if (empty($request->password)) {
            Teacher::find($id)->update([
                'email'    => $request->email
            ]);
        }else {
            Teacher::find($id)->update([
                'email'    => $request->email,
                'password'    => bcrypt($request->password)
            ]);
        }
        $data     = Teacher::findOrFail($id);
        return redirect()->route('unon.show', [$id])->with('notif', 'Akun Login '.$data->name.' berhasil diubah');
    }

    public function updateProfile(Request $request, $id)
    {
        $data     = Teacher::findOrFail($id)->update([
            'name'    => $request->name,
            'nip'    => $request->nip,
            'gender'    => $request->gender,
            'start_year'    => $request->start_year,
            'status'    => 0,
        ]);
        $ps     = ProfileTeacher::where('teacher_id', $id)->exists();
        if ($ps) {
            ProfileTeacher::findOrFail($id)->update([
                'nik'      => $request->nik,
                'address'  => $request->address,
                'religion' => $request->religion,
                'place_of_birth' => $request->place_of_birth,
                'date_of_birth' => $request->date_of_birth,
                'phone_number' => $request->phone_number
            ]);
        }else {
            $std     = Teacher::findOrFail($id);
            $prof     = new ProfileTeacher;
            $prof->teacher_id = $std->id;
            $prof->nik = $request->nik;
            $prof->address = $request->address;
            $prof->religion = $request->religion;
            $prof->place_of_birth = $request->place_of_birth;
            $prof->date_of_birth = $request->date_of_birth;
            $prof->phone_number = $request->phone_number;
            $prof->save();
        }
        $guru     = Teacher::findOrFail($id);
        return redirect()->route('unon.show',[$id])->with('notif', 'Data Informasi '.$guru->name.' berhasil diubah');
    }

    public function updateAva(Request $request, $id)
    {
        $data     = Teacher::findOrFail($id);
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
}
