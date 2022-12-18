<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Models\Notifications;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Notifications $notifications)
    {
        try {

            $SERVER_API_KEY = 'AAAA9JOa3h8:APA91bF2Zdem6dX2O1n9tRE-cAFy-y4O5n3Y5iS_cpJPzMhTMla0CET4JmKEb7jCrrsDoyVQMDA3MSqeAwrI_61mNLSqnu-mpCbKi8beQ5c9jHH8G3QJUvztK3pXBGv3WLqdRz1PitXz';

            $token_1 = 'dRA842kOQqGOngWkMZKyQX:APA91bFFbKNeTi8mS1dgeC1943HjvuOEZnsEMjfPgDaYDT1ZCrXY_j4XdJVPsqKj2LxcXNLrOldshESOHC_wHscl9hnFWbDpqofDhTBH62Rlz2Rg-Wt2mCd1QUJ9bxyr0a1pdaY9gFDO';
            $user_tokens = notifications::all(['fcm_token']);
            $tokens = [];
            foreach ($user_tokens as $token) {
                array_push($tokens, $token['fcm_token']);
            }

            $data = [
                // for one user 
                // "registration_ids" => [
                //     $token_1
                // ],

                //for all user by select from databse tokens
                // "registration_ids" => $tokens,

                //send notification by topic
                "to" => "/topics/user",
                
                //send notification by condition  not all users
                // "condition" => "'dogs' in topics || 'cats' in topics",
                "notification" => [

                    "title" => 'Welcome',

                    "body" => 'Description',

                    "sound" => "default" // required for sound on ios

                ],
                "data" => [

                    "id" => '123553261',

                    "message" => 'asfdafadasdfadfasadsf',

                    "type" => "chat"

                ],
                'priority' => 'high' //'Urgent' //

            ];

            $dataString = json_encode($data);

            $headers = [

                'Authorization: key=' . $SERVER_API_KEY,

                'Content-Type: application/json',



            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
            $response = json_decode($response, true);
            // dd($response);
            if ($response != null) {
                return $this->success($response,);
            }else{
                return $this->error([],"",500);

            }
        } catch (\Throwable $th) {
            return $this->error($th, '', 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NotificationRequest $request)
    {
        $request->validated();
        $Notification = Notifications::create([
            'user_id' => Auth::user()->id,
            'fcm_token' => $request->fcm_token,

        ]);

        return $this->success([], 'token added');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notifications $notifications)
    {
        if (Auth::user()->id !== $notifications->user_id) {
            return $this->error('', 'You are not authorized to make this request', 403);
        }

        $notifications->update($request->all());

        return $this->success($notifications, 'token updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notifications $notifications)
    {
        return $this->isNotAuthorized($notifications) ? $this->isNotAuthorized($notifications) : $notifications->delete();
    }
    private function isNotAuthorized($task)
    {
        if (Auth::user()->id !== $task->user_id) {
            return $this->error('', 'You are not authorized to make this request', 403);
        }
    }
}
