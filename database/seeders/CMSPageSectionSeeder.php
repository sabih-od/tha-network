<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Seeder;

class CMSPageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::truncate();

        $about = Page::create([
            'name' => 'About',
            'slug' => 'about',
            'content' => json_encode([
                'banner_image' => asset('images/banner.jpg'),
                'banner_circle_title' => 'ABOUT US',
                'banner_circle_title_2' => 'Membership Pays',
                'banner_circle_text' => 'The Social Media Network that allows its members to earn cash ',
                'section_1_image' => asset('images/abtImg.png'),
                'section_1_title' => 'About Us',
                'section_1_description' => 'What makes Tha Network different from other Social Media Sites',
                'section_1_title_2' => 'What makes Tha Network different from other Social Media Sites',
                'section_1_description_2_line_1' => 'Tha Network is a social media site created to help people help each other while having fun and building a large network of friends.',
                'section_1_description_2_line_2' => 'Our members earn cash just by making referrals to other people and getting them to join the network. ',
                'section_1_description_2_line_3' => 'Our members earn a referral fee for each person that joins and earns monthly payments for the life of that persons membership. ',
                'section_1_description_2_line_4' => 'If you invited 100 people to join your network and earned $10 per person, you would make a $1,000 if all 100 people joined. If each person keeps their membership you will continue to earn $1,000 per month for the life of their membership. Now let’s use real numbers, multiple the number of (friends and followers) you have on all your social media sites by $10 per person!! This is how much you could earn per month if all your friends and followers joined your network. ',
                'section_1_description_2_line_5' => 'Members can invite as many people as they like. ',
                'section_2_image' => asset('images/abtImg2.png'),
                'section_2_title' => 'Our Promise',
                'section_2_description' => 'Tha Network promises to allow its members to enjoy the benefit of membership.. ',
                'section_2_title_2' => 'Membership offers benefits.',
                'section_2_description_2_line_1' => 'Your personal information is secure.',
                'section_2_description_2_line_2' => 'You create your own personal network.',
                'section_2_description_2_line_3' => 'You earn cash and have fun sharing information with people within your network.',
                'section_3_image' => asset('images/overlayBg.png'),
                'section_3_title' => 'Membership Pays',
                'section_3_description' => 'You earn cash while doing the things you like without needing to be behind a desk for an 8hr work day! ',
                'section_4_image' => asset('images/abtImg3.png'),
                'section_4_title' => 'Let’s Change Social Media ',
                'section_4_description' => 'Usually people who are on social media platforms are concerned with how many likes, friends, views, and followers they have but they are missing one key element!! ',
                'section_4_title_2' => 'THA NETWORK',
                'section_4_description_2_line_1' => 'Unlimited Friends. ',
                'section_4_description_2_line_2' => 'Communicate with family & Friends around the world. ',
                'section_4_description_2_line_3' => 'Send messages. ',
                'section_4_description_2_line_4' => 'Business Marketing ',
                'section_4_description_2_line_5' => 'Earn CASH from each person in your network!!! ',
                'section_5_image' => asset('images/abtImg4.png'),
                'section_5_title' => 'Other Social Media Sites',
                'section_5_description_line_1' => 'Unlimited Friends. ',
                'section_5_description_line_2' => 'Communicate with family & Friends around the world. ',
                'section_5_description_line_3' => 'Send messages via video or messaging. ',
                'section_5_description_line_4' => 'Business Marketing ',
            ]),
        ]);

        $home = Page::create([
            'name' => 'Home',
            'slug' => 'home',
            'content' => json_encode([
                'banner_image' => asset('images/banner.jpg'),
                'banner_circle_title' =>  'Membership Pays',
                'section_2_title' => 'HOW IT WORKS',
                'section_2_element_1_text' => 'Become a Member',
                'section_2_element_2_text' => 'Refer Friends',
                'section_2_element_3_text' => 'Earn Cash',
                'title' => 'Our Benefits',
                'element_1_image' => asset('images/benefit1.jpg'),
                'element_1_title' => 'Membership Benefits',
                'element_1_description_line_1' => 'Once you become a member the Sky is the Limit in your earning potential. We have developed this site to help everyone earn extra cash while also donating a portion of the sites proceeds to charities. Enjoy a full network of people to chat with, share information with, send daily post, and brag about the number of members in your Network!!! Earn Financial Freedom!! Membership has never been this great!!!!',
                'element_1_description_line_2' => 'Have fun, keep the invites coming, build your network!!!',
                'element_1_description_line_3' => 'Become a member today!!!!',
                'element_2_image' => asset('images/benefit2.jpg'),
                'element_2_title' => 'Let Me Break it Down for you by doing the math!',
                'element_2_description' => 'If you invited 100 people to join your network (100 x $10 = $1,000) you could earn $1,000 if each person joined the network. If each of the 100 people continue their membership with Tha Network, you will continue to earn $1,000 per month for the life of their membership. Now let’s use real numbers, multiple the number of (friends and followers) you have on all your social media sites by $10 per person!! This is how much you could earn per month if all your friends and followers joined your network. What are you waiting for JOIN TODAY!!!',
                'element_3_image' => asset('images/benefit3.jpg'),
                'element_3_title' => 'Membership Pays',
                'element_3_description' => 'Join Today for $29.99 per month!!! This will be the best investment you have ever made!!!!! You Should be Excited because WE ARE!!!',
                'element_3_title_2' => 'HOW MANY PEOPLE ARE IN YOUR NETWORK!!!!',
                'element_3_image_2' => asset('images/character.png'),
                'element_3_title_3' => 'REMEMBER, MEMBERSHIP PAYS!!!!',
                'element_3_description_2_line_1' => 'This site was created to allow its members to earn cash by making referrals to the people you know or meet, and allows you to enjoy the benefits that come with a social media experience. ',
                'element_3_description_2_line_2' => 'You will earn Monthly Income by just convincing your contacts to join your network. Once they join all you need to do is watch the cash add up in your account! ',
                'element_3_description_2_line_3' => 'The site does all the work for you automatically by sending payments directly to you when your referrals join Tha Network. ',
                'section_3_plan_1_image' => asset('images/plan1.jpg'),
                'section_3_plan_2_image' => asset('images/plan2.jpg'),
                'section_3_plan_3_image' => asset('images/plan3.jpg'),
                'section_3_plan_4_image' => asset('images/plan4.jpg'),
                'section_3_plan_5_image' => asset('images/plan5.jpg'),
                'section_3_title' => 'OUR MEMBERSHIP PLAN PAYS!',
                'section_3_description_line_1' => 'As a member your membership payment will never change, but you will have the ability to earn more cash when the site becomes More Popular!',
                'section_3_description_line_2' => 'Now is the time to become a member While Membership Rates are Inexpensive!!!!!!',
                'section_3_description_line_3' => 'So What Are You Waiting For JOIN TODAY!!!',
            ]),
        ]);

        $benefits = Page::create([
            'name' => 'Benefits',
            'slug' => 'benefits',
            'content' => json_encode([
                'banner_image' => asset('images/banner.jpg'),
                'banner_circle_title' =>  'MEMBERSHIP BENIFITS',
                'section_2_image' => asset('images/benefit1.png'),
                'section_2_title' => 'MEMBERSHIP BENEFITS',
                'section_2_description_line_1' => ' Once you become a member, the Sky is the Limit to your earning potential. We have developed this site to help it’s members earn extra cash without over working yourself like a social media Influencer. Enjoy a full network of people to chat with, share information with, send daily post, and to brag about the number of members in your Network!!! Earn Financial Freedom!! Membership has never been this great!!!! ',
                'section_2_description_line_2' => ' Have fun, keep the invites coming, build your network!!! ',
                'section_2_description_line_3' => ' Become a member today!!!! ',
                'section_3_image' => asset('images/benefit2.jpg'),
                'section_3_title' => 'LET ME BREAK IT DOWN FOR YOU BY DOING THE MATH!',
                'section_3_description' => ' If you invited 100 people to join your network (100 x $10 = $1,000) you could earn $1,000 if each person joined the network. If each of the 100 people continue their membership with Tha Network, you will continue to earn $1,000 per month for the life of their membership. Now let’s use real numbers, multiple the number of (friends and followers) you have on all your social media sites by $10 per person!! This is how much you could earn per month if all your friends and followers joined your network. What are you waiting for JOIN TODAY!!! ',
                'section_4_image' => asset('images/benefit3.jpg'),
                'section_4_title' => 'MEMBERSHIP PAYS',
                'section_4_description_line_1' => ' Join Today for $29.99 per month!!! This will be the best investment you have ever made!!!!! You Should be Excited because WE ARE!!! ',
                'section_4_description_line_2' => ' Join Now and lock in your membership payment because membership dues will be increasing as the site’s popularity grows!!!! Be one of the first Members to help make Tha Network an EPIC social media experience! ',
                'section_4_title_2' => ' HOW MANY PEOPLE ARE IN YOUR NETWORK!!!! ',
                'section_4_image_2' => asset('images/character.png'),
                'section_4_title_3' => ' $$$ REMEMBER, MEMBERSHIP PAYS $$$ ',
                'section_4_description_2_line_1' => ' This site was created to allow its members to earn cash by making referrals to the people you know or meet, and you will enjoy the benefits that come with a social media experience. ',
                'section_4_description_2_line_2' => ' You will earn Monthly Income by just convincing your contacts to join your network. Once they join all you need to do is watch the cash add up in your account! ',
                'section_4_description_2_line_3' => ' The site does all the work for you automatically by sending payments directly to your bank account when your referrals join Tha Network. ',
            ]),
        ]);
    }
}
