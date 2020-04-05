<?php

namespace App\Console\Commands;

use App\Term;
use App\User;
use Illuminate\Console\Command;

class PublishedTermsCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'terms:cleanup-published';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleaning all published terms which is not attached to any user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function keepTheFirstDeleteTheRest()
    {
        $latestTerm = Term::whereNotNull('published_at')->orderBy('published_at','desc')->first();
        $affected = Term::whereNotNull('published_at')->whereNotIn('id', [$latestTerm->id])->delete();
        $this->info('Deleted '.$affected.' terms.');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $usedTermsList = array();
        $users = User::all();
        if($users->isEmpty()){
            //there are no users, so non of the terms are attached to any of the users
            //so basically we can delete all terms, accoding to the rules.
            //still, I'll keep the latest.
            $this->info('No user found. Deleting all the Terms except the latest.');
            $this->keepTheFirstDeleteTheRest();
            return true;
        }
        foreach($users as $user){
            $termsAcceptedAt = $user->terms_accepted_at;
            $termAcceptedByTheUser = Term::whereNotNull('published_at')->where('published_at','<=',$termsAcceptedAt)->orderBy('published_at','desc')->first();
            if($termAcceptedByTheUser){
                if(!in_array($termAcceptedByTheUser->id, $usedTermsList)){
                    $usedTermsList[] = $termAcceptedByTheUser->id;
                }
            }
        }
        if(count($usedTermsList)){
            $affected = Term::whereNotNull('published_at')->whereNotIn('id', $usedTermsList)->delete();
            $this->info('Deleted '.$affected.' published terms. | Keeping '.count($usedTermsList).' published terms.');
            return true;
        }else{
            //no terms are attached to the users. Maybe missconfiguration, maybe something else.
            $this->info('None of the terms were attached to any of the users. Deleting all the Terms except the latest');
            //Still, best solution I think would be to
            $this->keepTheFirstDeleteTheRest();
            return true;
        }
    }
}
