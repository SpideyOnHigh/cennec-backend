@extends('layouts.front')

@section('title')
    Privacy Policy
@endsection

@section('content')
    <section class="bg-white dark:bg-gray-900 ">
        <div class="max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:py-16">
            <div class="mr-auto place-self-center lg:col-span-7">
                <h1
                    class="text-4xl font-extrabold leading-none tracking-tight md:text-5xl xl:text-6xl dark:text-white mb-4 mt-5">
                    Privacy Policy</h1>
                <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">Your
                    privacy is important to us. It is {{ env('APP_NAME') }}'s policy to respect your privacy regarding any
                    information we may collect from you across our website, <a href="{{ url('/') }}"
                        class="hover:underline">Cennec</a>, and other sites we own and operate.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">1. Information We Collect</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">We only ask for personal information when we
                    truly need it to provide a service to you. We collect it by fair and lawful means, with your knowledge
                    and consent. We also let you know why we’re collecting it and how it will be used.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">2. How We Use Information</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">We use collected information to provide,
                    operate, and maintain our website, to improve, personalize, and expand our website, and to understand
                    and analyze how you use our website.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">3. Sharing Your Information</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">We do not share any personally identifying
                    information publicly or with third parties, except when required to by law.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">4. Security of Your Information</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">We take reasonable steps to protect the personal
                    information you share with us from unauthorized access, use, or disclosure.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">5. Your Privacy Rights</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">You have the right to access, correct, or delete
                    your personal information. You also have the right to object to or restrict certain processing of your
                    data.</p>

                <h2 class="text-2xl font-bold leading-tight mb-4 dark:text-white">6. Changes to This Policy</h2>
                <p class="mb-6 font-light text-gray-500 dark:text-gray-400">We may update our Privacy Policy from time to
                    time. We will notify you of any changes by posting the new Privacy Policy on this page.</p>

                <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">If you
                    have any questions about our Privacy Policy, please contact us at <a href="mailto:test@yopmail.com"
                        class="hover:underline">test@yopmail.com</a>.</p>
            </div>
        </div>
    </section>
@endsection
