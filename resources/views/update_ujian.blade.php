<form action="{{url('classroom/'.$classroom_id.'exam-update/' . $exam->id)}}" method="post">
@csrf
<input type="hidden" name="_method" value="put">
<input type="text" name="name" value="{{$exam->name}}">
<input type="text" name="description" value="{{$exam->description}}">
<input type="datetime-local" name="start_time" value="{{$exam->start_time}}">
<input type="datetime-local" name="end_time" value="{{$exam->end_time}}">
<input type="text" name="is_open" value="{{$exam->is_open}}">
<button>submit</button>
</form>
