<?php

namespace App\Http\Controllers;

use App\Commons\Responses;
use App\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PresenceController extends Controller
{
    //
    public function presenceIn(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $presence = new Presence();
            $presence->name = $user->name;
            $presence->user_id = $user->id;
            $presence->presence_in = Carbon::now();
            $presence->save();
            DB::commit();
            return response(Responses::success('Success Presence In'), 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return Responses::errorServer();
        }
    }

    public function presenceOut(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $userId = $user->id;
            $presence = Presence::where('user_id', $userId)->latest()->first();
            $diff = $this->getDifferenceTime($presence->presence_in);
            $presence->name = $user->name;
            $presence->user_id = $user->id;
            $presence->presence_out = Carbon::now();
            $presence->duration = $diff;
            $presence->save();
            DB::commit();
            return response(Responses::success('Success Presence OUT'), 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return Responses::errorServer();
        }
    }

    public function detail(Request $request, $id)
    {
    }

    public function details(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $presence = Presence::where('user_id', $userId)->get();
        return response(Responses::success('Success get Data', $presence), 200);
    }

    public function cekStatus(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $presence = Presence::where('user_id', $userId)->latest()->first();
        if ($presence == null) {
            $data = 0;
        } else {
            if ($presence->presence_out == null) {
                $data = 1;
            } else {
                $data = 0;
            }
        }
        return response(Responses::success('validation status', $data), 200);
    }

    private function getDifferenceTime($date1)
    {
        $now = Carbon::now();
        $first = Carbon::parse($date1);
        return $first->diffInSeconds($now);
    }

    private function getDifferenceMinute($date)
    {
        $now = Carbon::now();
        $first = Carbon::parse($date);
        return $first->diffInMinutes($now);
    }
}
