<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Question\IQuestionRepository;

class QuestionController extends Controller
{
    private IQuestionRepository $questionRepo;
    public function __construct(IQuestionRepository $questionRepo){
        $this->questionRepo = $questionRepo;
        $this->middleware(function($request, $next){
            session(['module_active' => 'question']);
            return $next($request);
        });
    }

    public function index(Request $request, $status = '')
    {
        return $this->questionRepo->getListQuesttion($request, $status);
    }
    public function view($id)
    {
        try{
            $question = $this->questionRepo->viewQuestion($id);
            return view('admin.question.view', compact('question'));
        }catch (Exception $e){
            return redirect()->back()->with('danger', 'Lỗi trong quá trình hiển thị câu hỏi');
        }
    }

    public function changeStatus($id, Request $request){
        try{
            $this->questionRepo->changeStatus($id, $request);
            return redirect()->route('admin.question.index')->with('success', 'Cập nhật trạng thái câu hỏi thành công!');
        }catch (Exception $e){
            return redirect()->route('admin.question.index')->with('danger', 'Lỗi trong quá trình cập nhật câu hỏi!');

        }
    }
}
