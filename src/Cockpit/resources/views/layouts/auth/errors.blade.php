@if($errors->has())
    <div class="alert alert-warning">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif