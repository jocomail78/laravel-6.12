<?php

use Illuminate\Database\Seeder;

class TermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('terms')->insert([
            'title' => 'Oldest seed Terms of services',
            'content' => 'Oldest seed Terms of services '.file_get_contents('http://loripsum.net/api/10/medium/plaintext'),
            'published_at' => '2020-01-01 00:00:00',
            'created_at' => '2020-01-01 00:00:00',
            'updated_at' => '2020-01-01 00:00:00',
        ]);

        DB::table('terms')->insert([
            'title' => 'Published seed Terms of services',
            'content' => 'Published seed Terms of services '.file_get_contents('http://loripsum.net/api/10/medium/plaintext'),
            'published_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('terms')->insert([
            'title' => 'Unpublished seed Terms of services',
            'content' => 'Unpublished seed Terms of services '.file_get_contents('http://loripsum.net/api/10/medium/plaintext'),
            'published_at' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('terms')->insert([
            'title' => 'Cleanup victim',
            'content' => 'Cleanup victim seed Terms of services '.file_get_contents('http://loripsum.net/api/10/medium/plaintext'),
            'published_at' => '2019-01-01 00:00:00',
            'created_at' => '2019-01-01 00:00:00',
            'updated_at' => '2019-01-01 00:00:00',
        ]);
    }
}
