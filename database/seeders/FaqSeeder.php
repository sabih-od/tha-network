<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::create([
            'name' => 'FAQS',
            'slug' => 'faqs',
            'content' => json_encode([
                'faqs' => [
                    [ 'question' => 'What is ThaNetwork.org?', 'answer' => 'ThaNetwork.org is a social media platform that allows its members to make post within the platform, chat with other members,  send messages to other members, create business contacts,  create a personalize avatar, and the most important thing of all, It allows each member to build a person network to earn residual income!!' ],
                    [ 'question' => 'Why Should I Join ThaNetwork.org?', 'answer' => 'If you are a person who is interested in becoming a member of an exclusive Social Media Platform that allows each member to earn lots of cash and benefit from a network of friends with common goals, then ThaNetwork.org is for you!!!  If popularity and fame are things that inspire your social media journey, you can continue all activities on other social media platforms, but make sure to add your network Referral Link to all content created to add new members to your network and earn CASH!!!' ],
                    [ 'question' => 'How do I become a member?', 'answer' => 'There are 3 ways to become a member of ThaNetwork.org. First You can go directly to the home login page and request an Invitation Code, Second you can be referred by a current member by hyperlink, and Lastly you can become a member by receiving an Invitation Code and entering the code on the Login Page of the Web Portal under the Invitation Code tab.' ],
                    [ 'question' => 'What is the Benefit of membership?', 'answer' => 'The benefit of membership is earning residual income and becoming the top network member!' ],
                    [ 'question' => 'What Can I do on the Site?', 'answer' => 'You can send referrals to friends and followers, and you can perform all of the normal activities granted by social media platforms, but the interactions will be limited to network members.' ],
                    [ 'question' => 'How do I get a Invitation Code or Referral Link?', 'answer' => 'Anyone can receive a invitation link or code from a current member of ThaNetwork.org, or you can receive an invitation code from the Login' ],
                    [ 'question' => 'Who can become a member?', 'answer' => 'Anyone above the age of 17 can become a member of ThaNetwork.org.' ],
                    [ 'question' => 'How do I send Referrals and get people to join My Network?', 'answer' => 'As a member, go to your home profile page, use the share your profile button to copy your referral link to send to others, or you can select the make a referral button to send a referral email to potential members.' ],
                    [ 'question' => 'How do I Edit my profile?', 'answer' => 'Log in to your profile, from your home page select the avatar picture in the upper menu selection area, and select the edit profile link.' ],
                    [ 'question' => 'How do I Cancel my Membership?', 'answer' => 'Log in to your profile, from your home page select the avatar picture in the upper menu selection area, and select the Edit Profile link.  Once on the Edit Profile page, scroll to the bottom of the page, select the close my account command button, and follow the directions.' ],
                    [ 'question' => 'How does the monthly Automatic payment work?', 'answer' => 'All membership payments are automatically withdrawn on the 1st of each month from the account used by you for payments.' ],
                    [ 'question' => 'How and when do I receive referral payments?', 'answer' => 'All referral payments are distributed on the 15th of each month for current members.  Remember that each person who you refer must be a member for at least 30 days before earning monthly membership payments for that person referred.  The initial membership payment will be distributed by the 15th of the month if the referral was made prior to the 12th of the month.' ],
                    [ 'question' => 'What happens if I miss a payment?', 'answer' => 'If you miss a payment you will have until the 7th of the month to update your payment method, by going to the bottom of the Edit Profile page and selecting the “Manager Your Stripe Subscription” button.  Once you have followed the instructions to update your payment information you will receive a confirmation email and your account will be updated.' ],
                    [ 'question' => 'What happens when my membership is closed?', 'answer' => 'You will lose all membership privileges along with All Network Followers.  You will also lose all monthly membership payments.' ],
                    [ 'question' => 'If my account is closed can I re-open my account and reclaim my personal network?', 'answer' => 'If your account is closed, you will have until the 11th of the month to make a request to reinstate your profile.  To reinstate your profile send a request to info@thanetwork.org.  In the subject enter the date, and title the email, “Reinstate My Account”.Make sure to give your account username so that the account can be searched for.  If the request is not received by the 12th of the month, we will be unable to reinstate your account.' ],
                ]
            ]),
        ]);
    }
}
