<div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li class="error">{{ $error }}</li>
      @endforeach
    </ul>
</div>
