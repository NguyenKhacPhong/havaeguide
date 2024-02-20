<?php

namespace App\Repositories\Post;

use App\Models\Post;
use Cache;
use App\Repositories\Repositories;
use Illuminate\Support\Facades\Auth;
use DB;

class PostRepository extends Repositories implements IPostRepository
{
    private Post $post;
    function __construct(post $post)
    {
        $this->post = $post;
    }
    public function find($id)
    {
        return $this->post->find($id);
    }
    public function getListPost($request, $status)
    {
        $posts = $this->getModelWithStatus($status, $this->post);
        if ($request->keyword) {
            $search = $request->keyword;
            $posts = $posts->where('title', 'like', "%{$search}%");
        }
        $posts = $posts
            ->join('users', 'posts.user_id', 'users.id')
            ->select('posts.id', 'posts.title','posts.image', 'users.name as user_name', 'posts.created_at', 'posts.deleted_at')
            ->paginate(10);
        $count = $this->count();
        $list_act = $this->getListStatus($status);

        return compact("posts", "count", "list_act");
    }
    public function storePost($request)
    {
        $request->validate(
            [
                'slug' => 'required|string|max:200| unique:posts,slug',
                'title' => 'required|string|max:200',
                'image' => 'mimes:jpg,png,gif,webp|max:20000',
            ],
            [
                'required' => ':attribute không được bỏ trống!',
                'max' => ':attribute có độ dài lớn nhất :max ký tự!',
                'unique' => ':attribute không được để trống!',
                'mimes' => ':attribute chỉ được có định dạng jpg,png,gif,webp'
            ],
            [
                'title' => "Tên ngành",
                'slug' => "Đường dẫn tĩnh",
                'image' => 'Hình ảnh'
            ]
        );
        // dd($request->all());
        $post = $request->except('_token');
        $post['image'] = $this->saveImage($request, 'image');
        $post['user_id'] = Auth::getUser()->id;
        return $this->post->create($post);
    }

    public function updatePost($request, $id)
    {
        $request->validate(
            [
                'image' => 'mimes:jpg,png,gif,webp|max:20000',
            ],
            [
                'max' => ':attribute có độ dài lớn nhất :max ký tự!',
                'mimes' => ':attribute chỉ được có định dạng jpg,png,gif,webp'
            ],
            [
                'image' => 'Hình ảnh'
            ]
        );
        $post = $request->except('_token');
        $oldpost = $this->post->find($id);
        $post['image'] = $this->saveImage($request, 'image', $oldpost->image);
        if ($oldpost) {
            return $oldpost->update($post);
        }
        return null;
    }
    public function removePost($id)
    {
        $post = $this->post->withTrashed()->find($id);
        return $post->delete();
    }

    public function deletePost($id)
    {
        $post = $this->post->withTrashed()->find($id);
        if($post != null)
            return $post->softDelete();
    }
    public function restorePost($id)
    {
        $post = $this->post->withTrashed()->find($id);
        if($post != null)
            return $post->restore();
    }
    public function count()
    {
        $count = [];
        $count['all_post'] = $this->post->withTrashed()->count();
        $count['post_active'] = $this->post->all()->count();
        $count['post_hide'] = $this->post->onlyTrashed()->count();
        return $count;
    }
    public function total()
    {
    }

    //API
    public function getAllPost(){
        // $posts = Cache::get('posts');
        // if ($posts == null) {
            $posts = post::
            join("users", "users.id", "posts.user_id")
            ->select("posts.slug", "posts.title", "posts.image","users.name as user_name", DB::raw("SUBSTR(posts.content, 1, 300) as content"), "posts.number_of_views", "posts.created_at")
            ->get();
            //     Cache::put('posts', $posts, 86400);
            // }
        return $posts;
    }

    public function getPost($slug)
    {
        $this->plusView($slug);
        $post = post::where('slug', $slug)
        ->join("users", "users.id", "posts.user_id")
            ->select("posts.slug", "posts.title", "posts.image","users.name as user_name", "posts.content", "posts.number_of_views", "posts.created_at")
        ->first();
        return $post;
    }
    public function plusView($slug){
        $post = post::where('slug', $slug)->first();
        $post->number_of_views += 1;
        $post->save();
    }
}
