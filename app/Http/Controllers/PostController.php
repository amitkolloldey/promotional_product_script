<?php

namespace App\Http\Controllers;

use App\Page;
use App\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use RealRashid\SweetAlert\Facades\Alert;

class PostController extends Controller
{
    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view posts', ['only' => ['posts']]);
        $this->middleware('permission:create post', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit post', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete post', ['only' => ['destroy']]);
    }

    /**
     * @param $slug
     * @return Application|Factory|RedirectResponse|Redirector|View
     */
    public function index($slug)
    {
        $post = Post::with(['meta'])
            ->where('slug', $slug)
            ->where('status','!=', '0')
            ->get()
            ->first();

        if (empty($post)) {
            abort('404');
        }

        $post = $post->toArray();

        if (View::exists('front.posts.' . $post['slug'])) {
            return view('front.posts.' . $post['slug'], compact('post'));
        }

        return view('front.posts.default', compact('post'));
    }

    /**
     * @return Application|Factory|View
     */
    public function posts()
    {
        // Getting All Pages
        $posts = Post::get([
            'title',
            'id',
            'slug',
            'created_at',
            'status',
            'updated_at'
        ])
            ->toArray();

        return view('admin.posts.all', compact('posts'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        return view('admin.posts.default');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $request
            ->request
            ->set(
                'title',
                trim(preg_replace("/[[:blank:]]+/", " ", $request->title))
            );

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:pages',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $image_name = $this->imageFormat($request);

        $post = Post::create([
            'content' => $request->post_content,
            'title' => $request->title,
            'image' => ($image_name) ? $image_name : null,
        ]);

        $post->meta()->create([
            'title' => $request->meta_title,
            'keywords' => $request->meta_keywords,
            'description' => $request->meta_description,
        ]);

        // Printing Alert Message
        Alert::toast('Post Created Successfully', 'success');

        return redirect('admin/post/edit/' . $post['id']);
    }

    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $post = Post::with(['meta'])
            ->where('id', $id)
            ->get()
            ->first();

        if (empty($post)) {
            abort('404');
        }

        $post = $post->toArray();

        if (View::exists('admin.posts.' . $post['slug'])) {
            return view('admin.posts.' . $post['slug'], compact('post'));
        }

        return view('admin.posts.edit', compact('post'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request
            ->request
            ->set(
                'title',
                trim(preg_replace("/[[:blank:]]+/", " ", $request->title))
            );

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:pages,title,' . $post->id,
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Deleting Page Image From Pages Folder
        if ($request->hasFile('main_image')) {
            $image_path = public_path('files/23/Photos/Posts/') . $post->image;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }

        $image_name = $this->imageFormat($request);

        $post->update([
            'title' => $request->title,
            'image' => ($image_name) ? $image_name : $post->image,
            'status' => $request->status,
            'content' => $request->post_content,
        ]);

        $post->meta()->update([
            'title' => $request->meta_title,
            'keywords' => $request->meta_keywords,
            'description' => $request->meta_description,
        ]);

        return redirect('admin/post/edit/' . $post->id);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        // Getting The User
        $post = Post::findOrFail($id);

        // Deleting Page Image From Pages Folder
        $image_path = public_path('files/23/Photos/Posts/' . '/' . $post->image);

        if (File::exists($image_path)) {
            File::delete($image_path);
        }

        // Deleting The Page
        $post->delete();

        return response()->json(array('success' => "Deleted!"));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function imageFormat(Request $request): string
    {
        $image_name = "";
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = seoUrl($request->title) . '.' . $image->getClientOriginalExtension();
            $image_destination_path = public_path('files/23/Photos/Posts/');
            $image->move($image_destination_path, $image_name);
        }
        return $image_name;
    }
}
