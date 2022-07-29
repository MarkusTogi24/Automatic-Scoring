<form action="{{url('classroom/'.$classroom_id.'/exam-create')}}" method="post">
@csrf
<input type="text" name="name">
<input type="text" name="description">
<input type="datetime-local" name="start_time">
<input type="datetime-local" name="end_time">
<input type="text" name="is_open">
<button>submit</button>
</form>