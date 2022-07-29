<h1>halo</h1>

<form action="{{ url('classroom') }}" method="POST">
    @csrf

    <input type="text" name="name">
    <input type="text" name="description">

    <button type="submit">submit</button>
</form>