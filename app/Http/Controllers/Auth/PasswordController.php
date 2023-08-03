<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Header;

class PasswordController extends Controller
{
    use ApiResponser;
    private $pvController;

    public function __construct(PinVerificationController $pvController)
    {
        $this->pvController = $pvController;
    }

    #[Header("Authorization", example: "Auth token is required")]
    #[BodyParam("current_password", "string", example: "abcd1234", required: true)]
    #[BodyParam("password", "string", example: "12345678", required: true)]
    #[BodyParam("password_confirmation", "string", example: "12345678", required: true)]
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|min:8|max:12',
            'password' => 'required|min:8|max:12|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Authorization error');
        }

        $user = Auth::user();
        $userPassword = $user->password;

        if (!Hash::check($request->current_password, $userPassword)) {
            return $this->errorResponse(['current_password' => ['Current password not match']], 422);
        }

        $user = User::find($user->id);
        $user->password = Hash::make($request->password);
        $user->save();
        return $this->successResponse(null, 'Password successfully changed');
    }

    #[BodyParam("contact_no", "string", example: "923001234567", required: true)]
    public function forgotPassword(Request $request)
    {
        $custom_message = [
            'contact_no.exists' => 'The contact no is not found',
        ];
        $validator = Validator::make($request->all(), [
            'contact_no' => 'required|numeric|exists:users,contact_no',
        ], $custom_message);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Account not found');
        }
        $user = $this->findByContactNo($request->contact_no);
        if ($user) {
            $pinType = 2;
            $pin = $this->pvController->generatePin($user->id, $pinType, $request->contact_no);
            // $pinSent = $this->pvController->sendPinToPhone($pin, $request->contact_no);
            $pinSent = true;
            if ($pinSent) {
                return $this->successResponse(['pin_message' => $pin . 'Pin code has been sent to ' . $request->contact_no, 'user_id' => $user->id, 'pin_type' => $pinType], 200);
            }
            return $this->errorResponse(['contact_no' => ['We are sorry, Unable to send pin code to ' . $request->contact_no]], 404);
        }
        return $this->errorResponse(['contact_no' => ['Invalid contact no']], 422);
    }

    public function findByContactNo($contact_no)
    {
        return User::where('contact_no', $contact_no)->first();
    }

    #[BodyParam("user_id", "int", example: "5", required: true)]
    #[BodyParam("pin_type", "int", example: "7", required: true)]
    #[BodyParam("password", "string", example: "12345678", required: true)]
    #[BodyParam("password_confirmation", "string", example: "12345678", required: true)]
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:pin_verifications,user_id',
            'pin_type' => 'required',
            'password' => 'required|min:8|max:12|confirmed'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Invalid Password Details');
        }

        if (DB::table('pin_verifications')->where([['user_id', $request->user_id], ['pin_type', $request->pin_type], ['pin_verified', 1], ['expired_at', '>', Carbon::now()]])->exists()) {
            DB::transaction(function () use ($request) {
                DB::table('users')->where('id', $request->user_id)->update(['password' => Hash::make($request->password)]);
                DB::table('pin_verifications')->where('user_id', $request->user_id)->delete();
            });
            return $this->successResponse(null, 'Password successfully changed');
        }
        return $this->errorResponse(null, 'Invalid Pin Code, please try again');
    }
}
