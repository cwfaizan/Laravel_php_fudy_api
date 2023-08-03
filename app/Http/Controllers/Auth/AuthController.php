<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Knuckles\Scribe\Attributes\Header;
use App\Models\PinVerification;
use App\Models\Role;
use App\Traits\ApiResponser;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\QueryParam;
use Craftsys\Msg91\Facade\Msg91;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    use ApiResponser;
    private $pvController;

    public function __construct(PinVerificationController $pvController)
    {
        $this->pvController = $pvController;
    }

    #[BodyParam("contact_no", "string", example: "923001234567", required: true)]
    #[BodyParam("account_type", "int", example: "1 for customer, 2 for waiter", required: true)]
    public function checkAccount(Request $request)
    {
        $custom_message = [
            'contact_no.unique' => 'The contact no already exists, login to continue.',
        ];
        $validator = Validator::make($request->all(), [
            'contact_no' => 'required|regex:/^[0-9]+$/',
            'account_type' => ['required', Rule::in([1, 2]),],
        ], $custom_message);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        $user = User::where('contact_no', $request->contact_no);
        if ($user->exists()) {
            $user = $user->first();
            if ($user->hasAccount($request->account_type)) {
                return $this->errorResponse(["contact_no" => ["The Account already exists, login to continue."]], 303);
            } else {
                return $this->errorResponse(["contact_no" => ["The Account already exists, complete profile to continue"], "user_id" => $user->id], 302);
            }
        }

        $mPinType = 1;
        $mPin = $this->pvController->generatePin(null, $mPinType, $request->contact_no);
        // $contacted = $this->pvController->sendPinToPhone($mPin, $request->contact_no);
        $contacted = true;
        if ($contacted) {
            return $this->successResponse([
                'pin_message' => $mPin . 'Pin code has been sent to ' . $request->contact_no,
                'pin_type' => $mPinType,
                'item' => $request->contact_no,
            ], 'success');
        }
        return $this->errorResponse(['contact_no' => ['Invalid contact no']], 422);
    }

    #[BodyParam("name", "string", required: true)]
    #[BodyParam("email", "string", required: true)]
    #[BodyParam("contact_no", "string", example: "923001234567", required: true)]
    #[BodyParam("password", "string", example: "12345678", required: true)]
    #[BodyParam("password_confirmation", "string", example: "12345678", required: true)]
    #[BodyParam("account_type", "int", example: "1 for customer, 2 for waiter", required: true)]
    public function signup(Request $request)
    {
        $custom_message = [
            'contact_no.unique' => 'The mobile no is already registered, login to continue.',
            'name.regex' => 'The full name must be combination of alphabet',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z]+(?:\s[a-zA-Z]+)+$/',
            // 'first_name' => 'required|regex:/^[a-zA-Z]+(?:\s[a-zA-Z]+)*$/',
            // 'last_name' => 'required|regex:/^[a-zA-Z]+(?:\s[a-zA-Z]+)*$/',
            // 'email' => 'required|email|unique:users,email',
            'contact_no' => 'required|regex:/^[0-9]+$/|unique:users,contact_no',
            'password' => 'required|min:8|max:12|confirmed',
            'account_type' => ['required', Rule::in([1, 2]),],
        ], $custom_message);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        if ($request->account_type == 1) {
            // do code
        } elseif ($request->account_type == 2) {
            // do code
        }

        if (!PinVerification::where([['item', $request->contact_no], ['pin_type', 1], ['pin_verified', 1]])->exists()) {
            return $this->errorResponse(['contact_no' => ['contact no is not verified, please verify it first']], 422);
        }
        PinVerification::where([['item', $request->contact_no], ['pin_type', 1], ['pin_verified', 1]])->delete();

        $user = User::Create([
            'name' => $request->name,
            'contact_no' => $request->contact_no,
            'contact_no_verified' => 1,
            // 'email' => $request->email,
            // 'email_verified' => 0,
            'active' => 1,
            // 'active' => $request->account_type < 2 ? 1 : 0,
            'password' => Hash::make($request->password),
        ]);
        $user->roles()->attach(Role::where([['name', $request->account_type == 2 ? 'waiter' : 'customer']])->get(['id']));

        return $this->successResponse($user, 'successfully created', 201);
    }

    #[BodyParam("contact_no", "string", example: "923001234567", required: true)]
    #[BodyParam("password", "string", required: true)]
    // #[BodyParam("account_type", "int", example: "1 for customer, 2 for waiter", required: true)]
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_no' => 'required|numeric|exists:users,contact_no',
            'password' => 'required|min:8|max:12',
            // 'account_type' => ['required', Rule::in([1, 2]),],
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'error');
        }
        if (User::where([['contact_no', $request->contact_no], ['active', 0]])->exists()) {
            return $this->errorResponse(['active' => 'Account is de-active'], 'Account is de-active');
        }
        $credentials = ['contact_no' => $request->contact_no, 'password' => $request->password, 'active' => 1];

        if (Auth::attempt($credentials)) {
            $token = $this->generateToken();
            return $this->successResponse(['user_id' => [Auth::id()], 'name' => [Auth::user()->name], 'token_type' => ['Bearer'], 'token' => [$token->accessToken], 'token_expires_at' => [$token->token->expires_at], 'roles' => $token->token->scopes], 'successfully login');
            // if (Auth::user()->hasAccount($request->account_type)) {
            //     $token = $this->generateToken();
            //     return $this->successResponse(['user_id' => [Auth::id()], 'name' => [Auth::user()->name], 'token_type' => ['Bearer'], 'token' => [$token->accessToken], 'token_expires_at' => [$token->token->expires_at], 'roles' => $token->token->scopes], 'successfully login');
            // } else {
            //     $this->invalidateToken();
            //     return $this->errorResponse(['auth' => ['Invalid login credentials']], 401);
            // }
        }
        return $this->errorResponse(['auth' => ['Invalid login credentials']], 401);
    }

    public function generateToken()
    {
        $token = User::find(Auth::id())->createToken('codeWithFaizan', Auth::user()->roles->pluck('name')->toArray());
        $oauthTokens = DB::table('oauth_access_tokens')->where([['id', '!=', $token->token->id], ['user_id', '=', $token->token->user_id]])->get();
        if (count($oauthTokens) > 1) {
            if (Carbon::create($oauthTokens[0]->created_at)->lt(Carbon::create($oauthTokens[1]->created_at))) {
                DB::table('oauth_access_tokens')->where('id', '=', $oauthTokens[0]->id)->delete();
            } else {
                DB::table('oauth_access_tokens')->where('id', '=', $oauthTokens[1]->id)->delete();
            }
            // DB::table('oauth_access_tokens')->where([['id', '!=', $token->token->id], ['user_id', '=', $token->token->user_id]])->delete();
        }
        return $token;
    }

    #[Header("Authorization", example: "Auth token is required")]
    public function invalidateToken()
    {
        DB::table('oauth_access_tokens')->where('user_id', Auth::id())->delete();
        return $this->successResponse(null, 'Successfully Logout');
    }

    public function findByContactNo($contact_no)
    {
        return User::where('contact_no', $contact_no)->first();
    }
}
