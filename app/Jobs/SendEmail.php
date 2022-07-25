<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //$data['email'] = $this->user->email;
        // Mail::send()的返回值为空，所以可以其他方法进行判断
//        Mail::raw('这是一封测试邮件',function($message) use ($data){
//            $message ->to($data['email'])->subject('邮件测试');
//        });
        $nowStr = Carbon::now()->toDateTimeString();
        $res = sprintf('Successful email delivery: %s', $nowStr);
        echo $res;
    }
}
