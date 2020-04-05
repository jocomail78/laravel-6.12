<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{
    private static $adminUserIdList = [1];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return array
     */
    public static function getAdminUserIdList()
    {
        return static::$adminUserIdList;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.list')->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        if(!$user){
            return redirect('/dashboard')->with('error','User does not exists');
        }
        return view('users.edit')->with('user',$user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if(!$user){
            return redirect('/dashboard')->with('error','User does not exist');
        }

        $customMessages = [
            'password.regex'  => 'The password must have at least 8 characters, one uppercase, one lowercase, one number and one special character',
        ];
        $this->validate($request,[
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|max:255',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
            ],
            'email_verified_at' => 'nullable|date',
            'terms_accepted_at' => 'nullable|date',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
        ], $customMessages);
        $emailChanged = false;
        if($user->email != $request->email){
            $emailChanged = true;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if(!$emailChanged){
            if (DateTime::createFromFormat('Y-m-d H:i:s', $request->email_verified_at) !== FALSE) {
                $user->email_verified_at = $request->email_verified_at;
            }else{
                $user->email_verified_at = null;
            }
        }else{
            $user->email_verified_at = null;
        }

        $user->terms_accepted_at = $request->terms_accepted_at;
        $user->created_at = $request->created_at;
        $user->updated_at = $request->updated_at;

        if(isset($request->password)){
            $user->password = Hash::make($request->password);
        }
        $user->save();
        if($emailChanged){
            $user->sendEmailVerificationNotification();
        }

        return redirect('/dashboard')->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userId = intval($id);

        $loggedInUser = auth()->user();
        if(!$loggedInUser){
            return response()->json([
                'success' => false,
                'message' => 'You cannot make changes in the system if you are not logged in.'
            ]);
        }

        if($loggedInUser->id ==$userId){
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete yourself'
            ]);
        }

        if(in_array($id, $this::$adminUserIdList)){
            return response()->json([
                'success' => false,
                'message' => 'Admin user cannot be deleted in this example project'
            ]);
        }

        $user = User::find($userId);
        if($user){
            $user->delete();
        }
        return response()->json([
            'success' => true,
            'message' => 'User deleted'
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unverify($id)
    {

        $user = User::find($id);
        if($user){
            $user->email_verified_at = null;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'User unverified'
        ]);
    }

    /**
     * @param $term
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search($term)
    {
        if(!strlen($term)){
            $users = User::all()->paginate(10);
            $isSearch = false;
        }else{
            $term = htmlentities($term, ENT_QUOTES, 'UTF-8', false);
            $users = DB::table('users')
                ->where(DB::raw('LOWER(name)'),'LIKE','%'.strtolower($term).'%')
                ->orWhere(DB::raw('LOWER(email)'),'LIKE','%'.strtolower($term).'%')
                ->orWhere(DB::raw('LOWER(phone)'),'LIKE','%'.strtolower($term).'%')
                //->get();
                //->inRandomOrder()
                ->paginate(10);
            $isSearch = true;
        }
        return view('users.list')->with('users',$users)->with('isSearch',$isSearch);
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptTerms()
    {
        $loggedInUser = auth()->user();
        if(!$loggedInUser) {
            return redirect('/login')->with('error','You cannot make changes if you are not logged in');
        }

        $user = User::find($loggedInUser->id);
        if($user){
            $user->terms_accepted_at = date('Y-m-d H:i:s');
            $user->save();

            Session::flash('updatedTerms',false);
            return redirect('/dashboard')->with('success','Terms of services accepted. Thank you.');
        }

        return redirect('/login')->with('error','User does not exists');
    }

}
