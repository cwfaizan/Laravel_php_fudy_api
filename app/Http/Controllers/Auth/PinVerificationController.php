<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PinVerification;
use App\Models\User;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Knuckles\Scribe\Attributes\QueryParam;

class PinVerificationController extends Controller
{
    use ApiResponser;
    public function index()
    {
        return view('auth.pin-code');
    }

    // Use of Pin Types
    // 1=>verify contact # and pin not delete (for new account)
    // 2=>verify contact # and pin not delete (forgot password)
    // 3=>verify contact # and pin not delete (forgot password)
    // 11=>verify email and pin delete
    // 12=>verify email and pin not delete
    public function generatePin($user_id, $pin_type, $item, $expired_in_minutes = (60 * 24))
    {
        $pin = random_int(1000, 9999);
        if ($user_id != null) {
            $resetData = [
                'user_id' => $user_id,
                'pin_type' => $pin_type,
                'pin' => $pin,
                'item' => $item,
                'expired_at' => Carbon::now()->addMinutes($expired_in_minutes),
            ];
        } else {
            $resetData = [
                'pin_type' => $pin_type,
                'pin' => $pin,
                'item' => $item,
                'expired_at' => Carbon::now()->addMinutes($expired_in_minutes),
            ];
        }

        if (DB::table('pin_verifications')->where([['item', $item], ['pin_type', $pin_type]])->exists()) {
            DB::table('pin_verifications')->where([['item', $item], ['pin_type', $pin_type]])->update($resetData);
        } else {
            DB::table('pin_verifications')->insert($resetData);
        }
        return $pin;
    }

