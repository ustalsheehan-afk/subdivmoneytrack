@foreach($announcements as $announcement)
    @include('admin.announcements.partials.card', [
        'announcement' => $announcement,
        'totalResidents' => $totalResidents ?? 0
    ])
@endforeach
