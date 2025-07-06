@extends('layouts.front')

@section('title')
    Term & Condition
@endsection

@section('content')
    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:py-16">
            <div class="mr-auto place-self-center lg:col-span-7">
                <h1
                    class="text-4xl font-extrabold leading-none tracking-tight md:text-5xl xl:text-6xl dark:text-white mb-4 mt-5">
                    Terms and Conditions</h1>
                <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">These
                    terms and conditions outline the rules and regulations for the use of {{ env('APP_NAME') }}'s Website.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">1. Introduction</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">By accessing this website we assume you accept
                    these terms and conditions. Do not continue to use {{ env('APP_NAME') }} if you do not agree to take all
                    of the terms and conditions stated on this page.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">2. Cookies</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">We employ the use of cookies. By accessing {{ env('APP_NAME') }}, you agreed to use cookies in agreement with the {{ env('APP_NAME') }}'s Privacy Policy.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">3. License</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">Unless otherwise stated, {{ env('APP_NAME') }}
                    and/or its licensors own the intellectual property rights for all material on {{ env('APP_NAME') }}. All
                    intellectual property rights are reserved. You may access this from {{ env('APP_NAME') }} for your own
                    personal use subjected to restrictions set in these terms and conditions.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">4. User Comments</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">Parts of this website offer an opportunity for
                    users to post and exchange opinions and information in certain areas of the website. {{ env('APP_NAME') }}
                    does not filter, edit, publish or review Comments prior to their presence on the website. Comments do
                    not reflect the views and opinions of {{ env('APP_NAME') }},its agents and/or affiliates. Comments reflect
                    the views and opinions of the person who post their views and opinions.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">5. Hyperlinking to our Content</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">The following organizations may link to our
                    Website without prior written approval: Government agencies, Search engines, News organizations, Online
                    directory distributors may link to our Website in the same manner as they hyperlink to the Websites of
                    other listed businesses; and System wide Accredited Businesses except soliciting non-profit
                    organizations, charity shopping malls, and charity fundraising groups which may not hyperlink to our Web
                    site.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">6. iFrames</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">Without prior approval and written permission,
                    you may not create frames around our Webpages that alter in any way the visual presentation or
                    appearance of our Website.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">7. Content Liability</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">We shall not be hold responsible for any content
                    that appears on your Website. You agree to protect and defend us against all claims that is rising on
                    your Website. No link(s) should appear on any Website that may be interpreted as libelous, obscene or
                    criminal, or which infringes, otherwise violates, or advocates the infringement or other violation of,
                    any third party rights.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">8. Your Privacy</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">Please read Privacy Policy</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">9. Reservation of Rights</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">We reserve the right to request that you remove
                    all links or any particular link to our Website. You approve to immediately remove all links to our
                    Website upon request. We also reserve the right to amen these terms and conditions and it’s linking
                    policy at any time. By continuously linking to our Website, you agree to be bound to and follow these
                    linking terms and conditions.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">10. Removal of links from our website</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">If you find any link on our Website that is
                    offensive for any reason, you are free to contact and inform us any moment. We will consider requests to
                    remove links but we are not obligated to or so or to respond to you directly.</p>

                <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">If you
                    have any questions about our Terms and Conditions, please contact us at <a
                        href="mailto:test@yopmail.com" class="hover:underline">test@yopmail.com</a>.</p>
            </div>
        </div>
    </section>
@endsection
