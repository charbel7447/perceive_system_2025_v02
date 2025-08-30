<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use File;
use Auth;
use App\Notifications;
use App\User;
use Spatie\Activitylog\Models\Activity;
use App\SidebarLink;


class PageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $manager_id = User::where('id','=',auth()->id())->value('manager_id');
        $notifications_count = Notifications::where('manager_id','=',$manager_id)
                                ->where('viewed','=',null)->orderBy('id','desc')->count();

        
        $quick_menus = \App\QuickMenu::take(4)->latest()->get();

        $dashboard_views = \App\DashboardViews::where('active','=',1)->latest()->get();
        $reports_views = \App\ReportsViews::where('active','=',1)->latest()->get();
        if(auth::check()){
            $release_note = \App\ReleaseNote::latest()->where('user_id','=',$user->id)->where('checked_by','!=',$user->id)->get();
        }else{
            $release_note = [];
        }

        if($notifications_count > 0){
            // $notifications = Notifications::where('manager_id','=',$manager_id)->where('viewed','=',null)->orderBy('id','desc')->get();
            $notifications = Notifications::where('viewed','=',null)->orderBy('id','desc')->get();
        }else{
            $notifications = 0;
        }
        
        // Sidebar Menu Logic
        $sidebarLinks = SidebarLink::orderBy('sort_order')
            ->get()
            ->groupBy('parent_id');

        $sidebar_lists = $this->buildMenu($sidebarLinks);
        if(Auth::check()) {
            return view('app')
            ->with(compact('notifications'))
            ->with(compact('quick_menus'))
            ->with(compact('dashboard_views'))
            ->with(compact('reports_views'))
            ->with(compact('sidebar_lists'))
            ->with(compact('release_note'));
        }

        return view('login');
    }


    private function buildMenu($links, $parentId = null)
    {
        if (!isset($links[$parentId])) return [];

        return $links[$parentId]->map(function ($link) use ($links) {
            $item = [
                'id'          => $link->id,
                'title'       => $link->title,
                'path'        => $link->route_path,
                'icon'        => $link->icon,
                'extra_class' => $link->extra_class,
                'links'       => $this->buildMenu($links, $link->id),
            ];

            if (empty($item['links'])) unset($item['links']);
            return $item;
        })->values()->all();
    }

    public function read_release($id, Request $request)
    {
        $model = \App\ReleaseNote::where('user_id','=',$id)->update(['checked_by' => $id]);
        return back();
    }
    
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|max:255'
        ]);

   

        $auth = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'is_active' => 1
        ]);

        if($auth) {
            activity()
            ->withProperties(['customProperty' => now()],['email'=> $request->email],['request' => $request])
            ->log($request->email, 'Log In');

            return redirect()
                ->intended('/');
        }

        activity()
                ->withProperties(['customProperty' => now()],['email'=> $request->email],['request' => $request])
                ->log($request->email, 'Log In Error');

        return back()
            ->withInput()
            ->withErrors(['email' => ['Invalid email and password combination!']]);
    }

    public function logout()
    {
        Auth::logout();

        activity()
        ->withProperties(['customProperty' => now()])
        ->log('Log out');

        return redirect('/');
    }

    public function showAttachment($filename)
    {
        $path = storage_path() . '/app/uploads/' . $filename;
        // dd($path);
        if(!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
}
