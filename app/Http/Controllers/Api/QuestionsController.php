<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class QuestionsController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    public function index()
    {
        // $userEmail = 'mr.eko.handriyanto@gmail.com';
        // $isactive = '1';

        // $exercises = DB::select(
        //     'SELECT 
        //         MD5(e.id) as xid,
        //         e.quest_amount as quest, 
        //         e.title, 
        //         e.description, 
        //         concat(trim(left(e.description, 100)), \'...\') as description_trim, 
        //         DATE_FORMAT(e.updated_at, \'%M %e, %Y\') as start_date,
        //         ue.paid
        //     FROM exercises e
        //     INNER JOIN users u ON u.email = ?
        //     LEFT JOIN userexercises ue ON ue.exercise_id = e.id AND ue.user_id = u.id
        //     WHERE e.isactive = ?
        //     ORDER BY e.updated_at DESC',
        //     [$userEmail, $isactive]
        // );

        // foreach ($exercises as $key => $exercise) {
        //     $eId = $exercise->xid;
        //     $exercise->questions = (object) DB::select(
        //         'SELECT eq.question
        //         FROM exercisequestions eq
        //         WHERE MD5(eq.exercise_id) = ?',
        //         [$eId]
        //     );
        // }

        // dump($exercises);
        // return response()->json(['exercises' => $exercises]);
    }

    public function show(string $id)
    {
        $questions = DB::select(
            'SELECT 
                MD5(eq.id) as xid,
                eq.question, 
                eq.lokasi, 
                eq.gambar,
                eq.orient,
                eq.seq,
                eq.correct
            FROM exercisequestions eq
            WHERE MD5(eq.exercise_id) = ?
            ORDER BY eq.seq ASC',
            [$id]
        );

        foreach ($questions as $key => $question) {
            $eqId = $question->xid;
            $question->choices = (object) DB::select(
                'SELECT 
                    MD5(ec.id) as xid,
                    ec.choice,
                    ec.seq,
                    ec.value,
                    ec.correct
                FROM exercisechoices ec
                WHERE MD5(ec.exercisequestion_id) = ?
                ORDER BY ec.seq ASC',
                [$eqId]
            );
        }

        // dump($questions);
        return response()->json(['questions' => $questions]);
    }
}
