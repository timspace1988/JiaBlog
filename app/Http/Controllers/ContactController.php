<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ContactMeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Show the form
     *
     * @return View
     */
    public function showForm(){
        return view('blog.contact');
    }

    /**
     * Email the contact request
     *
     * @param ContactMeRequest $request
     *
     * @return Redirect
     */
    public function sendContactInfo(ContactMeRequest $request){
        $data = $request->only('name', 'email', 'phone');
        $data['messageLines'] = explode("\n", $request->get('message'));

        //instead of using Mail::send(), Mail::queue will let laravel queue this job automatically,
        //Note: laravel will just queue this job(put it into queue), not process it
        //We can call php artisan queue:work to process it, but we cannot call it each time a job is queued
        //So we use queue:listen. We call it at stat up on server,
        //then it will call queue:work whenever a job is queued (as long as no problem happended)
        //Therefore, we can choose to use 'supervisord' wit queue:listen
        //Anothe option is to use scheduled command, it will schedule queue:work to run every minute or even everu 5 miniutes
        //we use second option because this site is a  low volume website
        //check the setting in app/Console/Kernel.php
        Mail::queue('emails.contact', $data, function($message)use($data){
            $message->subject('Blog Contact Form: '.$data['name'])
                    ->to(config('blog.contact_email'))
                    ->replyTo($data['email']);//add a reply-to recipient
        });

        return back()->withSuccess("Thank you for your message. It has been sent.");
    }
}
