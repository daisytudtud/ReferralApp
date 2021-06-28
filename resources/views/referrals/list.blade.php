<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Referrals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @auth

                        @if( count($referrals) )
                            <table class="table-auto w-full border border-collapse border-black mb-10">
                              <thead>
                                <tr>
                                  <th class="border border-gray-300 px-3 py-1">Referrer</th>
                                  <th class="border border-gray-300 px-3 py-1">Email Referred</th>
                                  <th class="border border-gray-300 px-3 py-1">Date</th>
                                  <th class="border border-gray-300 px-3 py-1">Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach( $referrals as $referral )
                                    <tr>
                                        <td class="border border-gray-300 px-3 py-1">{{ $referral->user->name }}</td>
                                        <td class="border border-gray-300 px-3 py-1">{{ $referral->email }}</td>
                                        <td class="border border-gray-300 px-3 py-1">{{ $referral->created_at->toFormattedDateString() }}</td>
                                        <td class="border border-gray-300 px-3 py-1">
                                            <span class="{{ isset($referral->user_id) ? 'text-green-600': 
                                                'text-red-600' }}">{{ isset($referral->user_id) ? 'Successful' : 'Waiting' }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                              </tbody>
                            </table>
                            {{ $referrals->links(); }}
                        @endif
                           
                    @endauth
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
