@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
</div>
@endif

@if (session('errors')&& is_array(session('errors')))
@foreach (session('errors') as $error)
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ $error }}
</div>
@endforeach
@endif