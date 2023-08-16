<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SendPostNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $postNotification;
    protected $selectedUserIds;

    /**
     * Create a new job instance.
     */
    public function __construct($postNotification, $selectedUserIds)
    {
        $this->postNotification = $postNotification;
        $this->selectedUserIds = $selectedUserIds;
    }
    /**
     * Execute the job.
     */
     public function handle(): void
    {
            // Get the users who have notification_on_off set to 0 and should receive notifications
        if ($this->selectedUserIds == "all-user") {

            User::where('notification_on_off', 0)->chunk(5000, function ($users) {
                // Attach the users to the post notification and mark the notification as unread for each batch
                $this->postNotification->users()->attach($users, ['read' => false]);

                Log::info('Processed ' . count($users) . ' users in the current batch.');
            });

        } else {
               // Fetch specific selected users with notification_on_off set to 0
               $users = User::whereIn('id', $this->selectedUserIds)->where('notification_on_off', 0)->get();

               // Attach the selected users to the post notification and mark the notification as unread
               $this->postNotification->users()->attach($users, ['read' => false]);
           }
        }


        // $this->postNotification->users()->attach($users, ['read' => false]);

        // foreach (array_chunk($users,5000) as $t)
        // {
        //     log::info($t);
        //     $this->postNotification->users()->attach($t, ['read' => false]);
        // }
    }

