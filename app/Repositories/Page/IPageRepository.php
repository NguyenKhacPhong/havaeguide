<?php

namespace App\Repositories\Page;

interface IPageRepository{
    public function getListPage();
    public function createPage();
    public function storePage($request);
    public function updatePage($request, $id);
    public function deletePage($request);

    //Api 
    public function getPage($slug);
}