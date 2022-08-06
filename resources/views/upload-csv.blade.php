<h1>Hello</h1>

<form action="{{url('upload-csv')}}" enctype="multipart/form-data" method="post">
@csrf
<input type="file" name="csvfile">
<button submit>submit</button>
</form>