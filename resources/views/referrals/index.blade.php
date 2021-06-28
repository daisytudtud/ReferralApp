<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Referrals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @auth

                        @if( $max_referrals_reached )
                            <h3 class="text-center mb-5 text-red-500">You have reached the maximum number of successful referrals.</h3>
                            <h1 class="text-center mb-10">{{ __('Do you still want to invite friends?') }}</h1>
                        @else
                            <h1 class="text-center mb-10">{{ __('Invite your friends to register now!') }}</h1>
                        @endif

                        @if ( isset($valid_emails) && count($valid_emails) )
                            <h3 class="text-green-600 mb-5">{{ count($valid_emails) }} {{ Str::plural('email', count($valid_emails)) }} successfully added.</h3>
                        @endif
                        @if ( isset($errors) && count($errors) )
                            <div class="p-6 mb-5 bg-red-100 border-b border-red-200">
                                <h3>Errors:</h3>
                                <ul class="list-disc ml-5">
                                    @foreach ($errors as $err)
                                        <li>{{ __($err) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('referrals') }}" method="post" class="mb-4">
                            @csrf
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="invite_emails" type="text" id="invite-emails" placeholder="Email">
                             <div class="btn-wrapper text-center mt-10">
                                <button type="submit" class="bg-green-400 hover:bg-green-600 text-white font-bold py-2 px-10 rounded">Invite</button>
                            </div>

                        </form>
                    @endauth

                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
