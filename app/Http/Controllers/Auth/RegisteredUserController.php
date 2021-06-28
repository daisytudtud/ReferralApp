<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\ReferralPoint;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Ramsey\Uuid\Uuid;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $referrer_code = $email = '';
        if ($request->has('refer')) {
            $referrer_code = $request->query('refer');
        }
        if ($request->has('email')) {
            $email = $request->query('email');
        }
        return view('auth.register',['referrer_code'=>$referrer_code, 'ref_email'=>$email]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->attachRole('user');

        // generate referral code
        $referral_code = (string)Uuid::uuid1();

        $user->referral_code()->create(['code'=>$referral_code]);

        $user->referral_points()->create(['points'=>0]);

        event(new Registered($user));
        

        // update referral
        $referrer = User::whereHas('referral_code', function ($query) use($request) {
                return $query->where('code', '=', $request->refferer_code);
            })->first();

        if($referrer) {
            $referral = Referral::whereEmail($request->email)->first();

            if($referral) {
                // update referral
                $referral->user_id = $user->id;
                $referral->referrer_id = $referrer->id;
                $referral->save();

            } else {
                // add a new referral
                $referrer->referrals()->create(['email'=>$request->email,'user_id'=>$user->id]);
            }

            // update referrer user points
            $max_referrals = config('constants.maximum_referrals');
            $referrer_points = $referrer->referral_points;

            if($referrer_points->points < $max_referrals) {
                $referrer_points->increment('points');
                $referrer_points->save();
            }
        }


        Auth::login($user);

        return redirect()->route('referrals');
    }
}
