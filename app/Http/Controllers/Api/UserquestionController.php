<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Userexercise;
use App\Models\Userquestion;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class UserquestionController extends Controller implements HasMiddleware
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

    public function myAnswer(Request $request)
    {
        $ex_id = $request->f1;
        $eq_id = $request->f2;

        $answer = Userquestion::select('answer')
            ->whereRaw('MD5(userexercise_id) = ?', [$ex_id])
            ->where('exercisequestion_id', $eq_id)
            ->first();
        // dd($eq_id);

        if ($answer) {
            return response()->json(['answer' => $answer->answer]);
        } else {
            return response()->json(['message' => 'Not Found']);
        }
    }

    public function myQuestion(Request $request)
    {
        $email = $request->email;
        $exercise_id = $request->exercise_id;

        $userquestions = DB::select(
            'SELECT 
                eq.id as eq_id,
                eq.question, 
                eq.lokasi, 
                eq.gambar,
                eq.orient,
                eq.seq,
                eq.correct
            FROM exercisequestions eq
            INNER JOIN users u ON u.email = ?
            INNER JOIN userexercises ue ON ue.exercise_id = eq.exercise_id AND ue.user_id = u.id
            WHERE MD5(eq.exercise_id) = ?
            ORDER BY eq.seq ASC',
            [$email, $exercise_id]
        );

        foreach ($userquestions as $key => $question) {
            $eqId = $question->eq_id;
            $question->choices = (object) DB::select(
                'SELECT 
                    ec.id as ec_id,
                    ec.choice,
                    ec.seq,
                    ec.value,
                    ec.correct
                FROM exercisechoices ec
                WHERE ec.exercisequestion_id = ?
                ORDER BY ec.seq ASC',
                [$eqId]
            );
        }

        // dump($userquestions);
        return response()->json(['userquestions' => $userquestions]);
    }

    public function show(string $id)
    {
        //
    }

    public function store(Request $request)
    {
        $md5_userexercise_id = $request->f1;
        $exercisequestion_id = $request->f2;
        $answer = $request->f3;
        $score = $request->f4;
        $email = $request->f5;
        $ret = null;

        $userexercise = Userexercise::whereRaw('MD5(id) = ?', [$md5_userexercise_id])->first();

        $userquestions = Userquestion::where('userexercise_id', $userexercise->id)->where('exercisequestion_id', $exercisequestion_id)->first();

        if ($userquestions) {
            $userquestions->update([
                'answer' => $answer,
                'score' => $score,
                'updated_by' => $email,
            ]);

            $ret = ['message' => 'successfully saved'];
        } else {
            Userquestion::create([
                'userexercise_id' => $userexercise->id,
                'exercisequestion_id' => $exercisequestion_id,
                'answer' => $answer,
                'score' => $score,
                'created_by' => $email,
                'updated_by' => $email,
            ]);

            $ret = ['message' => 'successfully added'];
        }

        $total_score = Userquestion::where('userexercise_id', $userexercise->id)->sum('score');
        $userexercise->update([
            'score' => $total_score,
        ]);

        return response()->json($ret);
    }
}
