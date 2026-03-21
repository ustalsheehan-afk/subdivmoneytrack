@if(isset($user))
    <h2>Welcome, {{ $user->name }}</h2>
@else
    <p>Please log in first.</p>
@endif
