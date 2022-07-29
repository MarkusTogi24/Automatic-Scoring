{{$exams}}

<form action="{{url('classroom/'.$classroom_id.'/change-status')}}" method="post">
@csrf
<select name="is_open" id="">
    <option value="0">0</option>
    <option value="1">1</option>
</select>

<button type="submit">submit</button>
</form>