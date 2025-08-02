<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class ExercisesController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    public function index()
    {
        $isactive = '1';

        $exercises = DB::select(
            'SELECT 
                MD5(e.id) as xid,
                e.quest_amount as quest, 
                e.title, 
                e.description, 
                e.price,
                e.discount,
                FORMAT(e.price, 0, "de_DE") as fprice, 
                concat(trim(left(e.description, 100)), \'...\') as description_trim, 
                e.start_date,
                e.end_date,
                DATE_FORMAT(e.start_date, \'%M %e, %Y\') as fstart_date,
                DATE_FORMAT(e.end_date, \'%M %e, %Y\') as fend_date,
                (
                    SELECT FLOOR(RAND()*(30-COUNT(*)+1)+COUNT(*)) FROM userexercises ue WHERE ue.exercise_id = e.id AND ue.PAID = 1
                ) AS sold
            FROM exercises e
            WHERE e.isactive = ?
            ORDER BY e.end_date ASC',
            [$isactive]
        );

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
        return response()->json(['exercises' => $exercises]);
    }

    public function catalog(Request $request)
    {
        $email = $request->email;
        $isactive = '1';

        $exercises = DB::select(
            'SELECT 
                MD5(e.id) as xid,
                e.quest_amount as quest, 
                e.title, 
                e.description, 
                e.price,
                e.discount,
                FORMAT(e.price, 0, "de_DE") as fprice, 
                concat(trim(left(e.description, 100)), \'...\') as description_trim, 
                e.start_date,
                e.end_date,
                DATE_FORMAT(e.start_date, \'%M %e, %Y\') as fstart_date,
                DATE_FORMAT(e.end_date, \'%M %e, %Y\') as fend_date,
                (
                    SELECT ue.paid
                    FROM userexercises ue
                    INNER JOIN users u ON u.id = ue.user_id AND u.email = ?
                    WHERE ue.exercise_id = e.id
                ) AS bought,
                (
                    SELECT FLOOR(RAND()*(30-COUNT(*)+1)+COUNT(*)) 
                    FROM userexercises ue2 
                    WHERE ue2.exercise_id = e.id AND ue2.PAID = 1
                ) AS sold
            FROM exercises e
            WHERE e.isactive = ?
            ORDER BY e.end_date ASC',
            [$email, $isactive]
        );

        return response()->json(['exercises' => $exercises]);
    }

    public function pending(Request $request)
    {
        $email = $request->email;
        $isactive = '1';

        $exercises = DB::select(
            'SELECT 
                MD5(e.id) as xid,
                MD5(ue.id) as x2id,
                e.quest_amount as quest, 
                e.title, 
                e.description,
                e.price,
                e.discount,
                FORMAT(e.price, 0, "de_DE") as fprice, 
                concat(trim(left(e.description, 100)), \'...\') as description_trim, 
                e.start_date,
                e.end_date,
                DATE_FORMAT(e.start_date, \'%M %e, %Y\') as fstart_date,
                DATE_FORMAT(e.end_date, \'%M %e, %Y\') as fend_date,
                0 as bought,
                (
                    SELECT FLOOR(RAND()*(30-COUNT(*)+1)+COUNT(*)) 
                    FROM userexercises ue2 
                    WHERE ue2.exercise_id = e.id AND ue2.PAID = 1
                ) AS sold
            FROM exercises e
            INNER JOIN users u ON u.email = ?
            INNER JOIN userexercises ue ON ue.exercise_id = e.id AND ue.user_id = u.id
            WHERE e.isactive = ? AND ue.paid = 0
            ORDER BY e.end_date ASC',
            [$email, $isactive]
        );

        return response()->json(['exercises' => $exercises]);
    }

    public function show(String $id)
    {
        //
    }
}
