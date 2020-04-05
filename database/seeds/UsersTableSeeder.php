<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    public function randomPhoneNumber() {
        $requiredLength = 7;
        $highestDigit = 9;
        $sequence = '';
        for ($i = 0; $i < $requiredLength; ++$i) {
            $sequence .= mt_rand(0, $highestDigit);
        }

        $numberPrefixes = ['036', '072', '073', '074'];

        return $numberPrefixes[array_rand($numberPrefixes)].$sequence;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!User::find(1)){
            User::firstOrCreate([
                'id' => '1',
                'name' => 'Admin user ',
                'email' => 'electrum89+admin@gmail.com',
                'phone' => '0747855844',
                'password' => Hash::make('Test1234!'),
                'created_at' => '2020-01-26 08:00:00',
                'updated_at' => '2020-01-26 08:00:00',
                'terms_accepted_at' => '2020-01-26 08:00:00',
                'email_verified_at' => '2020-01-26 08:00:00',
            ]);
        }

        for($i=0;$i<3;$i++){

            DB::table('users')->insert([
                'name' => 'Seed user '.Str::random(10),
                'email' => 'electrum89+'.Str::random(10).'@gmail.com',
                'phone' => $this->randomPhoneNumber(),
                'password' => Hash::make('Test1234!'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'terms_accepted_at' => date('Y-m-d H:i:s'),
                'email_verified_at' => date('Y-m-d H:i:s'),
            ]);

        }
    }
}
