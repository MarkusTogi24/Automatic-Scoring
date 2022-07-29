<h1>halo</h1>
<form action="{{url('member')}}" method="post">
@csrf
<input type="text" name="enrollment_key">
<button type="submit">submit</button>
</form>