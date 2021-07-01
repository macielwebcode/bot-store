<?php

namespace App\Console;

use App\Http\Traits\appSettings;
use App\Mail\LowBalanceAlert;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    use appSettings;
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Run alert mail batch daily to notice users that has balance bellow X percent
        $schedule->call(function() {

            $warning_percent = $this->app_settings->warning_percent;

            // Gets only users with balance below warning percent
            $user_bellow_index = User::where("subscriptions.");

            foreach($user_bellow_index as $user){

                Log::info("Sending low balance alert mail to [ ". $user->email . " ]");

                Mail::to($user->email)->send(new LowBalanceAlert($user));

            }

        })->daily();

        // Get user consumption at Yank
        $schedule->call(function() {

            // TODO: Gets users consumption
            // Call Yank WS for updates
            $consumptions = [];

            foreach($consumptions as $con){
                $user = User::find($con['user_id']);
                $product = Product::find($con['prod_id']);

                $user->extracts()->create([
                    'operation' => 'D',
                    'description' => 'Consumo bot - '. $product->title,
                    'balance'   => $user->balance - $product->value,
                    'old_balance' => $user->balance,
                    'user_id' => $user->id
                ]);

                $user->balance -= $product->value;

                $user->save();
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
