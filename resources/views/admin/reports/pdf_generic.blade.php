<!DOCTYPE html>
<html>
<head>
    <title>Report - {{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header p { margin: 5px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .summary { margin-top: 20px; border-top: 2px solid #333; padding-top: 10px; }
        .summary-item { font-size: 14px; font-weight: bold; margin-bottom: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Period: {{ $period }}</p>
    </div>

    @if(!empty($summary))
        <div class="summary">
            <h3>Summary</h3>
            @foreach($summary as $key => $value)
                <div class="summary-item">{{ $key }}: {{ $value }}</div>
            @endforeach
        </div>
    @endif

    <h3>Details</h3>
    <table>
        <thead>
            <tr>
                @foreach($columns as $col)
                    <th>{{ $col }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($results as $row)
                <tr>
                    {{-- Logic to map row data to columns (similar to CSV) --}}
                    {{-- Since we passed objects, we need to know how to map them to columns in the generic view. --}}
                    {{-- However, in Blade we can't easily switch like in PHP. --}}
                    {{-- A better approach for the PDF would be to transform the data in the Controller into a simple array of arrays before passing it to the view, or duplicate the logic. --}}
                    {{-- For now, let's try to infer properties or use the same logic as CSV but inside the view? No, that's messy. --}}
                    {{-- I will rely on the fact that I should have transformed the data in the controller. --}}
                    {{-- Let's update the controller to pass a 'rows' array instead of raw objects for PDF/CSV consistency. --}}
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
