<div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-0 sm:pt-0">

    

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
            <img src="assets/logo_text.jpg" height="70" dalt="logo!">
        </div>
        @if (Route::has('login'))
            <div class="flex justify-center text-lg pt-8 sm:justify-start sm:pt-8">
                @auth
                    <Link href="{{ url('/dashboard') }}" class="text-gray-700 dark:text-gray-500">{{ __('Dashboard') }}</Link>
                @else
                    <Link href="{{ route('login') }}" class="text-gray-700 dark:text-gray-500">Log in</Link>

                    @if (Route::has('register'))
                        <Link href="{{ route('register') }}" class="ml-4 text-gray-700 dark:text-gray-500">{{ __('Register') }}</Link>
                    @endif
                @endauth
            </div>
            <br>
        @endif
        <div class="mt-12 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-1">
                <div class="p-6 ">
                    <div class="flex items-center">
                        <div class="ml-4 text-lg leading-7 font-semibold">devTRAC</div>
                    </div>

                    <div class="ml-12 pt-4">
                        <div class="mt-12 text-gray-600 dark:text-gray-400 text-lg">
                            {{ __("devTRAC is an issue tracking tool for a small software development projects. It helps developers to view theirs tasks and interact with a Project Manager.") }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <div class="ml-4 pt-5 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                v 0.9
            </div>

    </div>
</div>