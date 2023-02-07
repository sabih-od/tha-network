<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //ABOUT US
        Page::create([
            'name' => 'About',
            'slug' => 'about-us',
            'content' => json_encode([
                'banner_title' => 'About Us',
                'abt_heading' => 'About Us',
                'sub_content' => 'Our in-house programs and remote learning classes create a hands-on, collaborative learning experience.',
                'main_content' => 'Judiann’s Fashion Design Studios aims at providing a higher
                        education level for students looking to enhance their skill sets and learn professional methods of constructing a garment.
                        Additionally we aim to teach students just starting out who want to gain a professional level of
                        learning. Our programs are designed to help the individual reach their goals. These goals could
                        be just personal learning to make things for themselves., for students applying to college and
                        need to prepare a professional portfolio, or for students who just need extra help while in
                        college.'
            ]),
        ]);
    }
}
