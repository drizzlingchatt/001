<?php

namespace App\Http\Controllers;

use App\Blog;

class BlogApiController extends Controller
{
	protected $data = [];

    public function index(Request $request){ 
        $all_post = Blog::all();
        return $all_post;
    }

    public function newPost(Request $request){ 

        $user_id = $request->input('user_id') ? $request->input('user_id') : '';
        $title = $request->input('title') ? $request->input('title') : '';
        $content = $request->input('content') ? $request->input('content') : '';
        $picture_name = $this->upload_file($request);

        if($user_id){
            $id = Blog::insert([
                'user_id' => $user_id,
                'title' => $title, 
                'content' => $content, 
                'picture' => $picture_name,
                'created_at' => NOW(),
                'updated_at' => NOW()
            ]);
            $message = 'Updated';
		}else{ 
			$message = 'Update fail';
		}
		return $message;
    }

	public function doEdit(Request $request)
	{
		$post_id = $request->input('post_id') ? $request->input('post_id') : '';
		$user_id = $request->input('user_id') ? $request->input('user_id') : '';
        $title = $request->input('title') ? $request->input('title') : '';
        $content = $request->input('content') ? $request->input('content') : '';
        $picture_name = $this->upload_file($request);
        
		if($post_id && $user_id){
            $this_post = Blog::where('id', $post_id)
			->where('user_id', $user_id)
            ->get();
			$this_post->update([
				'title' => $title,
				'content ' => $content ,
				'picture' => $picture_name,
				'updated_at' => NOW(),
			]);
			$message = 'Updated';
		}else{ 
			$message = 'Update fail';
		}
		return $message;
	}

	public function doSort(Request $request)
	{
        $sort_by = $request->input('sort_by') ? $request->input('sort_by') : '';
        $sort_way = $request->input('sort_way') ? $request->input('sort_way') : 'asc';
        
		if($sort_by){
            $all_post = Blog::orderBy($sort_by, $sort_way)
            ->get();
		}

		return $all_post;
	}

	public function doSearch(Request $request)
	{
        $search_text = $request->input('search_text') ? $request->input('search_text') : '';
        
		if($search_text){
            $all_post = Blog::where('title', 'like', '%' . $search_text . '%')
			->orwhere('content', 'like', '%' . $search_text . '%')
            ->get();
		}
		return $all_post;
	}

	public function doActive(Request $request)
	{

		$post_id = $request->input('post_id') ? $request->input('post_id') : '';
		$user_id = $request->input('user_id') ? $request->input('user_id') : '';
        $active = $request->input('active') ? $request->input('active') : '';
        
		if($post_id && $user_id && $active){
            $this_post = Blog::find($post_id)
            ->update([
                'stage' => 'active'
            ]);
			$message = 'Actived';
		}else{ 
			$message = 'Active fail';
		}
		return $message;
	}

	public function doInactive(Request $request)
	{

		$post_id = $request->input('post_id') ? $request->input('post_id') : '';
		$user_id = $request->input('user_id') ? $request->input('user_id') : '';
        $inactive = $request->input('inactive') ? $request->input('inactive') : '';
        
		if($post_id && $user_id && $inactive){
            $this_post = Blog::find($post_id)
            ->update([
                'stage' => 'inactive'
            ]);
			$message = 'Inactived';
		}else{ 
			$message = 'Inactive fail';
		}
		return $message;
	}

	public function doDelete(Request $request)
	{

		$post_id = $request->input('post_id') ? $request->input('post_id') : '';
		$user_id = $request->input('user_id') ? $request->input('user_id') : '';
        $delete = $request->input('delete') ? $request->input('delete') : '';
        
		if($post_id && $user_id && $delete){
            $this_post = Blog::find($post_id)
            ->update([
                'deleted_at' => now()
            ]);
			$message = 'Deleted';
		}else{ 
			$message = 'Delete fail';
		}
		return $message;
	}

    public function upload_file(Request $request)
        {
            $request->validate([
                'file' => 'required|mimes:jpg,png,pdf|max:2048',
            ]);

            $file = $request->file('file');
            $path = $file->store('uploads', 'public');
            $filename = uniqid('store_coupon_') . '.' . $request->file($path)->getClientOriginalExtension();

            return $filename;
        }
}
