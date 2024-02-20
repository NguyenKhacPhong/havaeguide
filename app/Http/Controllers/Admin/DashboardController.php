<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Question\IQuestionRepository;
use App\Repositories\School\ISchoolRepository;
use Illuminate\Http\Request;
use App\Repositories\User\IUserRepository;

class DashboardController extends Controller
{
    private $userRepo;
    private $questionRepo;
    private $schoolRepo;
    function __construct(IUserRepository $userRepo, IQuestionRepository $questionRepo, ISchoolRepository $schoolRepo)
    {
        $this->userRepo = $userRepo;
        $this->questionRepo = $questionRepo;
        $this->schoolRepo = $schoolRepo;
        $this->middleware(function($request, $next){
            session(['module_active' => 'dashboard']);
            return $next($request);
        });
    }
    
    function show( Request $request){
        $count_user = $this->userRepo->countUser();
        $count_question = $this->questionRepo->countQuestion();
        $un_question = $this->questionRepo->countUnQuestion();
        $count_school = $this->schoolRepo->countSchool();
        $questions = $this->questionRepo->getQuestionByStatus(0);
        return view('admin.dashboard', compact('count_user', 'count_question', 'un_question', 'count_school', 'questions'));
    }
}
