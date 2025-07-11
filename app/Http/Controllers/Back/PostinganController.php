<?php

namespace App\Http\Controllers\Back;

use App\Models\Category;
use App\Models\Postingan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostinganRequest;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\UpdatePostinganRequest;
use Carbon\Carbon;

class PostinganController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $postingan = Postingan::with(['Category', 'user'])->latest();

            if (Auth::user()->role != 1) {
                $postingan->where('user_id', Auth::id());
            }

            \Carbon\Carbon::setlocale('id');
            return DataTables::of($postingan)
                ->addIndexColumn()
                ->addColumn('name', function ($postingan) {
                    return $postingan->user ? $postingan->user->name : '-';
                })
                ->addColumn('category_id', function ($postingan) {
                    return $postingan->Category->name;
                })
                ->addColumn('status', function ($postingan) {
                    switch ($postingan->approval_status) {
                        case 'pending':
                            return '<span class="badge bg-warning text-dark">Pending</span>';
                        case 'approved':
                            return '<span class="badge bg-success">Approved</span>';
                        case 'rejected':
                            return '<span class="badge bg-danger">Rejected</span>';
                        default:
                            return '-';
                    }
                })
                ->addColumn('created_at', function ($postingan) {
                    return \Carbon\Carbon::parse($postingan->created_at)->translatedFormat('d F Y');
                })
                ->addColumn('button', function ($postingan) {
                    return '<div class="d-flex justify-content-center align-items-center">
                <a href="postingan/' . $postingan->id . '" class="btn btn-info btn-sm mx-1" title="Detail">
                    <span data-feather="eye"></span>
                </a>
                <a href="postingan/' . $postingan->id . '/edit" class="btn btn-warning btn-sm mx-1" title="Edit">
                    <span data-feather="edit"></span>
                </a>
                <button type="button" onClick="deletePostingan(this)" data-id="' . $postingan->id . '" class="btn btn-danger btn-sm mx-1" title="Hapus">
                    <span data-feather="trash-2"></span>
                </button>
            </div>';
                })
                ->rawColumns(['category_id', 'status', 'button'])
                ->make();
        }

        return view('back.postingan.index');
    }

    public function create()
    {
        return view('back.postingan.create', [
            'categories' => Category::get(),
        ]);
    }

    public function store(PostinganRequest $request)
    {
        $data = $request->validated();
        //dd($data);

        $file = $request->file('img');
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('back', $fileName, 'public');

        $data['img'] = $fileName;
        $data['slug'] = Str::slug($data['title']);
        $data['user_id'] = Auth::user()->id;

        Postingan::create($data);

        return redirect(url('postingan'))->with('success', 'Data postingan berhasil ditambahkan');
    }

    public function show(string $id)
    {
        return view('back.postingan.show', [
            'postingan' => Postingan::find($id)
        ]);
    }

    public function edit(string $id)
    {
        return view('back.postingan.update', [
            'postingan' => Postingan::find($id),
            'categories' => Category::get()
        ]);
    }

    public function update(UpdatePostinganRequest $request, string $id)
    {
        $data = $request->validated();

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('back', $fileName, 'public');

            Storage::disk('public')->delete('back/' . $request->oldImg);
            $data['img'] = $fileName;
        } else {
            $data['img'] = $request->oldImg;
        }

        $data['slug'] = Str::slug($data['title']);

        // Pertahankan user_id yang asli
        $postingan = Postingan::find($id);
        $data['user_id'] = $postingan->user_id;

        $postingan->update($data);

        return redirect(url('postingan'))->with('success', 'Data postingan berhasil diperbarui');
    }


    public function destroy(string $id)
    {
        $data = Postingan::find($id);

        if (!$data) {
            return response()->json([
                'message' => 'Data postingan tidak ditemukan'
            ], 404);
        }

        if ($data->img && Storage::disk('public')->exists('back/' . $data->img)) {
            Storage::disk('public')->delete('back/' . $data->img);
        }

        $data->delete();

        return response()->json([
            'message' => 'Data postingan berhasil dihapus'
        ]);
    }

    public function joinRequests($id)
    {
        $postingan = Postingan::with('joinRequests')->findOrFail($id);

        if ($postingan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses');
        }

        return view('back.postingan.join-requests', compact('postingan'));
    }

    public function approveJoin($postinganId, $userId)
    {
        $postingan = Postingan::findOrFail($postinganId);

        if ($postingan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk menyetujui');
        }

        $postingan->joinRequests()->updateExistingPivot($userId, ['status' => 'approved']);

        return back()->with('success', 'Permintaan berhasil disetujui.');
    }

    public function rejectJoin($postinganId, $userId)
    {
        $postingan = Postingan::findOrFail($postinganId);

        if ($postingan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk menolak');
        }

        $postingan->joinRequests()->updateExistingPivot($userId, ['status' => 'rejected']);

        return back()->with('success', 'Permintaan berhasil ditolak.');
    }
}
