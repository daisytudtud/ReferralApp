<?php

namespace App\Http\Controllers;

use App\Mail\Referral;
use App\Models\Referral as ReferralModel;
use App\Models\ReferralPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ReferralController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $user = auth()->user();
        // maximum number of refererrals
        $max_referrals = config('constants.maximum_referrals');

        $referrals_cnt = $user->referral_points->points;

        $max_referrals_reached = $referrals_cnt >= $max_referrals;
        // dd($referrals_cnt);

        return view('referrals.index')->with('max_referrals_reached',$max_referrals_reached);
    }

    public function store(Request $request)
    {
        $errors = $valid_emails = $emails = [];

        $user = $request->user();

        // maximum number of refererrals
        $max_referrals = config('constants.maximum_referrals');

        $referrals_cnt = $user->referral_points->points;

        $max_referrals_reached = $referrals_cnt >= $max_referrals;

        if( $request->invite_emails && trim($request->invite_emails) !='' ) {
            $emails = explode(';', $request->invite_emails);
        }

        if( $request->email && trim($request->email) !='' ) {
            $emails[] = $request->email;
        }

        if( count($emails) ) :

            foreach( $emails as $email ) :
                $validate_email['email'] = $email;
                $validator = Validator::make($validate_email, [
                    "email"    => 'string|email|unique:users|unique:referrals',
                ]);

                // validation fails
                if( $validator->fails() ) :
                    $validation_errors = $validator->errors();
                    foreach ($validation_errors->get('email') as $message) {
                        $errors[] = $message;

                    }
                else:
                    // valid email
                    $valid_emails[] = $email;

                    // save referral and send referral email 
                    if (!$user->referrals()->where('email', $email)->count()):

                        $user->referrals()->create(['email'=>$email]);

                        Mail::to($email)->queue(new Referral($user,$email));
                    endif;

                endif;
            endforeach;
        else :
            $errors[] = "You haven't added emails.";
        endif;

        return view('referrals.index')->with(compact('errors', 'valid_emails'))->with('max_referrals_reached',$max_referrals_reached);
    }

    public function successful_referrals()
    {

        $user = auth()->user();

        // maximum number of refererrals
        $max_referrals = config('constants.maximum_referrals');

        $successful_referrals = $user->referrals()->where('referrer_id',$user->id)->whereNotNull('user_id')->take($max_referrals)->get();

        return view('referrals.successful_referrals')->with('successful_referrals',$successful_referrals);
    }

    public function referral_list()
    {
        $referrals = ReferralModel::with('user')->paginate(20);
        return view('referrals.list',['referrals'=>$referrals]);
    }

}
