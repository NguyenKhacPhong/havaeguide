<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Repositories\Comment\ICommentRepository;
use Illuminate\Http\Request;
use App\Repositories\Slider\SliderRepository;
use App\Repositories\Sector\ISectorRepository;
use App\Repositories\School\ISchoolRepository;
use App\Repositories\Major\IMajorRepository;
use App\Repositories\SchoolType\ISchoolTypeRepository;
use App\Repositories\Page\IPageRepository;
use App\Repositories\Post\IPostRepository;
use App\Repositories\Question\IQuestionRepository;
use App\Repositories\Repositories;
use Exception;
use Illuminate\Cache\Repository;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $sectorRepo;
    private $sliderRepo;
    private $schoolRepo;
    private $majorRepo;
    private $typeRepo;
    private $pageRepo;
    private $commentRepo;
    private $questionRepo;
    private $postRepo;
    private $Repo;

    public function __construct(
        ISchoolRepository $schoolRepo,
        ISectorRepository $sectorRepo,
        SliderRepository $sliderRepo,
        Repositories $repo,
        IMajorRepository $majorRepo,
        IPageRepository $pageRepo,
        ICommentRepository $commentRepo,
        IQuestionRepository $questionRepo,
        ISchoolTypeRepository $typeRepo,
        IPostRepository $postRepo,
    )
    {
        $this->sliderRepo = $sliderRepo;
        $this->sectorRepo = $sectorRepo;
        $this->schoolRepo = $schoolRepo;
        $this->majorRepo = $majorRepo;
        $this->typeRepo = $typeRepo;
        $this->pageRepo = $pageRepo;
        $this->commentRepo = $commentRepo;
        $this->questionRepo = $questionRepo;
        $this->postRepo = $postRepo;

        $this->Repo = $repo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {
        try {
            $sliders = $this->sliderRepo->getSliderShow();
            $sectors = $this->sectorRepo->getAllSector();
            $outstandingSchools = $this->schoolRepo->getOutstendingSchools();
            $areaCenters = $this->Repo->getAreaCenter();
            return response()->json(
                [
                    "status" => 200,
                    "sliders" => $sliders,
                    "sectors" => $sectors,
                    "outstanding_schools" => $outstandingSchools,
                    "area_centers" => $areaCenters
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    "status" => 500,
                    "message" => $e->getMessage()
                ],
                500
            );
        }
    }

    public function getAllSchool(Request $request)
    {
        $s = $request->input('s');
        $sectors = $this->sectorRepo->getAllSector();
        $majors = $this->majorRepo->getAllMajor();
        $areas = $this->Repo->getArea();
        $types = $this->typeRepo->getAllType();
        $schools = $this->schoolRepo->getAllSchool($s);
        return response()->json(
            [
                "status" => 200,
                "sectors" => $sectors,
                "majors" => $majors,
                "areas" => $areas,
                "school_types" => $types,
                "schools" => $schools
            ],
            200
        );
    }

    public function getSchool($school_code){
        $school = $this->schoolRepo->getSchool($school_code);
        $majors = $this->majorRepo->getMajorInArray(json_decode($school->school_majors));
        $school->school_majors = $majors;
        if($school != null){
            return response()->json([
                "code" => 200,
                "school" => $school
            ],200);
        }else{
            return response()->json([
                "code" => 404,
                "message" => "Trường này không tồn tại!"
            ],404);
        }

    }

    public function getSector($sector_id)
    {
        $sector = $this->sectorRepo->getSector($sector_id);
        if($sector != null) {
            $schools = $this->schoolRepo->getSchoolBySectorId($sector_id);
            $sector->schools = $schools;
            return response()->json([
                "code" => 200,
                "sector" => $sector
            ], 200);
        }else{
            return response()->json([
                "code" => 404,
                "message" => "Nhóm ngành không tồn tại!"
            ],404);
        }
    }
    public function getAllArea()
    {
        $areas = $this->Repo->getArea();
        return response()->json([
            "code" => 200,
            "areas" => $areas
        ],200);
    }
    public function getArea($codename){

        $areas = $this->Repo->getArea();
        $areas = array_filter($areas, function($area) use ($codename) {
            return $area->codename === $codename;
        });
        $area = reset($areas);
        $schools = $this->schoolRepo->getSchoolByAreaCodename($area->code);
        $area->schools = $schools;
        if($area){
            return response()->json(
                [
                    "code" => 200,
                    "area" => $area,
                ],
                200
            );
        }else{
            return response()->json(
                [
                    "code" => 404,
                    "message" => "Khồng có tỉnh thành nào như thế!",
                ],
                404
            );
        }
    }
    public function getPage($slug)
    {
        $page = $this->pageRepo->getPage($slug);
        if($page){
            return response()->json(
                [
                    "status" => 200,
                    "page" => $page,
                ],
                200
            );
        }else{
            return response()->json(
                [
                    "status" => 404,
                    "message" => "Trang không tồn tại",
                ],
                404
            );
        }
    }

    public function getAllComment($code)
    {
        return response()->json([
            "code" => 200,
            "comments" => $this->commentRepo->getCommentsBySchoolCode($code)
        ]);
    }
    public function  addComment(Request $request){
        $comment = $this->commentRepo->addComment($request);
        if($comment){
            return response()->json([
               "code" => 200,
               "data" => $comment
            ]);
        }else{
            return response()->json([
                "code" => 400,
                "message" => "Thêm comment thất bại!"
            ],400);
        }
    }

    //người dùng đặt câu hỏi
    public function ask(Request $request){
        try{
            $question = $this->questionRepo->ask($request);
                return response()->json([
                    "code" => 200,
                    "question" => $question,
                ],200);
        }catch (Exception $e){
            return response()->json([
                "code" => 500,
                "message" => "Lỗi trong quá trình đặt câu hỏi",
            ],500);
        }
    }

    //Lấy tất cả các câu hỏi
    public function getAllQuestion(){
        try{
            $questions = $this->questionRepo->getAllQuestion();
            return response()->json([
                "code" => 200,
                "questions" => $questions,
            ],200);
        }catch (Exception $e){
            return response()->json([
                "code" => 500,
                "message" => "Lỗi trong quá trình lấy câu hỏi!",
            ],500);
        }
    }

    //Lấy tất cả các câu hỏi của một người dùng
    public function getAllQuestionByUserId(){
        try{
            $questions = $this->questionRepo->getQuestionByUserId(1);
            return response()->json([
                "code" => 200,
                "questions" => $questions,
            ],200);
        }catch (Exception $e){
            return response()->json([
                "code" => 500,
                "message" => "Lỗi trong quá trình lấy câu hỏi!",
            ],500);
        }
    }
    //Lấy câu hỏi
    public function getQuestion($id){
        try{
            $question = $this->questionRepo->getQuestion($id);
            return response()->json([
                "code" => 200,
                "question" => $question,
            ],200);
        }catch (Exception $e){
            return response()->json([
                "code" => 404,
                "message" => "Lỗi trong quá trình lấy câu hỏi!",
            ],404);
        }
    }

    //Cập nhật câu hỏi
    public function updateQuestion($id, Request $request){
        try{
            $question = $this->questionRepo->updateQuestion($id, $request);
            return response()->json([
                "code" => 200,
                "message" => "Cập nhật câu hỏi thành công!",
            ],200);
        }catch (Exception $e){
            return response()->json([
                "code" => 404,
                "message" => "Lỗi trong quá trình cập nhật câu hỏi",
            ],404);
        }
    }
    //Xóa câu hỏi
    public function deleteQuestion($id){
        try{
            $this->questionRepo->deleteQuestion($id);
            return response()->json([
                "code" => 200,
                "message" => "Xóa câu hỏi thành công!",
            ],200);
        }catch (Exception $e){
            return response()->json([
                "code" => 404,
                "message" => "Lỗi trong quá trình xóa câu hỏi",
            ],404);
        }
    }

    //Hiển thị câu hỏi
    public function detailQuestion($id){
        try{
            $question = $this->questionRepo->detailQuestion($id);
            return response()->json([
                "code" => 200,
                "question" => $question,
            ],200);
        }catch (Exception $e){
            return response()->json([
                "code" => 404,
                "message" => "Lỗi trong quá trình hiển thị câu hỏi",
            ],404);
        }
    }

    //Trà lời câu hỏi
    public function answerQuestion($id, Request $request){
        try{
            $answer = $this->questionRepo->answerQuestion($id, $request);
            return response()->json([
                "code" => 200,
                "answer" => $answer,
            ],200);
        }catch (Exception $e){
            return response()->json([
                "code" => 404,
                "message" => $e,
            ],404);
        }
    }
    //Lấy tất cả các bài viết
    public function getAllPost(){
        try{
            $posts = $this->postRepo->getAllPost();
            return response()->json([
                "code" => 200,
                "posts" => $posts,
            ],200);
        }catch (Exception $e){
            return response()->json([
                "code" => 500,
                "message" => "Lỗi trong quá trình đặt câu hỏi",
            ],500);
        }
    }
    //Lấy một bài viết theo slug
    public function getPost($slug){
        try{
            $post = $this->postRepo->getPost($slug);
            return response()->json([
                "code" => 200,
                "post" => $post,
            ],200);
        }catch (Exception $e){
            return response()->json([
                "code" => 500,
                "message" => "Lỗi trong quá trình đặt câu hỏi",
            ],500);
        }
    }
}
