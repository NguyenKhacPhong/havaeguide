<?php

namespace App\Repositories\Post;

interface IPostRepository{
    public function find($id);
    public function getListPost($request, $status);
    public function storePost($request);
    public function updatePost($request, $id);
    public function removePost($id);
    public function restorePost($id);
    public function deletePost($id);

    //Api 
    public function getAllPost();
    public function getPost($slug);
}