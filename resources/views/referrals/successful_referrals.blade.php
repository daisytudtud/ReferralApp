<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Successful Referrals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @auth
                        <h2 class="mb-5">{{ count($successful_referrals) }} successful {{ Str::plural('referral', count($successful_referrals)) }}</h2>
                        @if( count($successful_referrals) )
                            <ul class="list-disc ml-5">
                                @foreach( $successful_referrals as $ref )
                                    <li>{{ $ref->email }}</li>
                                @endforeach
                            </ul>
                        @endif
                    @endauth

                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>