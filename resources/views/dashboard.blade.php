<link href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css" rel="stylesheet">


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        new DataTable('#example', {
            searching: false,
            lengthChange: false,
            paging: true,
            ordering: false,
            pageLength: 5 // 👈 Set this to show 5 results per page

        });
    });
</script>




<x-app-layout>
    <x-slot name="header">
    </x-slot>
    {{-- <div class="flex justify-end mt-6 items-right gap-4">
        <form method="POST" action="#">
            @csrf
            <input type="hidden" name="hotels" value="{{ base64_encode(json_encode($results)) }}">
            <input type="hidden" name="query" value="{{ $query }}">
            <button type="submit"
                class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                </svg>
                Export to Excel
            </button>
        </form>
    </div> --}}
    <div class="flex justify-end mt-6">
        <div class="flex flex-col gap-2 mr-[40px]">
            <!-- Export Button Form -->
            <form method="POST" action="{{ route('hotels.export') }}">
                @csrf
                <input type="hidden" name="hotels" value="{{ base64_encode(json_encode($results)) }}">
                <input type="hidden" name="query" value="{{ $query }}">
                <button type="submit"
                    class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium leading-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                    </svg>
                    Export to Excel
                </button>
            </form>

            <!-- Generate Chart Link (styled as button) -->
            <a href="{{ url('/motels-chart') }}"
                class="flex items-center gap-2 bg-yellow-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium leading-5">
                Generate Chart
            </a>
        </div>
    </div>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Search form --}}
                    <form action="{{ url('/dashboard') }}" method="GET"
                        class="w-full max-w-7xl flex flex-wrap sm:flex-nowrap items-center gap-2 border border-gray-300 rounded-lg p-4 mb-4">

                        <!-- Search input -->
                        <input type="text" name="motel" placeholder="Motel" value="{{ request('motel') }}"
                            class="flex-grow px-4 py-2 border border-gray-300 rounded focus:outline-none">
                        <input step="any" type="number" name="rating" placeholder="Rating"
                            value="{{ request('rating') }}"
                            class="flex-grow px-4 py-2 border border-gray-300 rounded focus:outline-none">

                        <input type="text" name="price" placeholder="Price per night" value="{{ request('price') }}"
                            class="flex-grow px-4 py-2 border border-gray-300 rounded focus:outline-none">
                        <input type="text" name="score" placeholder="Score" value="{{ request('score') }}"
                            class="flex-grow px-4 py-2 border border-gray-300 rounded focus:outline-none">
                        <input type="text" name="rank" placeholder="Rank" value="{{ request('rank') }}"
                            class="border border-gray-300 rounded focus:outline-none" style="width: 68px">

                        <!-- Per page dropdown -->
                        {{-- <select name="per_page"
                            class="min-w-[100px] px-4 py-2 border border-gray-300 rounded-md focus:outline-none">
                            <option value="5" {{ request('per_page')==5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('per_page')==10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page')==15 ? 'selected' : '' }}>15</option>
                            <option value="20" {{ request('per_page')==20 ? 'selected' : '' }}>20</option>
                        </select> --}}

                        <!-- Submit -->
                        <button type="submit"
                            class=" min-w-[100px] px-4 py-2 border border-gray-300 rounded-md focus:outline-none">
                            Search
                        </button>
                        <a href="{{ route('dashboard') }}"
                            class="min-w-[100px] px-4 py-2 border border-gray-300 rounded-md focus:outline-none">
                            Clear
                        </a>
                    </form>


                    {{-- showing results here --}}
                    @if(!empty($query))
                    <h2 class="text-lg font-bold mt-6 mb-4">Results for "{{ $query }}"</h2>
                    @if(count($results))
                    <div class="w-full overflow-x-auto">
                        <table id="example" class="min-w-full bg-white border border-gray-300 rounded shadow">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-left px-4 py-2">Name</th>
                                    <th class="text-left px-4 py-2">Address</th>
                                    <th class="text-left px-4 py-2">Rating</th>
                                    {{-- <th class="text-left px-4 py-2">Operating Status</th> --}}
                                    <th class="text-left px-4 py-2">Total Ratings</th>
                                    <th class="text-left px-4 py-2">Estimated Price</th>
                                    <th class="text-left px-4 py-2">Score</th>
                                    <th class="text-left px-4 py-2">Rank</th>
                                    {{-- <th class="text-left px-4 py-2">Distance from Airport in KM</th> --}}
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($results as $motel)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-4 py-2 font-semibold">
                                        @if ($motel['weblink'] !== 'Not available')
                                        <a href="{{ $motel['weblink'] }}" target="_blank" rel="noopener noreferrer"
                                            class="text-blue-600 hover:underline">
                                            {{ $motel['name'] }}
                                        </a>
                                        @else
                                        <a href="https://www.google.com/search?q={{ urlencode($motel['name'] . ' motel in ' . $query) }}"
                                            target="_blank" rel="noopener noreferrer"
                                            class="text-blue-600 hover:underline">
                                            {{$motel['name']}}
                                        </a>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-gray-600">{{ $motel['address'] }}</td>
                                    <td class="px-4 py-2 text-yellow-500"><span>⭐ {{ $motel->ranking['rating'] ?? ''
                                            }}</span>
                                    </td>
                                    {{-- <td class="px-4 py-2 text-gray-600">{{ $motel['status'] }}</td> --}}
                                    <td class="px-4 py-2 text-gray-600">{{ $motel->ranking['user_total_rating'] }}
                                    </td>
                                    <td class="px-4 py-2  font-medium">
                                        <span class="text-green-600">$</span> {{ $motel['price'] }}
                                    </td>
                                    {{-- <td class="px-4 py-2 text-blue-600">
                                        {{ $motel['distance_from_airport'] }}
                                    </td> --}}
                                    <td class="px-4 py-2 text-gray-600">{{ $motel->ranking['score'] }}</td>
                                    <td class="px-4 py-2 text-gray-600">{{ $motel->ranking['rank'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-gray-500">No motels found for this location.</p>
                    @endif


                    @endif
                    {{-- end of showing results --}}
                </div>
            </div>

        </div>
</x-app-layout>