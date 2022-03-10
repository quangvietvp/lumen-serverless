<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\FirebaseException as FirebaseException;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use App\Models\Profile;


class UserController extends Controller
{
    protected $auth;

    //
    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            // Create user on firebase
            $userProperties = [
                'email' => $request->input('email'),
                'emailVerified' => false,
                'password' => $request->input('password'),
                'displayName' => $request->input('name'),
                'disabled' => false,
            ];
            $this->auth->createUser($userProperties);

            $profile = Profile::create($request->all());
            return response()->json(['message' => 'User created successful']);
        } catch (FirebaseException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode(), ['X-Header-One' => 'Header Value']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($request['email'], $request['password']);
            //$loginUid = $signInResult->firebaseUserId();

            // Generate token
            $token = $signInResult->idToken();
            return response()->json(['message' => 'Login successful', 'token' => $token]);

        } catch (FirebaseException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws FirebaseException
     * @throws \Kreait\Firebase\Exception\AuthException
     */
    public function logout(Request $request) {
        $idTokenString = $request->bearerToken();
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idTokenString);
            $uid = $verifiedIdToken->claims()->get('sub');
            $this->auth->revokeRefreshTokens($uid);
            return response()->json(['message' => 'Logout successfully'], 200);
        } catch (FailedToVerifyToken $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

  /*  public function dummy(Request $request)
    {
        $idTokenString = $request->input('token');
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idTokenString);
        } catch (FailedToVerifyToken $e) {
            echo 'The token is invalid: ' . $e->getMessage();
        }

        $uid = $verifiedIdToken->claims()->get('sub');

        $user = $this->auth->getUser($uid);
        print_r($user->jsonSerialize());
        return response()->json(['uid' => $uid]);
    }*/
}
