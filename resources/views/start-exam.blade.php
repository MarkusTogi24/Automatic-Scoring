<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>
<body>
    
@for ($i = 1; $i <= count($questions); $i++)
<button onclick="return loadQuestionComponents({{ $i }})"> {{ $i }} </button>
@endfor

<div id="question_component">
</div>
</body>
</html>

<script>
    let something = <?php echo $questions; ?>
    
    function loadQuestionComponents(index){
        index -= 1
        $("#question_component").empty()
        $("#question_component").append("<h5>" + something[index].question + "</h5>")
        console.log(something[index])
        if (something[index].answer == null) {
            $("#question_component").append("<input id='input_column' value =''>")
        } else {
            $("#question_component").append("<input id='input_column' value ='"+something[index].answer+"'>")
        }

        related_index = index
        question_id = something[index].id
        
        if (something[index].answer_id == null){
            $("#question_component").append("<button onclick='return saveAnswer(question_id, related_index)'>submit</button>")
        } else {
            $("#question_component").append("<button onclick='return updateAnswer(related_index)'>submit</button>")
        }
    }

    function saveAnswer(question_id, index) {
        answer = document.getElementById("input_column").value

      let request = new XMLHttpRequest();
      request.open("get", "http://127.0.0.1:8000/save_answer/"+question_id+"/"+answer+"");
      request.send();
      request.onload = () => {
        if (request.status === 200) {
            let response = JSON.parse(request.response);
            something[index].answer_id = response.answer_id
            something[index].answer = response.answer
        } else {
          console.log("Page not found"); // if link is broken, output will be page not found
        }

      };
    }

    function updateAnswer(index) {
        answer = document.getElementById("input_column").value
        answer_id = something[index].answer_id

        console.log(answer_id)
        let request = new XMLHttpRequest();
        request.open("get", "http://127.0.0.1:8000/update_answer/"+answer_id+"/"+answer+"");
        request.send();
        request.onload = () => {
            if (request.status === 200) {
                let response = JSON.parse(request.response);
                something[index].answer_id = response.answer_id
                something[index].answer = response.answer
            } else {
            console.log("Page not found"); // if link is broken, output will be page not found
            }

        };
    }

</script>