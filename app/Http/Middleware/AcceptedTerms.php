<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Term;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;


class AcceptedTerms
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if($user){
            $user = User::find($user->id);
            $termsAcceptedAt = $user->terms_accepted_at;
            $latestTerm = Term::whereNotNull('published_at')->orderBy('published_at','desc')->first();
            if(strtotime($termsAcceptedAt)<strtotime($latestTerm->published_at)){
                Session::flash('updatedTerms',true);
                $termAcceptedByTheUser = Term::where('published_at','<=',$termsAcceptedAt)->orderBy('published_at','desc')->first();
                if($termAcceptedByTheUser){
                    Session::flash('acceptedId',$termAcceptedByTheUser->id);
                }
            }
        }
        return $next($request);
    }
}
