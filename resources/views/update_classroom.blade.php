<form action="{{ url('classroom/'.$classroom->id)}}" method="post">
    @csrf
    <input type="hidden" name="_method" value="put">
    <input type="text" name="name" value="{{$classroom->name}}">
    <input type="text" name="description" value="{{$classroom->description}}">
    <button>submit</button>
    </form>