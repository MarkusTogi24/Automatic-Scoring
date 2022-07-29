<h1>Hallo</h1>

<form action="{{url('classroom/'.$classroom_id.'/exam-add-question/'.$exam_id)}}" method="post">
@csrf
<input type="text" name="question">
<input type="text" name="answer_key">
<input type="text" name="max_score">

<button type="submit">submit</button>
</form>