<?php

namespace App\Http\Controllers;

use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Helpers\ExamHelper;
use App\Http\Helpers\ClassroomHelper;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Exam\StoreExamRequest;
use App\Http\Requests\Exam\UpdateExamRequest;

class ExamController extends Controller
{

    private $helper;
    private $exam_helper;

    public function __construct()
    {
        $this->helper       = new ClassroomHelper;
        $this->exam_helper  = new ExamHelper;
        $this->middleware('auth');
    }
    
    public function index($classroom_id)
    {
        $this->helper->authorizing_classroom_member($classroom_id);
        $exams = Exam::select("*")->where('class_id', $classroom_id)->get();

        return $exams;
    }
    
    public function create($classroom_id)
    {
        //
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);

        return view('create_ujian', compact('classroom_id'));
    }
    
    public function store(StoreExamRequest $request, $classroom_id)
    {   
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);

        $validated = $request->validated();

        $exam = new Exam;
        $exam->class_id     = $classroom_id;
        $exam->name         = $validated['name'];
        $exam->description  = $validated['description'];
        $exam->is_open      = 1;

        // Set start_time
        $request_start      = new DateTime($validated['start_time']);
        $exam->start_time   = $request_start;

        // Set end_time
        $hours              = (int) explode(":", $validated['duration'])[0];
        $minutes            = (int) explode(":", $validated['duration'])[1];
        $exam->end_time     = (clone $request_start)->add(new DateInterval("PT{$hours}H{$minutes}M"));

        $exam->save();

        return redirect()->route('classroom.show', $classroom_id)
            ->with("success","Data {$exam->name} berhasil disimpan!");
    }

    public function show(Classroom $classroom, Exam $exam)
    {
        $this->helper->authorizing_by_role(["GURU", "SISWA"]);
        $this->helper->authorizing_classroom_member($classroom->id);

        $duration = $this->exam_helper->get_duration($exam);
        
        $questions = Question::query()
            ->where('exam_id', $exam->id)
            ->get();

        $total_score = $questions->sum('max_score');

        return view('pages.user.exam.index', compact('classroom', 'exam', 'questions', 'duration', 'total_score'));
    }
    
    public function edit($classroom_id, $exam_id)
    {
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);

        $exam = Exam::find($exam_id);
        return view ('update_ujian', compact('exam', 'classroom_id'));
    }
    
    public function update(UpdateExamRequest $request, Classroom $classroom, Exam $exam)
    {
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom->id);

        $validated = $request->validated();

        $exam->name         = $validated['name'];
        $exam->description  = $validated['description'];
        

        // Set start_time
        $request_start      = new DateTime($validated['start_time']);
        $exam->start_time   = $request_start;

        // Set end_time
        $hours              = (int) explode(":", $validated['duration'])[0];
        $minutes            = (int) explode(":", $validated['duration'])[1];
        $exam->end_time     = (clone $request_start)->add(new DateInterval("PT{$hours}H{$minutes}M"));

        $exam->is_open      = $validated['is_open'];
        $exam->save();

        return redirect()->route('exam.show', [$classroom, $exam])
            ->with("success","Perubahan pada {$exam->name} - {$classroom->name} berhasil disimpan!");
    }
    
    public function destroy($classroom_id, $exam_id)
    {
        //
        $exam = Exam::find($exam_id);

        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);

        $exam->delete();
    }

    public function changeStatus(Request $request, $classroom_id, $exam_id){
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);

        $exam = Exam::find($exam_id);
        $exam->is_open = $request->is_open;
        $exam->save();

        return $exam;
    }
}
