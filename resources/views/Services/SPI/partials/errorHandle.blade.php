@if ($errors->any())
    <div class="alert alert-danger mb-3" role="alert">
        <h6 class="alert-heading">Oops! Terjadi beberapa masalah:</h6>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
