<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Section;
use App\Model\Course;
use App\Model\FileSection;
use App\Model\Chapter;
use App\Model\ListCourse;
use App\Model\Classroom;
use Yajra\Datatables\Datatables;
use Form;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    public function index()
    {
        if (Gate::allows('index-section')) {
            $listCourse = ListCourse::all();
            $classroom = Classroom::all();
            $ajax     = route('section.dbtb');
            return view('admin.sections.listSection', compact('ajax', 'listCourse', 'classroom'));
        }else {
            abort(403);
        }
    }

    public function dbTables(Request $Request)
    {
        $data     = Section::all();
        return Datatables::of($data)
        ->editColumn('created_at', function ($index) {
            return $index->created_at->format('d M Y');
        })
        ->addColumn('course_id', function ($index) {
            return isset($index->course->list_course) ? $index->course->list_course." / ".$index->course->classroom : '-';
        })
        ->addColumn('file', function($index) {
            $isi    = FileSection::where('section_id', $index->id)->get();
            $count  = count($isi);
            if ($count < 1) {
                return "<span class='badge badge-warning'>Kosong</span>";
            } else {
                return "<span class='badge badge-primary'>".$count." File</span>";
            }
        })
        ->addColumn('user_id', function ($index) {
            return isset($index->course->user->name) ? $index->course->user->name : '-';
        })
        ->addColumn('action', function($index){

            $tag     = Form::open(["url"=>route("section.destroy", $index->id), "method" => "DELETE"]);
            $tag    .= "<div class='btn-group'>";
            $tag    .= "<a href='".route('section.show', $index->id)."' class='btn btn-xs btn-primary' data-toggle='tooltip' data-placement='top' title='Lihat'><i class='fa fa-eye'></i></a>";
            $tag    .= "<button type='submit' class='btn btn-xs btn-danger' data-toggle='tooltip' data-placement='top' title='Hapus'><i class='fas fa-trash'></i></button>";
            $tag    .= "</div>";
            $tag    .= Form::close();
            return $tag;
        })
        ->rawColumns([
            'id', 'file', 'action'
        ])
        ->make(true);
    }

    public function detail($id)
    {
        if (Gate::allows('update-section')) {
            // untuk menemukan data
            $files          = FileSection::find($id);
            $section        = Section::find($files->section_id);
            $course         = Course::find($section->course_id);
            $fileSections   = fileSection::where('section_id', $section->id)->get();
            return view('admin.sections.fileView', compact('course', 'fileSections', 'files', 'section'));
        }else {
            abort(403);
        }
    }

    public function show($id)
    {
        if (Gate::allows('update-section')) {
            $section = Section::find($id);
            $fileSections   = FileSection::where('section_id', $id)->get();
            return view('admin.sections.index2', compact('section', 'fileSections'));
        }else {
            abort(403);
        }
    }

    public function add($id)
    {
        if (Gate::allows('create-section')) {
            $course = Course::find($id);
            return view('admin.sections.create', compact('course'));
        }else {
            abort(403);
        }
    }

    public function create()
    {
        if (Gate::allows('create-section')) {
            $course = Course::all();
            $listCourse = ListCourse::all();
            $classroom = Classroom::all();
            return view('admin.sections.create2', compact('course', 'listCourse', 'classroom'));
        }else {
            abort(403);
        }
    }

    public function store(Request $request)
    {
        if (Gate::allows('create-section')) {
            $filename       = $_FILES['file'];
            $count          = count($request->file('file'));
            $data = new Section;
            $data->course_id     = $request->course_id;
            $data->title         = $request->title;
            $data->description   = $request->description;
            $data->save();

            $section    = Section::orderBy('id', 'DESC')->first();
            for ($i=0; $i<$count ; $i++) {
                $file               = new FileSection;

                $fileStore = $request->file;
                if ($fileStore) {
                    $file_path = $fileStore[$i]->store('file_section', 'public');
                }

                $file->section_id   = $section->id;
                $file->title        = $filename['name'][$i];
                $file->name_file    = $file_path;
                $file->type_file    = $filename['type'][$i];
                $file->save();
            }
            return redirect()->route('section.index')->with('notif', 'Data Materi berhasil ditambahkan');
        }else {
            abort(403);
        }
    }

    public function fileDownload($id)
    {
        $file = FileSection::findOrFail($id);
        $link = $file->name_file;
        $catch = public_path()."/".$link;
        return response()->download(storage_path("app/public/{$link}"));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        if (Gate::allows('update-section')) {
            Section::findOrFail($id)->update([
                'title'         => $request->title,
                'description'   => $request->description,
            ]);

            if ($request->sectionId) {
                return redirect()->route('section.show', $request->sectionId)->with('notif', 'Data Materi berhasil diubah');
            } else {
                return redirect()->route('section.detail', $request->fileId)->with('notif', 'Data Materi berhasil diubah');
            }
        }else {
            abort(403);
        }
    }

    public function addFile(Request $request, $id)
    {
        if (Gate::allows('create-section')) {
            $filename       = $_FILES['file'];
            $count          = count($request->file('file'));

            for ($i=0; $i < $count; $i++) {
                $data               = new FileSection;

                $fileStore = $request->file;
                if ($fileStore) {
                    $file_path = $fileStore[$i]->store('file_section', 'public');
                }

                $data->section_id   = $id;
                $data->title        = $filename['name'][$i];
                $data->name_file    = $file_path;
                $data->type_file    = $filename['type'][$i];
                $data->save();
            }

            if ($request->sectionId) {
                return redirect()->route('section.show', $request->sectionId)->with('notif', 'Data File Materi berhasil ditambah');
            } else {
                return redirect()->route('section.detail', $request->fileId)->with('notif', 'Data File Materi berhasil ditambah');
            }
        }else {
            abort(403);
        }
    }

    public function deleteFileHome($id)
    {
        $file       = FileSection::find($id);
        $section    = Section::find($file->section_id);

        //Deleting File
        $data       = FileSection::findOrFail($id);
        $data->delete();

        $fileCheck  = FileSection::where('section_id', $section->id)->first();

        return redirect()->route('section.show', [$section->id])->with('notif', 'Data File berhasil dihapus');
    }

    public function deleteFile($id)
    {
        $file       = FileSection::find($id);
        $section    = Section::find($file->section_id);

        //Deleting File
        $data       = FileSection::findOrFail($id);
        $data->delete();

        $fileCheck  = FileSection::where('section_id', $section->id)->first();

        if (!empty($fileCheck)) {
            $post   = $fileCheck->id;
            return redirect()->route('section.detail', [$post])->with('notif', 'Data File berhasil dihapus');
        } else {
            $post   = $section->id;
            return redirect()->route('section.show', [$post])->with('notif', 'Data File berhasil dihapus');
        }
    }

    public function destroy($id)
    {
        $data       = Section::findOrFail($id);
        $data->delete();
        return redirect()->route('section.index')->with('notif', 'Data Materi berhasil dihapus');
    }
}
