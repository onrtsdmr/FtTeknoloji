@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md">
        <h1 class="text-3xl font-semibold text-blue-600 dark:text-green-400 mb-8">News</h1>

        <form action="" method="GET" class="space-y-6 mb-8">
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-4">
                <div class="w-full">
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Başlangıç Tarihi</label>
                    <input type="date" name="start_date" id="start_date" class="w-full p-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-green-500">
                </div>
                <div class="w-full">
                    <label for="end_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Bitiş Tarihi</label>
                    <input type="date" name="end_date" id="end_date" class="w-full p-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-green-500">
                </div>
            </div>
            <div class="w-full mb-4">
                <label for="currency" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Para Birimleri</label>
                <select name="currency" id="currency" class="w-full p-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-green-500">
                    <option value="">Tüm Para Birimleri</option>
                    @foreach($uniqueCurrencies as $currency)
                        <option value="{{ $currency->currency_code }}">{{ $currency->currency_title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="text-right">
                <button type="submit" class="bg-blue-500 text-white dark:bg-green-600 dark:hover:bg-green-700 px-6 py-2 rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-green-500 focus:ring-opacity-50">
                    Filtrele
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-900 shadow-md rounded-lg overflow-hidden">
                <thead>
                <tr class="bg-blue-600 dark:bg-gray-700 text-white text-left text-sm uppercase font-semibold">
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3 px-4">Başlık</th>
                    <th class="py-3 px-4">Yayınlanma Tarihi</th>
                    <th class="py-3 px-4">Para Birimleri</th>
                </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300 text-sm divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($filteredData as $news)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="py-4 px-4">{{ $news->id }}</td>
                        <td class="py-4 px-4">{{ $news->title }}</td>
                        <td class="py-4 px-4">{{ $news->published_at }}</td>
                        <td class="py-4 px-4 space-y-1">
                            @foreach($news->currencies as $currency)
                                @if($currency->currency_code)
                                    <p class="text-blue-500 dark:text-green-400">{{ $currency->currency_title }}</p>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">No Currency</span>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $filteredData->links() }}
        </div>
    </div>
@endsection
