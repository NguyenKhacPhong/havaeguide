<?php

namespace App\Repositories\Comment;

interface ICommentRepository{
    public function getComments($request, $status);
    public function createComment();
    public function storeComment($request);
    public function editComment($id);
    public function updateComment($request, $id);
    public function removeComment($request);
    public function deleteComment($request);
    public function count();
    public function total();

    //Api
    public function getCommentsBySchoolCode($code);
    public function addComment($request);
}