    public function sendPinToPhone($pin, $contact_no)
    {
        try {
            $data = array(
                "flow_id" => "63daa7b5d6fc0561d2211952",
                "sender" => "Yuween",
                "mobiles" => $contact_no,
                "var1" => $pin
            );
            $response = Http::contentType("text/plain")->withHeaders(["authkey" => "372157A02VtyIIWs627e8d81P1"])->post('https://api.msg91.com/api/v5/flow/', $data)->json();
            return true;
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    // api pin_type 1 for contact_no verification for sign-up (pin will not delete after verification)
    // api pin_type 2 for contact_no verification for forget password (pin will not delete after verification)
    // api pin_type 3 for contact_no verification for contact_no change/update (pin not delete)
    // api pin_type 11 for email verification (pin will delete after verification)
    // api pin_type 12 for email verification for forget password (pin will not delete after verification)
    // not wpi pin_type 21 for contact_no verification for sign-up (pin will not delete after verification)
    // wpi pin_type 22 for contact_no verification for forget password (pin will not delete after verification)
    // Params => user_id, pin_type, pin
    #[QueryParam("item", "int", example: 'Use for new account e.g. 923001234567')]
    #[QueryParam("user_id", "int", example: "Don't use for new account e.g. 5")]
    #[QueryParam("pin_type", "int")]
    #[QueryParam("pin", "int")]
    public function verifyPin(Request $request)
    {
        if ($request->has('user_id') && $request->filled('user_id') && $request->has('pin_type') && $request->filled('pin_type') && $request->has('pin') && $request->filled('pin')) {
            $pv = DB::table('pin_verifications')->where([['user_id', $request->query('user_id')], ['pin_type', $request->query('pin_type')], ['pin', $request->query('pin')]])->whereDate('expired_at', '>', Carbon::now());
            if ($pv->exists()) {
                $user = User::find($request->query('user_id'));
                if ($request->query('pin_type') < 11 || $request->query('pin_type') == 22) {
                    // $user->contact_no = $pv->first()->item;
                    // $user->contact_no_verified = 1;
                    return $this->updatePinVerification($user, $request->query('user_id'), $request->query('pin_type'), ['pin_message' => ['Contact no successfully verified']]);
                } elseif ($request->query('pin_type') == 11 || $request->query('pin_type') == 12) {
                    $user->email = $pv->first()->item;
                    $user->email_verified = 1;
                    return $this->updatePinVerification($user, $request->query('user_id'), $request->query('pin_type'), ['pin_message' => ['Email successfully verified']]);
                }
            } elseif ($request->query('pin_type') < 11) {
                return $this->errorResponse(['pin_message' => ['Invalid Pin please try again later']], 'Pin Expired');
            } elseif ($request->query('pin_type') == 22) {
                return view('auth.pin-code', ['error_message' =>  'Invalid Pin please try again later', 'user_id' => $request->query('user_id'), 'pin_type' => $request->query('pin_type')]);
            } else {
                return view('error-page', ['error_message' => 'Invalid Pin please try again later']);
            }
            // elseif is for new signup/account
        } elseif ($request->has('item') && $request->filled('item') && $request->has('pin_type') && $request->filled('pin_type') && $request->has('pin') && $request->filled('pin')) {
            if (DB::table('pin_verifications')->where([['item', $request->query('item')], ['pin_type', $request->query('pin_type')], ['pin', $request->query('pin')]])->whereDate('expired_at', '>', Carbon::now())->exists()) {
                if ($request->query('pin_type') == 1) {
                    return $this->updatePinVerification(null, $request->query('item'), $request->query('pin_type'), ['pin_message' => ['Contact no successfully verified']]);
                } elseif ($request->query('pin_type') < 11) {
                    return $this->errorResponse(['pin_message' => ['Invalid Pin please try again later']], 422);
                } else {
                    return redirect()->route('login')->with('status', 'Invalid Pin please try again later');
                }
            } elseif ($request->query('pin_type') < 11) {
                return $this->errorResponse(['pin_message' => ['Invalid Pin please try again later']], 422);
            } else {
                return redirect()->route('login')->with('status', 'Invalid Pin please try again later');
            }
        } else {
            return $this->errorResponse(['pin_message' => ['(item or user id), pin type & pin code is required']], 422);
        }
    }

    private function updatePinVerification($user, $user_id, $pin_type, $message)
    {
        if ($pin_type == 1 && $user == null) {
            PinVerification::where([['item', $user_id], ['pin_type', $pin_type]])->update(['pin_verified' => 1, 'pin_verified_at' => Carbon::now()]);
        } elseif ($pin_type == 2 || $pin_type == 22) {
            DB::table('pin_verifications')->where([['user_id', $user_id], ['pin_type', $pin_type]])->update(['pin_verified' => 1, 'pin_verified_at' => Carbon::now()]);
        } elseif ($pin_type == 3) {
            $pv = DB::table('pin_verifications')->where([['user_id', $user_id], ['pin_type', $pin_type]])->first();
            $user->contact_no = $pv->item;
            $user->contact_no_verified = 1;
            $user->save();
            DB::table('pin_verifications')->where([['user_id', $user_id], ['pin_type', $pin_type]])->delete();
        } elseif ($pin_type == 11) {
            $user->save();
            // User::findOrFail($user_id)->update(['active' => 1]);
            DB::table('pin_verifications')->where([['user_id', $user_id], ['pin_type', $pin_type]])->delete();
        } else {
            $user->save();
            PinVerification::where([['user_id', $user_id], ['pin_type', $pin_type]])->update(['pin_verified' => 1, 'pin_verified_at' => Carbon::now()]);
        }
        if ($pin_type < 11) {
            return $this->successResponse($message, 'successfully');
        } elseif ($pin_type == 11) {
            return redirect()->route('login')->with('status', 'Congrats you have successfully verified your email, login to continue');
        } elseif ($pin_type == 12) {
            return view('auth.forgot-password', ['user_id' => $user_id]);
        } elseif ($pin_type == 22) {
            return view('auth.reset-password', ['pin_message' => 'Pin code successfully verified', 'user_id' => $user_id, 'pin_type' => $pin_type]);
        }
    }
}
