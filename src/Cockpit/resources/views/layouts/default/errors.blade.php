@if($errors->has())
    <div id="errors-container">
        <div class="alert alert-warning">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
@endif

@if(session('message'))
    <div id="errors-container">
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div id="errors-container">
        <div class="alert alert-warning">
            {{ session('error') }}
        </div>
    </div>
@endif