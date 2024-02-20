<?php

namespace App\Repositories\Question;

interface IQuestionRepository{
    public function getListQuesttion($request, $status);
    public function getQuestionByStatus($status);
    public function viewQuestion($id);
    public function changeStatus($id, $request);
    public function countQuestion();
    public function countUnQuestion();

    //Api
    public function getAllQuestion();
    public function ask($request);
    public function getQuestionByUserId($user_id);
    public function getQuestion($id);
    public function updateQuestion($id, $request);
    public function deleteQuestion($id);
    public function detailQuestion($id);
    public function answerQuestion($id, $request);

}
