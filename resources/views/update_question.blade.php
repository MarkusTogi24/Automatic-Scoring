<h1>Hallo</h1>

<form action="{{url('classroom/'.$classroom_id.'/exam-edit-question/'.$question_id)}}" method="post">
@csrf
<input type="hidden" name="_method" value="put">
<input type="text" name="question" value="{{$question->question}}">
<input type="text" name="answer_key" value="{{$question->answer_key}}">
<input type="text" name="max_score" value="{{$question->max_score}}">

<button type="submit">submit</button>
</form>