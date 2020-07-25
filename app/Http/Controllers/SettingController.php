<?php

namespace App\Http\Controllers;

use App\Setting;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Restricting Methods
     */
    function __construct()
    {
        $this->middleware('permission:view settings', ['only' => ['settings']]);
        $this->middleware('permission:create setting', ['only' => ['store']]);
        $this->middleware('permission:edit setting', ['only' => ['update']]);
    }

    /**
     * @return Application|Factory|View
     */
    public function settings()
    {
        // Getting All Settings and converting In Array
        $settings = Setting::all()->toArray();
        // IF Settings Exist
        if (count($settings)) {

            // Returns Settings Edit View
            return view('admin.settings.edit', compact('settings'));
        }

        // Else Returns Create View
        return view('admin.settings.create');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'site_name' => 'max:255',
            'site_email' => 'email',
            'site_logo' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'site_favicon' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if ($validator->fails()) {
            return redirect('admin/settings')
                ->withErrors($validator)
                ->withInput();
        }

        // Handling Logo and Favicon File name
        list($logoname, $faviconname) = $this->logoFaviconFormat($request);

        // Storing All The Settings In $data Array
        $data = [
            'site_name' => $request->site_name,
            'site_email' => $request->site_email,
            'site_tagline' => $request->site_tagline,
            'site_phone' => $request->site_phone,
            'site_address' => $request->site_address,
            'site_logo' => ($logoname) ? $logoname : null,
            'site_favicon' => ($faviconname) ? $faviconname : null,
            'site_description' => $request->site_description,
            'site_facebook' => $request->site_facebook,
            'site_twitter' => $request->site_twitter,
            'site_instagram' => $request->site_instagram,
            'site_linkedin' => $request->site_linkedin,
            'site_github' => $request->site_linkedin,
            'site_meta_title' => $request->site_meta_title,
            'site_meta_keywords' => $request->site_meta_keywords,
            'site_meta_description' => $request->site_meta_description,
        ];

        // Converting $data In to JSON Array
        $settings['data'] = json_encode($data);

        // Creating Created at and Updated at Columns
        $settings['created_at'] = Carbon::now();
        $settings['updated_at'] = Carbon::now();

        // Creating Product's Common Information Columns
        $settings['delivery_charges'] = $request->delivery_charges;
        $settings['payment_terms'] = $request->payment_terms;
        $settings['return_policy'] = $request->return_policy;
        $settings['disclaimer'] = $request->disclaimer;

        // Inserting All Settings
        Setting::insert($settings);

        return redirect('admin/settings')->with('success', 'Settings Saved.');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function logoFaviconFormat(Request $request): array
    {
        // Declaring Empty Logo and Favicon Name
        $logoname = "";
        $faviconname = "";

        // Handling Site Logo File
        if ($request->hasFile('site_logo')) {
            $logo = $request->file('site_logo');
            $logoname = seoUrl(config('app.name')) . '-logo' . '.' . $logo->getClientOriginalExtension();
            $logodestinationPath = public_path('files/23/Photos/Settings/');
            $logo->move($logodestinationPath, $logoname);
        }

        // Handling Site Favicon File
        if ($request->hasFile('site_favicon')) {
            $favicon = $request->file('site_favicon');
            $faviconname = seoUrl(config('app.name')) . '-fav' . '.' . $favicon->getClientOriginalExtension();
            $favicondestinationPath = public_path('files/23/Photos/Settings/');
            $favicon->move($favicondestinationPath, $faviconname);
        }

        return array($logoname, $faviconname);
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        // Getting Settings By The ID
        $setting = Setting::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'site_name' => 'max:255',
            'site_email' => 'email',
            'site_logo' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'site_favicon' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if ($validator->fails()) {
            return redirect('admin/settings')
                ->withErrors($validator)
                ->withInput();
        }

        // Handling Logo and Favicon File name
        list($logoname, $faviconname) = $this->logoFaviconFormat($request);

        // Updating Settings
        $setting->update([
            'data->site_name' => $request->site_name,
            'data->site_email' => $request->site_email,
            'data->site_tagline' => $request->site_tagline,
            'data->site_phone' => $request->site_phone,
            'data->site_address' => $request->site_address,
            'data->site_logo' => ($logoname) ? $logoname : $setting['data']['site_logo'],
            'data->site_favicon' => ($faviconname) ? $faviconname : $setting['data']['site_favicon'],
            'data->site_description' => $request->site_description,
            'data->site_facebook' => $request->site_facebook,
            'data->site_twitter' => $request->site_twitter,
            'data->site_instagram' => $request->site_instagram,
            'data->site_linkedin' => $request->site_linkedin,
            'data->site_github' => $request->site_github,
            'data->site_meta_title' => $request->site_meta_title,
            'data->site_meta_keywords' => $request->site_meta_keywords,
            'data->site_meta_description' => $request->site_meta_description,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'delivery_charges' => $request->delivery_charges,
            'payment_terms' => $request->payment_terms,
            'return_policy' => $request->return_policy,
            'disclaimer' => $request->disclaimer
        ]);

        return redirect('admin/settings')->with('success', 'Settings Saved.');
    }
}
