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

        $terms = Page::create([
            'name' => 'Terms',
            'slug' => 'terms',
            'content' => json_encode([
                'banner_image' => asset('images/banner.jpg'),
                'banner_circle_title' =>  'TERMS & CONDITIONS',
                'terms_content' => '<h2>
                        Terms of Service
                    </h2>
                    <p>
                        Updated as of 11/6/2022
                    </p>
                    <p>
                        WE DO NOT CONDUCT CRIMINAL BACKGROUND CHECKS. PLEASE BE SAFE WHEN INTERACTING WITH USERS.
                    </p>
                    <p>
                        This Terms of Service Agreement (the "Agreement") controls your access and use of ThaNetwork. By
                        becoming a member to ThaNetwork you agree to this Agreement, our Privacy Policy and the Content
                        and Conduct Policy are each part of this Agreement. ONLY USERS WHO ARE 18 YEARS OF AGE OR OLDER
                        MAY REGISTER FOR THE SERVICES. By accessing the services and/or completing the registration
                        process for our Service, you represent that you are 18 years of age or older, and can and will
                        be legally bound by this Agreement. By registering to ThaNetwork, you represent and warrant that
                        you are not a registered sex offender with any government entity, and Not a state nor federal
                        Felon. No Member may participate where doing so would be prohibited by any applicable law or
                        regulation. We have created side summaries to help you easily locate specific terms within this
                        Agreement. These summaries are for reference only and in the event that there is a discrepancy
                        between this Agreement and the language of the side summaries, the Agreement will prevail.
                    </p>
                    <h2>
                        A) Changes to the Terms
                    </h2>
                    <p>
                        The Company reserves the right to change or amend this Agreement at any time, for any reason, or
                        for no reason at all, at the Company’s sole discretion. The most recent version of this
                        Agreement will be posted on the Services. Although the Company will provide notice of material
                        changes to this Agreement on the Services, as a Member it is your sole responsibility to keep
                        yourself informed of any such changes or amendments. Should a Member object to any terms and
                        conditions of the Agreement or any subsequent changes to the Agreement or become dissatisfied
                        with the Company in any way, Member`s only solution is to immediately: (1) discontinue use of
                        the Services; (2) terminate their Services registration; and (3) notify the Company of
                        termination.
                    </p>
                    <h2>
    B) Description of Services
    </h2>
                    <p>
                        As a Member, you will have access to other members profiles, send chats, and send messages. The
                        Company reserves the right to enhance, change, or discontinue the Services, in whole or in part,
                        at any time, due to business needs or legal requirements. The Company has sole discretion when
                        in the operation of the site and its content.
                    </p>
                    <h2>
    C) Member Conduct
    </h2>
                    <p>
    You must use the Services in accordance with the Content and Conduct Policy.
                    </p>
                    <p>
                        Use of the Services by you, as a Member, is subject to all applicable local, state, national and
    international laws and regulations. The Company reserves the right, but does not assume any
                        obligation, to monitor the Services to enforce this Agreement. Nor does the Company guarantee
                        that any monitoring it does perform will be to the Member`s satisfaction. Upon learning of any
                        violation of this Agreement, the Company, at its sole discretion, may terminate your access to
                        and use of the Services, require you to correct such violation, and/or take any other actions
                        that the Company deems appropriate to enforce its rights and pursue all available remedies.
                        Without limitation, the Company reserves the right to terminate your access to and use of the
                        Services if, in our view, your conduct fails to meet any of the following guidelines:
                    </p>
                    <ul>
                        <li>
                            1. Members shall not attempt to gain unauthorized access to the Company’s database or other
                            computer systems.
                        </li>
                        <li>
                            2. Members shall not attempt to change, translate, adapt, edit, decompile, disassemble, or
                            reverse engineer any software programs used by Company in connection with the Services.
                        </li>
                        <li>
                            3. Members shall not engage in any activity that disrupts, diminishes the quality of,
                            interferes with the performance of, or impairs the functionality of, the Services.
                        </li>
                        <li>
                            4. Members shall not use the account, username, or password of another Member at any time or
                            disclose their password to any third party or permit any third party to access their
                            account.
                        </li>
                        <li>
                            5. Members shall be respectful and not conduct any criminal or illegal activity on the site.
                        </li>
                        <li>
                            6. Members should not share personal identifying information or attempt to connect in person
                            with any person on the site. ThaNetwork is not responsible for any personal contact or
                            personal relationships which may be created during the course of networking on the site.
                        </li>
                        <li>
                            7. If ThaNetwork gets information of any illegal activity on the site the member’s
                            membership will be cancelled.
                        </li>
                        <li>
                            8. No Member shall give or sell any other member’s personal information to a third-party.
                        </li>
                        <li>
                            9. ThaNetwork does not support nor are we responsible for any business offerings or
                            opportunities solicited between members. If the activity is illegal in nature the member/s
                            participating in the activity will lose their membership privileges.
                        </li>
                        <li>
                            10. Complaints of Harassment are taking very seriously and if evidence shows any indication
                            of harassment, threats, or any illegal activity your account will be cancelled as soon as
                            ThanNetwork.org is made aware of such activity.
                        </li>
                    </ul>
                    <p>
                        All decisions concerning the applicability of these guidelines shall be at the sole and
                        exclusive discretion of the Company and its designees. The Company has the right in its sole
                        discretion to pre-screen, refuse or remove any content that is available via the Network. The
                        Company and its designees shall have the right to remove any Content that violates this
                        Agreement or is otherwise objectionable. An account may be terminated at any time, without
                        notice, depending on the severity of the offense, which is determined exclusively at the
                        discretion of the Company. The Company is not obligated to provide a member with a warning prior
                        to removal.
                    </p>
                    <h2>
                        D) Privacy
                    </h2>
                    <p>
                        The Company has established a Privacy Policy to explain to Members how their information is
                        collected and used. The policy explains how and when the Company may use Member information and
                        content. Member`s use of our network signifies acknowledgment of and agreement to the Company’s
                        Privacy Policy.
                    </p>
                    <h2>
    E) Document Retention Schedule
    </h2>
                    <p>
    All personal information collected by the Company in connection with your use of the Services,
                        including, without limitation, your name, physical address, DOB, Social, email address,
                        pictures, friend connections, messages, comments, login information, IP addresses and other
                        data, may be stored by the Company indefinitely and will be stored in a safe and secure manner.
                    </p>
                    <h2>
    F) Notice Regarding Commercial Email
    </h2>
                    <p>
    MEMBERS CONSENT TO RECEIVE COMMERCIAL E-MAIL MESSAGES FROM THE COMPANY, AND ACKNOWLEDGE AND
    AGREE THAT THEIR EMAIL ADDRESSES AND OTHER PERSONAL INFORMATION MAY BE USED BY THE COMPANY FOR
        THE PURPOSE OF INITIATING COMMERCIAL E-MAIL MESSAGES.
                    </p>
                    <h2>
    G) Member Account and Password
    </h2>
                    <p>
    Once Member registers for the Services, Member will have a password and an account with the
                        company. Member is responsible for keeping the Member`s password and account confidential.
                        Furthermore, Member is entirely responsible for any and all activities that occur under Member`s
                        account. Member agrees to immediately notify the Company of any unauthorized use of Member`s
                        account or any other breach of security known to Member.
                    </p>
                    <h2>
                        H) Membership
                    </h2>
                    <p>
                        Each member will have an account which has a re-occurring membership fee and the fee will be
                        made upon initiation of membership and automatically withdrawn on the 1st of each month after
                        initiation. CANCELLATION Any member may cancel their membership at any time, ThaNetwork will not
                        give refunds when a membership is cancelled. All Members who cancel their account will lose all
                        membership benefits to include the loss of monthly payments for referred members. Only Paid
                        Members will earn payments for referrals made. Once the membership is cancelled, the member’s
                        profile will be deleted.
                        Each member who uses the site and builds their personal network will receive payment for signing
                        up each member on a monthly basis, for as long as that member continues their membership. Your
                        membership payment will be withdrawn on the 1st of every month and your monthly earnings will be
                        available to you on the 15th of each month. Payments for new members joining your network will
                        be sent to each member after payments are processed and available for transfer. If your
                        recurring payment is rejected for any reason, we will send you notifications via email, text,
                        and internal message from the site. If you have not paid your membership dues by the 7th of the
                        month, your membership will be placed in a hold status until the 14th of the month. If payment
                        is not received by the 14th your account will be closed, all network payments to you will
                        discontinue, and the member will lose all network connections within the member’s personal
                        network. The only way you can become a member to the network again is if a member invites you
                        back or if you receive an invitation code from the company, either way you will have to start
                        from scratch to rebuild your network. The community is created for members to be able to help
                        each other meet their membership goals. Each member is responsible for inviting members to the
                        site if they desire to earn payment. If you do not invite any members, you will not earn
                        payment. ThaNetwork.org is not responsible for paying any member payment for just joining the
                        site. Referral Payment is earned by getting others to join the site, if you do not refer anyone
                        you will not receive Referral Compensation. Each member may invite as many people as you like to
                        join your network. This is not a multilevel marketing site; each member earns cash for making
                        referrals and getting those referrals to join the member’s network. ThaNetwork has a simple
                        philosophy, GET AS MANY PEOPLE to join your network to reap the benefits of fulfilling your
                        earning goals. As the membership demand for the network grows, the cost to become a member and
                        the member’s earnings will increase. Membership fee increases will only apply to new member’s
                        joining the site. Each member’s original membership fee will never change for the life of the
                        member’s membership. (Example) member joins and membership fee is $29.99 per month. Six months
                        later the membership fee increases to $59.99. The membership fee increase will only apply to new
                        members who join ThaNetwork. As membership fees increase each member who invites new members at
                        the increased membership fee will earn more income for each member who joins the member’s
                        network at the new rate. List of forecasted membership fee increases along with member earnings
                        for each increase.
                    </p>
                    <ul>
                        <li>
                            1. At the start of the business, membership will cost $29.99. Members will receive $10 for
                            referrals.
                        </li>
                        <li>
                            2. At Level 2 after there are a total of 500,000 members the new membership price will
                            increase to $59.99 for new members joining. Members will receive $15 per referral at this
                            level.
                        </li>
                        <li>
                            3. At level 3 and after 2,000,000 members the membership fee will increase to $99.99 and the
                            member will earn $25 per referral.
                        </li>
                        <li>
                            4. At Level 4 after 5,000,000 members the membership fee will increase to $159.99 and the
                            member will earn $50 per referral at this level.
                        </li>
                        <li>
                            5. At Level 5 after 7,000,000 members the membership fee will increase to $299.99 and the
                            member will earn $100 per referral at this level.
                        </li>
                        <li>
                            6. At Level 6 after 10,000,000 members the membership fee will increase to $399.99 and the
                            member will earn $150 per referral at this level.
                        </li>
                        <li>
                            7. Next levels will depend on the traffic on the site If the demand becomes greater, the
                            membership price will increase to meet the demand. Notifications will be sent to member’s
                            when the levels change and there will be a site maintenance pause on the site to accommodate
                            the change.
                        </li>
                    </ul>
                    <h2>
                        I) Mobile
                    </h2>
                    <p>
                        The Company allows access through a mobile website. This Agreement governs all Services that are
                        accessible on or through the Mobile website. If you use the Services on a mobile device, you
                        agree that information about your use of the Services through your mobile device and carrier may
                        be communicated to us, including but not limited to the identities of your mobile carrier or
                        your mobile device, or your physical location. Although we provide our Services through the
                        mobile websites free of charge, your mobile carrier`s standard fees and rates will still apply.
    You accept responsibility for all charges.
                    </p>
                    <h2>
    J) Copyrights, Trademarks, Patents and Intellectual Property Rights
    </h2>
                    <p>
    “Content” means all data, text, software, music, sound, photographs, graphics, artwork, video,
                        pictures, images, posts, broadcasts, messages or other materials of any kind, whether publicly
                        posted or privately transmitted. Your Content is your sole responsibility. You represent and
    warrant that you own or have the necessary licenses, rights, consents and permissions to publish
                        all of your Content. Except as set forth in our Privacy Policy, we are not responsible for any
                                                                                                                   Content that you upload or transmit on the Services. We do not control the posted Content and,
                        as such, we do not control its accuracy, integrity, quality or any other aspect. Under no
                        circumstances are we liable in any way for any Content, including but not limited to any errors
    or omissions in any Content, or for any loss or damage of any kind incurred as a result of the
                        use of any Content.
                    </p>
                    <p>
    You hereby grant the Company a perpetual, assignable, world-wide, royalty free, sub-licensable
    and non-exclusive license to use, distribute, reproduce, record, modify, adapt, process,
        combine, synchronize, create derivative works from, publish, publicly perform and publicly
                        display such Content (including your user name and likeness) on the Services and elsewhere in
                        any and all media or distribution methods (now known or later developed) for any promotional and
    other commercial purpose, whether by us, our partners or other third parties, in our sole
                        discretion.
                    </p>
                    <p>
    This license authorizes the Company to make your Content available to the rest of the world and
    to let others do the same. You agree that this license includes the right for the Company to
    provide, promote, and improve the Services and to make Content submitted to or through the
                        Services available to other companies, organizations or individuals for the syndication,
                                                                                                broadcast, distribution, promotion or publication of such Content on other media and services,
                        subject to the Company’s terms and conditions for such Content use. Such additional uses by the
                        Company, or other companies, organizations or individuals, may be made with no compensation paid
                        to you with respect to the Content that you submit, post, transmit or otherwise make available
                        through the Services.
                    </p>
                    <p>
    The Company may use your feedback, comments and suggestions without any obligation to compensate
                        you for them. The Company may continue to use and make available any and all Content and the
                        Company will continue to have all of these rights even if your account is terminated.
                    </p>
                    <p>
    You acknowledge and agree that the Company does not promise to screen Content, but that it has
                        the right to do so. The Company has the right to remove any Content that violates this Agreement
    or that it finds objectionable. You accept liability associated with the use of any Content,
                        including but not limited to your reliance on the accuracy, completeness, or usefulness of such
                        Content. You may not reproduce, republish, further distribute or publicly exhibit any Content on
                        the Services that is not yours.
                    </p>
                    <p>
    The Company respects copyright law and expects Members to do the same. Illegal or unauthorized
                        copying, distribution, modification, public display, or public performance of copyrighted works
                        is an infringement of the copyright holders` rights. If you believe that your work has been
                        copied in a way that constitutes copyright infringement, or your intellectual property rights
                        have been otherwise violated, please notify the Company’s Agent for Notice of claims of
                        copyright or other intellectual property infringement ("Agent"), at:
                        <a href="mailto:support@thanetwork.org">support@thanetwork.org</a>
                    </p>
                    <p>
                        Please provide our Agent with the following Notice:
                    </p>
                    <ul>
                        <li>
                            1. Identify the copyrighted work or other intellectual property that you claim has been
                            infringed;
                        </li>
                        <li>
                            2. Identify the material on the Services that you claim is infringing, with enough detail so
                            that we may locate it on the website;
                        </li>
                        <li>
                            3. A statement by you that you have a good faith belief that the disputed use is not
                            authorized by the copyright owner, its agent, or the law;
                        </li>
                        <li>
                            4. A statement by you declaring under penalty of perjury that (a) the above information in
                            your Notice is accurate, and (b) that you are the owner of the copyright interest involved
                            or that you are authorized to act on behalf of that owner;
                        </li>
                        <li>
                            5. Your address, telephone number, and email address;
                        </li>
                        <li>
                            6. Provide the profile name of the person who shared the information including date of post.
                        </li>
                        <li>
                            7. Your physical or electronic signature.
                        </li>
                    </ul>
                    <p>
                        The Company’s Agent will forward this information to the alleged infringer. It is Company’s
                        policy to terminate the accounts of infringers.
                    </p>
                    <h2>
                        K) Disputes
                    </h2>
                    <p>
                        PLEASE READ THIS SECTION CAREFULLY AS IT LIMITS THE MANNER IN WHICH YOU CAN SEEK RELIEF. You and
                        the Company agree that, except as otherwise specifically provided in this Agreement, you and the
                        Company shall seek to resolve any and all disputes between the Company and you, respectively,
                        including, without limitation, all claims, counter-claims and cross-claims (collectively,
                        "Claims"), whether in law, equity or otherwise, solely through individual arbitration in
                        accordance with the policies and procedures set forth in this Agreement. Such disputes shall
                        include without limitation:
                    </p>
                    <ul>
                        <li>
                            • disputes arising out of and/or otherwise relating to Company’s Terms of Service, Content
                            and Conduct Policy, and/or Privacy Policy;
                        </li>
                        <li>
                            • disputes arising out of and/or otherwise relating to any Services and/or any content on
                            our Services;
                        </li>
                        <li>
                            • disputes arising out of and/or otherwise relating to any information, communications
                            and/or other material that you and/or any other person and/or entity provide to and/or
                            through the Services;
                        </li>
                        <li>
                            • disputes arising out of and/or otherwise relating to any information and/or other material
                            that is collected, stored and/or disseminated by, on behalf of and/or with the approval of
                            the Company;
                        </li>
                        <li>
                            • disputes arising out of and/or otherwise relating to any advertising and/or other
                            communications by the Company in connection with the Services;
                        </li>
                        <li>
                            • disputes that are the subject of purported class action litigation in which you are not a
                            member of a certified class;
                        </li>
                        <li>
                            • disputes that arose before you entered into this Agreement and/or any prior version of
                            this Agreement;
                        </li>
                        <li>
                            • disputes that may arise after you terminate your registration with the Services; and
                        </li>
                        <li>
                            • disputes arising out of or relating to any aspect of the relationship between us, whether
                            based in contract, tort, statute, fraud, misrepresentation or any other legal theory.
                        </li>
                    </ul>
                    <p>
                        Despite the language agreed to above and as an alternative to arbitration, either you and/or the
                        Company may bring an individual action against the other in court. Additionally, you and/or the
                        Company may bring any Claim against the other to the attention of a federal, state and/or local
                        government entity, which may elect to seek relief against the Company on your behalf, and/or
                        against you on the Company’s behalf.
                    </p>
                    <p>
                        You agree that you and the Company have voluntarily and intentionally waived any and all right
                        to a trial by jury, and (except as otherwise specifically provided in this Agreement) any and
                        all right to participate in a class action. The Federal Arbitration Act governs the
                        interpretation and enforcement of this Dispute provision. This Dispute provision shall survive
                        termination of this Agreement.
                    </p>
                    <h2>
                        L) Termination
                    </h2>
                    <p>
                        Either party may terminate use of the Services at any time. The Company may terminate for lack
                        of payment, and policy violations as stated in these terms and conditions of membership. The
                        Company shall not be liable to Member or any third party for termination membership.
                        Notifications will always be made to a member prior to termination of membership in an effort to
                        allow the member to take the necessary action to resolve infractions, but if any violations of
                        the law or harassment or safety to another member is presented, those infractions will be
                        handled immediately with termination of membership.
                    </p>
                    <h2>
                        M) DISCLAIMERS OF WARRANTIES
                    </h2>
                    <p>
                        MEMBER AGREES THAT USE OF THE COMPANY SERVICES IS ENTIRELY AT MEMBER`S OWN RISK. THE COMPANY
                        SERVICES ARE PROVIDED ON AN "AS IS" BASIS, WITHOUT ANY WARRANTIES OF ANY KIND. ALL EXPRESS AND
    IMPLIED WARRANTIES, INCLUDING, WITHOUT LIMITATION, THE WARRANTIES OF MERCHANTABILITY, FITNESS
                        FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT OF PROPRIETARY RIGHTS ARE EXPRESSLY DISCLAIMED TO
                        THE FULLEST EXTENT PERMITTED BY LAW. TO THE FULLEST EXTENT PERMITTED BY LAW, THE COMPANY
                        DISCLAIMS ANY WARRANTIES FOR THE SECURITY, RELIABILITY, TIMELINESS, ACCURACY, AND PERFORMANCE OF
                        THE COMPANY SERVICES. TO THE FULLEST EXTENT PERMITTED BY LAW, THE COMPANY DISCLAIMS ANY
                        WARRANTIES FOR OTHER SERVICES OR GOODS RECEIVED THROUGH OR ADVERTISED ON THE COMPANY SERVICES
                        ACCESSED THROUGH ANY LINKS ON THE SERVICES. TO THE FULLEST EXTENT PERMITTED BY LAW, THE COMPANY
                        DISCLAIMS ANY WARRANTIES FOR VIRUSES OR OTHER HARMFUL COMPONENTS IN CONNECTION WITH THE THE
                        COMPANY SERVICES. Some jurisdictions do not allow the disclaimer of implied warranties. In such
                        jurisdictions, the foregoing disclaimers may not apply to you insofar as they relate to implied
                        warranties.
                    </p>
                    <h2>
    N) LIMITATION OF LIABILITY
    </h2>
                    <p>
    UNDER NO CIRCUMSTANCES SHALL THE COMPANY BE LIABLE AS A RESULT OF MEMBER`S USE OR MISUSE OF THE
                        COMPANY SERVICES, WHETHER THE DAMAGES ARISE FROM USE OR MISUSE OF THE COMPANY SERVICES, FROM
                        INABILITY TO USE THE COMPANY SERVICES, OR THE INTERRUPTION, SUSPENSION, MODIFICATION,
                        ALTERATION, OR TERMINATION OF THE COMPANY SERVICES. SUCH LIMITATION SHALL ALSO APPLY WITH
                        RESPECT TO DAMAGES INCURRED BY REASON OF OTHER SERVICES OR PRODUCTS RECEIVED THROUGH OR
                        ADVERTISED IN CONNECTION WITH THE THE COMPANY SERVICES OR ANY LINKS ON THE COMPANY SERVICES, AS
                        WELL AS BY REASON OF ANY INFORMATION OR ADVICE RECEIVED THROUGH OR ADVERTISED IN CONNECTION WITH
                        THE THE COMPANY SERVICES OR ANY LINKS ON THE COMPANY SERVICES. THESE LIMITATIONS SHALL APPLY TO
                        THE FULLEST EXTENT PERMITTED BY LAW. In some jurisdiction, limitations of liability are not
                        permitted. In such jurisdictions, the foregoing limitation may not apply to you.
                    </p>
                    <h2>
                        O) Indemnification
                    </h2>
                    <p>
                        Member agrees to indemnify and hold the Company, its subsidiaries, affiliates, successors,
                        assigns, directors, officers, agents, employees, service providers, and suppliers harmless from
                        any dispute which may arise from a breach of terms of this Agreement or use of the Services.
                        Member agrees to hold the Company harmless from any claims and expenses, including reasonable
                        attorney`s fees and court costs, related to Member`s violation of this Agreement.
                    </p>
                    <h2>
                        P) Agreement
                    </h2>
                    <p>
                        The Agreement makes up the entire agreement between Member and the Company and governs your use
                        of the Services, superseding any prior agreements between Member and the Company. You also may
                        be subject to additional terms and conditions that may apply when you use affiliate services,
                        third-party content or third-party software. The failure of the Company to exercise or enforce
                        any right or provision of the Agreement shall not result in a waiver of such right or provision.
                        The section titles in the Agreement are for convenience only and have no legal or contractual
                        effect. In the event there is a discrepancy between this English language version and any
                        translated copies of the Agreement, the English version shall prevail. The side summaries within
                        this Agreement are for reference only. In the event that there is a discrepancy between the full
                        terms of this Agreement and the language contained within the side summaries the full version of
                        the Agreement shall prevail.
                    </p>
                    <h2>
                        Contacting Us
                    </h2>
                    <p>
                        Users with questions about this Agreement and any of the above statements may contact the
                        Company by email at
                        <a href="mailto:support@thanetwork.org">support@thanetwork.org</a>
                    </p>

                    <p>As a member of ThaNetwork each member`s payment method will incur a monthly membership fee of
                        $29.99 for the life of the member`s membership.  Monthly payments will be deducted from the
                        member`s payment method on the 1st day of each month.  A member may end their membership at any
                        time by canceling the membership from the member`s Profile Edit page.  If you understand and
                        agree with the terms, acknowledge this agreement by selecting the box below.     (I agree and
                        acknowledge to the recurring terms listed above)</p>

                    <h5 style="text-decoration: underline;">Requirements</h5>
                    <p>When a member joins ThaNetwork, a payment receipt will be sent to the member`s personal email
                        address each time a payment is deducted from the member`s payment method, and when payments are
                        made to the member.</p>
                    <p>Membership payments will be made deducted on the 1st of each month.  When new member`s join they
                        must be a member for 30days before the first automated payment is deducted from the member`s
                        payment method.</p>
                    <p>Membership payments will be credited to each member`s account on the 15th of each month.  New
    referral payment credit will not be paid until the new member has been a member for 30 days.</p>
                    <p>When a new member joins ThaNetwork, the referring member will not receive payment until the
                        payment has been processed.  Once the payment is processed the funds will be credited to the
                        member`s account and the member will receive an email and notification of receiving payment with
                        the new member`s name and username included in the message.</p>
                    <p>If a member cancels their membership they will not receive a refund, and if the member has
                        referred member`s in their personal network they will no longer receive payments for those
                        members.  The payments will go to the company.</p>'
            ]),
        ]);

        $privacy = Page::create([
            'name' => 'Privacy',
            'slug' => 'privacy',
            'content' => json_encode([
                'banner_image' => asset('images/banner.jpg'),
                'banner_circle_title' =>  'PRIVACY POLICY',
                'privacy_content' => '<h2>
                            PRIVACY POLICY
                        </h2>
                        <h4>
                            Updated as of 10/8/2022
                        </h4>
                        <p>
                            By joining ThaNetwork, you agree to this Privacy Policy and the applicable Terms of Service
                        </p>
                        <p>
                            When you create a profile on ThaNetwork, your profile information will be visible to other
                            members.
                        </p>
                        <p>
                            If you are under the age of 18, you may not join ThaNetwork. We do not knowingly collect,
                            maintain, or sell information from our members. We do not knowingly collect, maintain, or
                            sell personal information from individuals under the age of 18.
                        </p>
                        <p>
                            Parents: If your child is under the age of 18 and has joined any of the Services using a
                            false age, please notify us at
                            <a href="mailto:support@thanetwork.org">support@thanetwork.org</a> , and we will terminate your child`s account and
    delete all personal information displaying on the site. We will happily respond to all
                            communications from parents with privacy questions or concerns.
                        </p>
                        <h2>
    I. What This Privacy Policy Covers
    </h2>
                        <p>
    This Privacy Policy describes how our website collects, stores, uses, and share your
                            personal information. It also describes how you can control and protect your privacy on our
                            Services.
                        </p>
                        <p>
    This Privacy Policy does not apply to the practices of companies that we do not own or
    control, such as companies that advertise on our Services or companies with whom we partner
                            for certain services. For example, companies that advertise on our Services may tag your
                            device or computer with cookies. We do not control these third-party cookies and their
                            privacy policies may differ from ours.
                        </p>
                        <h2>
    II. Controlling and Protecting Your Privacy
    </h2>
                        <ul>
                            <li>
    1. <strong>Personal Safety.</strong> After getting to know someone online, you may
                                choose to meet him or her in-person. If so, be careful and use common sense. Never meet
                                someone in private for the first time; choose a public place, and take a friend. For
                                more information about staying safe, please visit The Meet Group Safety page.
                            </li>
                            <li>
    2. <strong>Account Settings.</strong> You can change your account settings at any time.
    You should review these settings regularly.
                            </li>
                            <li>
    3. <strong>Deleting Your Account.</strong> You can edit or delete information that you
                                upload to our Services and you can delete your account, but you cannot recall or delete
                                information that others have copied. When you delete your account, some or all of your
                                information may remain on our servers, but you will not be able to access it.
                            </li>
                            <li>
    4. <strong>Deleting Your Information and Content.</strong> You can request that we
                                delete your posted information and content. To do so, send a system message to admin
                                from your profile requesting deletion.
                            </li>
                            <li>
    5. <strong>Password.</strong> Store your password in a safe place, and do not share it
                                with anyone. If you think someone has your password, change it immediately.
                            </li>
                            <li>
    6. <strong>Posting Information.</strong> Posting personal information on public areas of
                                our Services will make it publicly available. The personal information you post or share
                                with others may in turn be shared by them with still other users and it may also show up
                                when someone uses a public search engine (even if that person is not a user of our
                                Services). <strong>Do not post personal information in public areas of our Services that
                                you
                                want to keep private.</strong>
                            </li>
                            <li>
    7. <strong>Third Parties.</strong> We are not responsible for (and don’t have any
                                control over) the privacy policies of third party websites, apps and ad servers. We
                                encourage you to read the privacy policies of each and every website and app you visit.
                            </li>
                            <li>
    8. <strong>Opting Out of Receiving Cookies.</strong> You may set your browser to block
                                some or all cookies. However, our Services might not fully function if you disable
                                cookies. If you use multiple devices, browsers or computers, you will need to opt out of
                                receiving cookies on each one. Also, if you change devices or computers you will need to
                                repeat this opt-out process
    </li>
                        </ul>
                        <h2>
    III. Information We Collect
    </h2>
                        <p>
    We collect different types of personal information about you and your activities. We collect
                            personal information when you register, log into, and use our Services, including the
                            features you use, the pages and screens you visit, and the information you enter, such as
                            chats or demographic information that you share in your profile on our Services. We do not
                            collect personal information from you on the website unless you are logged onto our service.
    The specific examples in the following bullets are not meant to be exhaustive.
                        </p>
                        <h4>
    Categories of Information We Collect When You Use Our Website and App:
                        </h4>
                        <ul>
                            <li>
    • <strong>Email Address And Phone Number.</strong> We require a valid email address or
    phone number to join the site. This email will be stored in our servers. We will use
        this email address to contact you. We may use your phone number to verify your account,
                                but generally will not otherwise contact you by phone.
                            </li>
                            <li>
    • <strong>Profile Information You Provide.</strong> We collect the personal information
                                that you provide to create your profile on our Site.
                            </li>
                            <li>
    • <strong>Activity and Usage Information Including Your Content.</strong> We collect
                                information about the features you use, the pages and screens you visit, and your
                                transactions.
                            </li>
                        </ul>
                        <h2>
    IV. How We Use and Disclose the Information We Collect
    </h2>
                        <p>
    We process personal information: (i) to execute transactions that you request, (ii) when you
                            provide your explicit consent, (iii) for our legitimate business interests such as
                            maintaining our books and records, securing and protecting the integrity of our Services,
                            and for Service development; and (iv) to meet applicable legal requirements.
                        </p>
                        <p>
    Specifically, we use and disclose your information in the following ways:
                        </p>
                        <ul>
                            <li>
    • <strong>To customize your experience</strong> on our Services, including suggestions
                                about features you
                                may enjoy or people you may want to meet.
                            </li>
                            <li>
    • <strong>To provide customer services</strong> in response to questions or concerns you
                                may bring to our
                                attention.
                            </li>
                            <li>
    • <strong>Bug fixes and product improvement.</strong> To find and fix technology
                                problems. We send data
                                to companies we do not own in order to analyze bugs in our websites and apps so that we
                                can keep them running smoothly.
                            </li>
                            <li>
    • <strong>Policy enforcement.</strong> To enforce this Privacy Policy and the applicable
                                Terms of
                                Service. We send data to companies we do not own for the following reasons:
        <ul>
                                    <li>
    o to verify accounts and activity;
                                    </li>
                                    <li>
    o combat harmful conduct, such as abusive behavior and other violations of our
                                        Terms of Service;
                                    </li>
                                    <li>
    o detect and prevent spam;
                                    </li>
                                    <li>
    o detect and prevent fraud;
                                    </li>
                                    <li>
    o maintain the integrity of our Services; and
                                    </li>
                                    <li>
    o promote safety and security on our Services, such as monitoring for illegal
                                                                          activity and reporting to the appropriate authorities.
                                    </li>
                                    <li>
    o <strong>Advertising:</strong> We use cookies and your AdID and IDFA to deliver
                                        relevant advertising. Our ad partners use cookies to deliver relevant ads and
    monitor how you interacted with an ad.
                                    </li>
                                </ul>
                            </li>
                            <li>
    • <strong>Cooperation with law enforcement.</strong> We cooperate with government and
    law enforcement
                                officials to enforce and comply with the law. We report threats of violence or self-harm
    and other illegal activities proactively, and we may disclose information about you to
                                government or law enforcement officials in order to: (1) protect the safety and security
                                of our users and members of the public or (2) satisfy subpoenas, court orders, or other
                                governmental requests.
                            </li>
                            <li>
    • <strong>Business transfers.</strong> We share your personal information with Third
                                Party E-Commerce
                                company for payments and transfer of funds.
                            </li>
                            <li>
    • <strong>Management of our company.</strong> We will process your information as needed
                                to maintain our
                                financial books and records, engage in sales of goods and Services to members and
    advertisers, ensure the integrity and security of our systems and resources, operate our
                                work environment, and respond to any potential compromise of anyone’s personal
                                information.
                            </li>
                            <li>
    • <strong>Service providers.</strong> We transfer information to vendors, service
                                providers, and other
                                partners who support our business, such as providing technical infrastructure services,
                                analyzing how our services are used, measuring the effectiveness of ads and services,
                                providing customer service, facilitating payments, or conducting research and surveys.
    These partners agree to adhere to confidentiality obligations consistent with this
                                Privacy Policy and the agreements we enter into with them.
                            </li>
                            <li>
    • <strong>Personal Information.</strong> Except for “Cooperation with Law Enforcement,”
                                “Business
                                Transfers” and “Service Providers” (all described above), in connection with account
                                verification (and then only for that purpose), or to enforce our rights under this
                                Privacy Policy and our Terms of Service, we do not share the following personal
                                information with any third party not owned by The Meet Group, Inc. for any reason: your
                                exact date of birth, your first name, your last name, your address, your phone
                                number(s), or your email address.
                            </li>
                        </ul>
                        <h2>
    V. Data Security and Storage
    </h2>
                        <p>
    We use industry standard security measures to prevent the loss, misuse and alteration of the
                            information under our control. However, we cannot guarantee that our security measures will
                            prevent “hackers” from illegally obtaining this information. We will store and maintain your
                            personal information for as long as necessary (i) for the purposes for which it was
    collected, (ii) to meet our current and future legal obligations, including compliance with
                            our records retention practices, and (iii) as permitted to meet our legitimate interests.
    Our Services are hosted in the United States and we maintain your information in the United
                            States and elsewhere on the cloud. If you are outside the United States, you agree to have
                            your data transferred to and processed in the United States and elsewhere. When we transfer
                            personal data outside of the European Economic Area, we ensure an adequate level of
                            protection for the rights of data subjects based on the adequacy of the receiving country’s
                            data protection laws and contractual obligations placed on the recipient of the data. A copy
                            of these safeguards may be made available by writing to us at the address provided in the
                            Contact Information section below.
                        </p>
                        <h2>
    VI. Deletion of Your Personal Information
    </h2>
                        <p>
    You may delete your account at any time, and your personal information will be deleted in
                            the normal course of business pursuant to our current data retention practices.
                        </p>
                        <h2>
    VII. California Resident Rights
    </h2>
                        <ul>
                            <li>
    • <strong>Notice of Right to Know About Information Collected.</strong> California
                                residents have the right to request certain information about whether we collect, use,
        disclose and sell personal information about them, and to request to know the personal
                                information that we have. To make such a request, please contact us at
    <a href="mailto:support@thanetwork.org">support@thanetwork.org</a> Please be advised that we will verify all such
                                requests prior to
                                providing any personal information by requiring you to respond to an email sent to the
                                email you used when making your request. Please be advised that we are only required to
                                respond to two requests per user each year. These reports will be provided free of
                                charge.
                            </li>
                            <li>
    • <strong>Notice of Right to Request Deletion of of Personal Information.</strong> If
    you are a California resident and a registered user of the Service, you may request the
                                deletion of personal information we have collected from you. To make such a request,
                                please contact us at <a href="mailto:support@thanetwork.org">support@thanetwork.org</a> Please be advised that we
                                will verify all
                                such requests prior to providing any personal information by requiring you to respond to
                                an email sent to the email you used when making your request.
                            </li>
                            <li>
    • <strong>Notice of Right to Opt Out of the “Sale” of Personal Information.</strong>
    California residents have the right to request that we not sell their personal
                                information to third parties, as those terms are defined by California Civil Code
                                Section 1798.140. ThaNetwork does not sell personal information to third parties.
                            </li>
                            <li>
    • <strong>Nondiscrimination.</strong> We will not discriminate against California
                                residents who exercise their privacy rights.
                            </li>
                            <li>
    • <strong>Authorized Agent.</strong> California residents may use an authorized agent to
                                make requests on their behalf. We require the authorized agent to provide us with proof
                                of the California resident’s written permission (for example, a power of attorney) that
                                shows the authorized agent has the authority to submit a request for the California
                                                                                                     resident. In addition to providing this proof, the authorized agent must follow the
                                appropriate process described above to make a request.
                            </li>
                            <li>
    • <strong>California Shine the Light Law.</strong> Under California’s “Shine the Light”
                                law, California residents who provide Personal Information in obtaining products or
    services for personal, family, or household use are entitled to request and obtain from
                                us once a calendar year information about the customer information we shared, if any,
                                with other businesses for their own direct marketing uses. ThaNetwork does not sell
                                personal information to third parties.
                            </li>
                            <li>
    • <strong>Our California Do Not Track Notice.</strong> We do not currently respond or
    take any action with respect to web browser “do not track” signals or other mechanisms
                                that provide users the ability to exercise choice regarding the collection of personal
                                information about that user’s online activities over time and across third-party web
                                sites or online services. We may allow third parties, such as companies that provide us
                                with analytics tools, to collect personal information about your online activities over
                                time and across different apps or web sites when you use our Service.
                            </li>
                        </ul>
                        <h2>
    VIII. Nevada Privacy Rights
    </h2>
                        <p>
    We do not sell consumers’ covered information for monetary consideration (as defined in
                            Chapter 603A of the Nevada Revised Statutes). However, if you are a Nevada resident, you
                            have the right to submit a request directing us not to sell your personal information.
                        </p>
                        <h2>
    IX. European Economic Area Residents
    </h2>
                        <p>
                            If you are a resident of the European Economic Area, you have the following data protection
                            rights:
                        </p>
                        <ul>
                            <li>
    • If you wish to access, correct, update or request deletion, restrict processing,
                                object to processing, or request porting of your personal information, you can do so at
                                any time by contacting us at <a href="mailto:support@thanetwork.org">support@thanetwork.org</a> . Please see sections VI and VII
                                above for more information.
                            </li>
                            <li>
    • You have the right to opt-out of marketing communications we send you at any time. You
                                can exercise this right by clicking on the "unsubscribe" or "opt-out" link in the
                                marketing emails we send you. You can manage your account settings and email marketing
                                preferences in the Settings section.
                            </li>
                            <li>
    • Similarly, if we have collected and processed your personal information with your
                                consent (such as for advertising), then you can withdraw your consent at any time in the
                                Settings section. Withdrawing your consent will not affect the lawfulness of any
                                processing we conducted prior to your withdrawal, nor will it affect processing of your
                                personal information conducted in reliance on lawful processing grounds other than
                                consent. Please note that if you opt-out of having your data shared with advertisers,
                                you will still see ads, they just will not be tailored to your interests.
                            </li>
                            <li>
    • You have the right to complain to a data protection authority about our collection and
                                use of your personal information. For more information, please contact your local data
                                protection authority.
                            </li>
                        </ul>
                        <p>
    We respond to all requests we receive from individuals wishing to exercise their data
                            protection rights in accordance with applicable data protection laws. Notwithstanding the
                            foregoing, we reserve the right to keep any information in our archives that we deem
                            necessary to comply with our legal obligations, resolve disputes and enforce our agreements.
                        </p>
                        <h2>
    X. Language
                        </h2>
                        <p>
    This Privacy Policy was written in English. If you are reading a translation and it
                            conflicts with the English version, please note that the English version controls.
                        </p>
                        <h2>
    Our Contact Information
    </h2>
                        <p>
                            For all requests concerning the security of your data, please contact us at <a href="mailto:support@thanetwork.org">support@thanetwork.org</a>
                        </p>'
            ]),
        ]);

        $contact = Page::create([
            'name' => 'Contact',
            'slug' => 'contact',
            'content' => json_encode([
                'banner_image' => asset('images/banner.jpg'),
                'banner_circle_title' =>  'ABOUT US',
                'banner_circle_title_2' =>  'MEMBERSHIP PAYS',
                'banner_circle_text' =>  'The Social Media Network that allows its members to earn cash',
            ]),
        ]);
    }
}
