<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserAccessToken;
use App\Models\UserCompanyLink;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller;
use Throwable;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    use CanResetPassword;

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function signIn(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->input('email'))->first();

        if (Hash::check($request->input('password'), $user->password)) {
            $apikey = base64_encode(Str::random(40));
            if ($user->accessToken === null) {
                $company = UserAccessToken::new(['api_key' => $apikey]);
                $user->accessToken()->save($company);
            } else {
                $user->accessToken->update(['api_key' => $apikey]);
            }

            return response()->json(['status' => 'success', 'api_key' => $apikey]);
        } else {
            return response()->json(['status' => 'fail'], 401);
        }
    }

    /**
     * @throws ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:user|max:255',
            'password' => 'required|max:255',
            'phone' => 'required|unique:user|max:255'
        ]);
        $attributes = $request->only('first_name', 'last_name', 'email', 'password', 'phone');
        $attributes['password'] = Hash::make($attributes['password']);
        $user = User::factory()->createOne($attributes);

        return response()->json($user, 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function recoverPassword(Request $request): JsonResponse
    {
        $this->validate($request, [
            'token' => 'required|exists:password_reset',
            'email' => 'required|email|exists:user|exists:password_reset',
            'password' => 'required|min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8',
        ]);
        $user = User::where('email', $request->input('email'))->first();
        $user->password = Hash::make($request->input('password'));
        $user->save();
        PasswordReset::where('email', $request->input('email'))->delete();

        return response()->json('Password changed successful!', 201);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function forgetPassword(Request $request): JsonResponse
    {
        $this->validate($request,
            [
                'email' => 'required|email|exists:user|unique:password_reset',
            ],
            [
                'email.unique' => 'Password reset request has already been sent to this email address'
            ]
        );
        $email = $request->input('email');
        $token = Str::random(64);
        PasswordReset::factory()->createOne([
            'email' => $email,
            'token' => $token
        ]);

        Mail::send('email.forgetPassword', ['token' => $token], function ($message) use ($email) {
            $message->to($email);
            $message->subject('Reset Password');
        });

        return response()->json('We have e-mailed your password reset token!');
    }

    /**
     * @return JsonResponse
     */
    public function showCompanies(): JsonResponse
    {
        return response()->json(
            Company::whereHas('userCompanyLinks', function (Builder $query) {
                $query->where('user_id', Auth::id());
            })->get()
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function createCompany(Request $request): JsonResponse
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'phone' => 'required|unique:user|max:255',
            'description' => 'max:255',
        ]);
        DB::beginTransaction();
        try {
            $company = Company::factory()->createOne($request->only('title', 'phone', 'description'));
            UserCompanyLink::factory()->createOne(['company_id' => $company->id, 'user_id' => Auth::id()]);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            return response()->json($throwable->getMessage());
        }

        return response()->json($company);
    }
}
