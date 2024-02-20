<?php

namespace App\Repositories\Question;

use App\Models\Question;
use App\Models\Answer;
use App\Models\SessionUser;
use App\Repositories\Repositories;
use http\Env\Request;
use Illuminate\Validation\Rule;
use Exception;
use Cache;
use function Ramsey\Uuid\Generator\timestamp;

class QuestionRepository extends Repositories implements IQuestionRepository
{
    private $question;
    private $answer;
    function __construct(Answer $answer, Question $question)
    {
        $this->question = $question;
        $this->answer = $answer;
    }
    public function find($id)
    {
        return $this->question->find($id);
    }
    public function getQuestionByStatus($status)
    {
        return $questions = $this->question->where('status', 0)->paginate(10);
    }
    public function getListQuesttion($request, $status)
    {
        if ($status == 'hide') {
            $questions = $this->question->where('status', 0);
        } elseif ($status == 'active') {
            $questions = $this->question->where('status', 1);
        } else {
            $questions = $this->question;
        }
        $search = '';
        if ($request->keyword) {
            $search = $request->keyword;
            $questions = $questions->where('title', 'like', "%{$search}%");
        }
        $questions = $questions
            ->join('users', 'questions.user_id', 'users.id')
            ->select('questions.id', 'questions.title', 'users.name as user_name', 'questions.created_at', 'questions.status')
            ->paginate(10);
        $count = $this->count();
        $list_act = $this->getListStatus($status);

        return view('admin.question.index', compact("questions", "count", "list_act"));
    }

    public function viewQuestion($id)
    {
        $question = $this->question->find($id);
        return $question;
    }
    public function changeStatus($id, $request)
    {
        $question = [
            'status' => $request->status
        ];
        $old = $this->question->find($id);
        return $old->update($question);
    }

    public function count()
    {
        $count = [];
        $count['all_question'] = $this->question->count();
        $count['question_active'] = $this->question->where('status', 1)->count();
        $count['question_hide'] = $this->question->where('status', 0)->count();
        return $count;
    }


    public function total()
    {
    }
    public function countQuestion()
    {
        return $this->question->count();
    }
    public function countUnQuestion()
    {
        return $this->question->where('status', 0)->count();
    }


    //API

    public function getAllQuestion()
    {
        $questions = question::join("users", "users.id", "questions.user_id")
            ->select('questions.id', 'questions.title', 'questions.content', 'questions.number_of_views', 'questions.number_of_replies', 'users.avatar', 'users.name as user_name', 'questions.created_at')
            ->where('status', 1)
            ->orderBy('questions.id')
            ->get();
        return $questions;
    }
    public function ask($request)
    {
        $user = SessionUser::where('token', $request->header('token'))
            ->select('user_id')
            ->first();
        $question = [
            "title" => $request->title,
            "content" => $request->content,
            "user_id" => $user->user_id,
            "status" => 0,
        ];
        $question = $this->question->create($question);
        return $question;
    }
    public function getQuestionByUserId($user_id)
    {
        $questions = question::select('id', 'title', 'content', 'number_of_views', 'status', 'number_of_replies', 'created_at', 'updated_at')
            ->where('user_id', $user_id)
            ->orderBy('number_of_views')
            ->get();
        return $questions;
    }
    public function getQuestion($id)
    {
        $question = $this->question->find($id);
        return $question;
    }
    public function updateQuestion($id, $request)
    {
        $question = $this->getQuestion($id);
        $question->update([
            'title' => $request->title,
            'content' => $request->content
        ]);
        return $question;
    }

    public function deleteQuestion($id)
    {
        $question = $this->getQuestion($id);
        $question->delete();
    }
    public function detailQuestion($id)
    {
        $question = $this->getQuestion($id);
        $question->number_of_views = $question->number_of_views + 1;
        $question->save();

        $question = $this->question
            ->select('title', 'content', 'status', 'number_of_views', 'number_of_replies', 'created_at')
            ->find($id);
        $answers = $this->answer
            ->where('question_id', $id)
            ->join('users', 'users.id', '=', 'answers.user_id')
            ->select('users.name as user_name', 'users.avatar', 'answers.message', 'answers.created_at')
            ->get();
        $question['answers'] = $answers;
        return $question;
    }

    public function answerQuestion($id, $request)
    {
        $userSession = SessionUser::where('token', $request->header('token'))->first();
        $answer = $this->answer->create([
            'question_id' => $id,
            'user_id' => $userSession->user_id,
            'message' => $request->message,
        ]);
        if ($answer != null) {
            $question = $this->getQuestion($id);
            $question->number_of_replies = $question->number_of_replies + 1;
            $question->update();
        }
        return $answer;
    }
}
