<!DOCTYPE html>
<html>
<head>
    <title>LiveCharts Preview</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">LiveCharts Gallery Preview</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($charts as $name => $chart)
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h2 class="text-xl font-semibold mb-4 capitalize">{{ $name }}</h2>
                    <livewire:livecharts :chart="$chart" :wire:key="'preview-'.$name" />
                </div>
            @endforeach
        </div>
    </div>
    @liveChartsScripts
</body>
</html>
