<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\User;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $users = DB::table('users')->join('info_candidates', 'users.id', '=', 'info_candidates.user_id')
            ->select('users.*', 'info_candidates.*')
            ->where('users.id', $request->user()->id)
            ->get();
        return response()->json([
            'status' => true,
            'users' => $users,
        ], 200);
    }

    public function uploadPhoto(Request $request)
    {
        $photosIni = DB::table('info_candidates')->where('user_id', $request->user()->id)->value('id');

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/photos/'), $name);

            DB::table('info_candidates')->where('id', $photosIni)
                ->update([
                    'photo' => $name,
                ]);

            return response()->json([
                'status' => true,
                'photo' => asset('storage/photos/' . $name),
            ], 200);
        }
        return response()->json(['success' => false, 'message' => 'Aucune image reçue'], 400);
    }

    public function uploadCV(Request $request)
    {
        $CVIni = DB::table('info_candidates')->where('user_id', $request->user()->id)->value('id');

        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/cvs/'), $name);

            DB::table('info_candidates')->where('id', $CVIni)
                ->update([
                    'cv' => $name,
                ]);

            return response()->json([
                'status' => true,
                'cv' => asset('storage/cvs/' . $name),
            ], 200);
        }
        return response()->json(['success' => false, 'message' => 'Fichier image reçue'], 400);
    }
}
