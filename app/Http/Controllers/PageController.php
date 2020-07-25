<?php

namespace App\Http\Controllers;

use App\Page;
use App\Post;
use App\QuickQuestion;
use App\Quotation;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use RealRashid\SweetAlert\Facades\Alert;

class PageController extends Controller
{
    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view pages', ['only' => ['pages']]);
        $this->middleware('permission:create page', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit page', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete page', ['only' => ['destroy']]);
    }

    /**
     * @param $slug
     * @return Application|Factory|RedirectResponse|Redirector|View
     */
    public function index($slug)
    {
        $page = Cache::get('page_by_slug_'.$slug, function () use($slug){
            Cache::put('page_by_slug_'.$slug, $page = Page::with(['meta'])
                ->where('slug', $slug)
                ->where('status', '!=', '0')
                ->get()
                ->first(), Carbon::now()->endOfDay());
            return $page;
        });

        if (empty($page)) {
            abort('404');
        }

        $page = $page->toArray();

        if (View::exists('front.pages.' . $page['slug'])) {

            if ($page['slug'] == "my-account") {
                if (!Auth::check()) {
                    return redirect('order/authenticate/');
                }

                $orders = Cache::get(
                    'logged_in_user_orders',
                    function () use($slug)
                    {
                    Cache::put(
                        'logged_in_user_orders',
                        $orders = auth()
                            ->user()
                            ->orders,
                        Carbon::now()
                            ->endOfDay()
                    );
                    return $orders;
                });

                $quotations = Cache::get(
                    'logged_in_user_quotations',
                    function () use($slug)
                    {
                    Cache::put(
                        'logged_in_user_quotations',
                        $quotations = Quotation::where(
                            'email',
                            Auth::user()->email
                        )
                        ->get(),
                        Carbon::now()
                            ->endOfDay()
                    );
                    return $quotations;
                });

                $questions = Cache::get('logged_in_user_questions', function () use($slug){
                    Cache::put('logged_in_user_questions', $questions = QuickQuestion::where('email', Auth::user()->email)
                        ->get(), Carbon::now()->endOfDay());
                    return $questions;
                });

                return view('front.pages.' . $page['slug'], compact('page', 'quotations', 'questions', 'orders'));
            }

            if ($page['slug'] == "blog") {

                $posts = Cache::get('active_posts', function () use($slug){
                    Cache::put('active_posts', $posts = Post::where('status', 1)
                        ->get()
                        ->toArray(), Carbon::now()->endOfDay());
                    return $posts;
                });

                return view('front.pages.' . $page['slug'], compact('page', 'posts'));
            }

            return view('front.pages.' . $page['slug'], compact('page'));
        }

        return view('front.pages.default', compact('page'));
    }

    /**
     * @return Application|Factory|View
     */
    public function home()
    {
        $posts = Cache::get('latest_six_posts', function () {
            Cache::put('latest_six_posts', $posts = Post::where('status', 1)
                ->take(6)
                ->get(), Carbon::now()->endOfDay());
            return $posts;
        });

        $posts = $posts->toArray();

        return view('front.pages.home', compact('posts'));
    }

    /**
     * @return Application|Factory|View
     */
    public function pages()
    {
        // Getting All Pages
        $pages = Page::get(['title', 'id', 'slug', 'created_at', 'status', 'updated_at'])
            ->toArray();

        return view('admin.pages.all', compact('pages'));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        return view('admin.pages.default');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $request->request->set('title', trim(preg_replace("/[[:blank:]]+/", " ", $request->title)));

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:pages',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $image_name = $this->imageFormat($request);

        $page = Page::create([
            'content' => $request->page_content,
            'title' => $request->title,
            'image' => ($image_name) ? $image_name : null,
        ]);

        $page->meta()->create([
            'title' => $request->meta_title,
            'keywords' => $request->meta_keywords,
            'description' => $request->meta_description,
        ]);

        // Printing Alert Message
        Alert::toast('Page Created Successfully', 'success');

        return redirect('admin/page/edit/' . $page['id']);
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
            $image_destination_path = public_path('files/23/Photos/Pages/');
            $image->move($image_destination_path, $image_name);
        }
        return $image_name;
    }

    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $page = Page::with(['meta'])
            ->where('id', $id)
            ->get()
            ->first();

        if (empty($page)) {
            abort('404');
        }

        $page = $page->toArray();

        if (View::exists('admin.pages.' . $page['slug'])) {
            return view('admin.pages.' . $page['slug'], compact('page'));
        }

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $request->request->set('title', trim(preg_replace("/[[:blank:]]+/", " ", $request->title)));

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:pages,title,' . $page->id,
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Deleting Page Image From Pages Folder
        if ($request->hasFile('main_image')) {
            $image_path = public_path('files/23/Photos/Pages/') . $page->image;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }

        $image_name = $this->imageFormat($request);

        $page->update([
            'title' => $request->title,
            'image' => ($image_name) ? $image_name : $page->image,
            'status' => $request->status,
            'content' => $request->page_content,
        ]);

        $page->meta()->update([
            'title' => $request->meta_title,
            'keywords' => $request->meta_keywords,
            'description' => $request->meta_description,
        ]);

        return redirect('admin/page/edit/' . $page->id);

    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($id)
    {
        // Getting The User
        $page = Page::findOrFail($id);

        // Deleting Page Image From Pages Folder
        $image_path = public_path('files/23/Photos/Pages/' . '/' . $page->image);

        if (File::exists($image_path)) {
            File::delete($image_path);
        }

        // Deleting The Page
        $page->delete();

        return response()->json(array('success' => "Deleted!"));
    }
}
