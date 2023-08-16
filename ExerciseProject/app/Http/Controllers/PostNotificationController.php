<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PostNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\PostNotificationUser;
use App\Jobs\SendPostNotificationJob;
use Illuminate\Support\Facades\Log;
// use Yajra\DataTables\DataTables; // Import the DataTables facade
use Yajra\DataTables\Facades\DataTables;


class PostNotificationController extends Controller
{
    public $model;
    public $post;

    public function __construct()
        {
            $this->model = new PostNotification;
            $this->post  = new PostNotificationUser;
        }
    /**
     * User data show done....
    */
    public function create()
    {
        $users = User::all();
        return view('users.create', compact('users'));
    }


    public function searchUsers(Request $request)
    {
        $query = $request->get('q');

        $users = User::where('name', 'LIKE', '%' . $query . '%')
            ->select('id', 'name as text') // Use "text" for Select2 to display user name
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    /**
     * send notification but when user off notification
     */
    public function store(Request $request)
{
    // Validate the input data
    $validator = Validator::make($request->all(), [
        'type' => 'required|in:marketing,invoice,system',
        'message' => 'required|string|max:60',
        'expiry_date' => 'required|date',
        'users' => 'array',
        'users.*' => ['required', Rule::exists('users', 'id')],
    ]);

    if ($validator->fails()) {
        return redirect()->route('posts.create')->withErrors($validator)->withInput();
    }

    // Create the post notification
    $postNotification = PostNotification::create([
        'type' => $request->input('type'),
        'message' => $request->input('message'),
        'expiration_date' => $request->input('expiry_date'),
    ]);

    // Get the selected user IDs

    $users = $request->input('all_user');

    if($users == 'on')
    {
        $selectedUserIds = "all-user";
    }
    else
    {
        $selectedUserIds = $request->input('users');
    }
    // Dispatch the job to send the notification

    SendPostNotificationJob::dispatch($postNotification, $selectedUserIds);

    return redirect()->route('posts.create')->with('success', 'Notification created successfully.');
}
    /**
     * show notification data usee side
     */
    public function show()
    {
        //show notification user side
        $notifications = PostNotification::all();

        return view('users.show_notification', compact('notifications'));
    }


    /** user view notification.
     *
    */

    // public function userview(string $id)
    // {
    //     $post_notification_data = DB::table('post_notification_user')
    //     ->where('post_notification_id', $id)
    //     ->get();

    //     $userIds = $post_notification_data->pluck('user_id')->unique()->toArray();

    //     $users = DB::table('users')
    //     ->whereIn('id', $userIds)
    //     ->simplePaginate(1000);

    //     return view('users.post_user_details', compact('users','id'));

    // }


/*  User View Notification Ends Here*/

public function userview(Request $request)
    {

        if ($request->ajax()) {
            $id = $request->id;

            $userIds = DB::table('post_notification_user')
                ->where('post_notification_id', $id)
                ->pluck('user_id')
                ->unique()
                ->toArray();

            // Use the query builder for users and join with post_notification_user table
            $query = DB::table('users')
                ->leftJoin('post_notification_user', function ($join) use ($id) {
                    $join->on('users.id', '=', 'post_notification_user.user_id')
                        ->where('post_notification_user.post_notification_id', $id);
                })
                ->whereIn('users.id', $userIds)
                ->select(['users.id', 'users.name', 'users.email','post_notification_user.read']);


            return DataTables::of($query)
                ->addColumn('read', function ($user) {
                    return $user->read ? 'Yes' : 'No';
                })
                ->toJson();
        }
    }

    public function user($id)
    {
        return view('users.post_user_details', compact('id'));
    }

    public function markNotificationRead(Request $request, $notification)
    {
        $userId = $request->input('userId');

        //notifcation read query in PostnotificationUser model
        $notificationData = $this->post->readnotification($userId, $notification);

        if ($notificationData) {
            DB::table('post_notification_user')
                ->where('post_notification_id', $notification)
                ->where('user_id', $userId)
                ->update(['read' => true]);

            return redirect()->back()->with('success', 'Notification marked as read successfully.');
        }

        return redirect()->back()->with('error', 'Notification not found or unauthorized to mark as read.');
    }


    /**search bar controller code
     *
     */

    public function search(Request $request)
    {
        $search = $request->input('search');

         // Perform the search query using $search variable in models
        $notifications = $this->model->getX($search);

        return view('users.show_notification', compact('notifications'));
    }



            public function updateStatus(Request $request)
        {
            log::info($request->input());

            $userId = $request->input('userId');
            $notificationId = $request->input('notificationId');

            // Update the post_notification_user table here
            // Assuming you have a model for post_notification_user
            DB::table('post_notification_user')
            ->where([
                'user_id' => $userId,
                'post_notification_id' => $notificationId
            ])
            ->update(['read' => 1]);
            return response()->json(['success' => true]);
        }

}



// foreach (array_chunk($users,1000) as $t)
// {
//     log::info($t);
//     $this->postNotification->users()->attach($t, ['read' => false]);
// }



