<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exercise;
use App\Models\Userexercise;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class UserexerciseController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    public function index()
    {
        //
    }

    public function myExercise(Request $request)
    {
        // $userEmail = 'mr.eko.handriyanto@gmail.com';
        $userEmail = $request->email;
        $isactive = '1';

        $userexercises = DB::select(
            'SELECT 
                MD5(ue.id) as xid,
                MD5(e.id) as x2id,
                e.quest_amount as quest, 
                e.title, 
                e.description, 
                concat(trim(left(e.description, 100)), \'...\') as description_trim, 
                e.start_date,
                e.end_date,
                DATE_FORMAT(e.start_date, \'%M %e, %Y\') as fstart_date,
                DATE_FORMAT(e.end_date, \'%M %e, %Y\') as fend_date,
                ue.score,
                e.total_score,
                ue.paid,
                (
                    SELECT FLOOR(RAND()*(30-COUNT(*)+1)+COUNT(*)) FROM userexercises ue2 WHERE ue2.exercise_id = e.id AND ue2.PAID = 1
                ) AS sold
            FROM exercises e
            INNER JOIN users u ON u.email = ?
            INNER JOIN userexercises ue ON ue.exercise_id = e.id AND ue.user_id = u.id
            WHERE e.isactive = ? 
            AND ue.paid = 1
            ORDER BY e.end_date ASC',
            [$userEmail, $isactive]
        );

        return response()->json(['userexercises' => $userexercises]);
        // return Userexercise::paginate(10);
    }

    public function store(Request $request)
    {
        $email = $request->email;
        $md5id = $request->xid;

        $user = User::where('email', $email)->select('id')->first();
        $exercise = Exercise::whereRaw('MD5(id) = ?', [$md5id])->first();

        $create = Userexercise::create([
            'user_id' => $user->id,
            'exercise_id' => $exercise->id,
            'price' => $request->price,
            'discount' => $request->discount,
            'paid' => $request->paid,
            'start_date' => date('Y-m-d h:i:s', strtotime($request->start_date)),
            'end_date' => date('Y-m-d h:i:s', strtotime($request->end_date)),
            'created_by' => $email,
            'updated_by' => $email,
        ]);

        if ($create) {
            return response()->json(['greeting' => 'Congratulation!', 'message' => 'You have 1 order to purchase a package of tryout exercise. Please follow next instruction to complete the process. Thank you.', 'package' => $exercise->title]);
        } else {
            return response()->json(['message' => 'Proccess Failed.'], 500);
        }
    }

    public function payment(Request $request)
    {
        $email = $request->email;
        $md5id = $request->xid;

        $userexercise = Userexercise::whereRaw('MD5(id) = ?', [$md5id])->first();
        $exercise = Exercise::where('id', $userexercise->exercise_id)->first();

        $update = $userexercise->update([
            'paid' => 1,
            'updated_by' => $email,
        ]);

        if ($update) {
            return response()->json(['greeting' => 'Congratulation!', 'message' => 'You have purchased a package of tryout exercise. Please take your time to accomplish your task. Enjoy your package. Thank you.', 'package' => $exercise->title]);
        } else {
            return response()->json(['message' => 'Proccess Failed.'], 500);
        }
    }

    public function show(Userexercise $userexercise)
    {
        // return $userexercise;
    }

    public function update(Request $request, Userexercise $userexercise)
    {
        // Gate::authorize('modify', $userexercise);

        // $data = $request->validate([
        //     'paid' => ['required', 'numeric'],
        //     'user_id' => ['required', 'exists:users,id'],
        //     'exercise_id' => ['required', 'exists:exercises,id'],
        // ]);

        // $userexercise->update($data);

        // return $userexercise;
    }

    public function destroy(Userexercise $userexercise)
    {
        // Gate::authorize('modify', $userexercise);

        // $userexercise->delete();

        // return ['message' => 'The record was deleted!'];
    }
}
