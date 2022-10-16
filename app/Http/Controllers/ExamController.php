<?php

namespace App\Http\Controllers;

use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Models\StudentAndScore;
use App\Http\Helpers\ExamHelper;
use App\Exports\ExamResultExport;
use App\Models\ClassroomAndMember;
use App\Models\StudentAndQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
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
        $exam->is_open      = 0;

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

        $questions_id = $questions->pluck('id');
        $student_answer_count = StudentAndQuestion::query()
            ->where('student_id', Auth::user()->id)
            ->whereIn('question_id', $questions_id)
            ->count();

        $student_exam = StudentAndScore::query()
            ->where('exam_id', $exam->id)
            ->where('student_id', Auth::user()->id)
            ->count();

        $classroom_members = ClassroomAndMember::select(
                'classroom_and_member.*', 
                'users.name', 
                'student_and_score.total_score', 
                'student_and_score.updated_at as submit_time'
            )
            ->leftJoin('users', function ($join) {
                $join->on('classroom_and_member.member_id', 'users.id');
            })
            ->leftJoin('student_and_score', function ($join) {
                $join->on('classroom_and_member.member_id', 'student_and_score.student_id');
            })
            ->where('users.role', 'SISWA')
            ->where('classroom_id', $classroom->id)
            ->get();

        // dd($classroom_members);

        return view('pages.user.exam.index', 
            compact('classroom', 'exam', 'questions', 'duration', 'total_score', 'student_answer_count', 'student_exam', 'classroom_members'));
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


        if ($validated['is_open'] == 1) {
            $questions_of_exam = Question::all()->where('exam_id', $exam->id);
            if (count($questions_of_exam) > 0) {
                $exam->is_open      = $validated['is_open'];
                $exam->save();
            } else {
                return redirect()->route('exam.show', [$classroom, $exam])
                ->with("failed","Gagal menyimpan perubahan pada {$exam->name} - {$classroom->name}! (Soal belum ditambahkan)");    
            }
        } else {
            $exam->is_open      = $validated['is_open'];
            $exam->save();
        }
        

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

    public function start(Classroom $classroom, Exam $exam){
        $this->exam_helper->authorizing_exam_question_access_for_student($classroom->id, $exam->id);

        $questions = Question::select("question.*", "student_and_question.answer as answer", "student_and_question.id as answer_id")
            ->leftJoin('student_and_question', function ($join) {
                $join->on('question.id', 'student_and_question.question_id');
                $join->on('student_and_question.student_id', DB::raw(Auth::user()->id));
            })                   
            ->where('question.exam_id', $exam->id)
            ->orderBy('id')
            ->get();

        return view ('pages.user.exam.start', compact('questions', 'exam', 'classroom'));
    }

    public function save(Request $request, Classroom $classroom, Exam $exam){

        if(isset($request->answer_text)){
            if( isset($request->answer_id) ){
                $entity = StudentAndQuestion::firstWhere('id', $request->answer_id);
                $entity->question_id    = $request->question_id;
                $entity->answer         = $request->answer_text;
                $entity->student_id     = $request->student_id;
                $entity->score          = 0;
                $entity->save();
            }else{
                $entity = new StudentAndQuestion;
                $entity->question_id    = $request->question_id;
                $entity->answer         = $request->answer_text;
                $entity->student_id     = $request->student_id;
                $entity->score          = 0;
                $entity->save();
            }
        }

        $questions = Question::query()
            ->where('exam_id', $exam->id)
            ->get('id');

        $student_score = StudentAndQuestion::query()
            ->whereIn('question_id', $questions)
            ->where('student_id', Auth::user()->id)
            ->sum('score');

        $student_exam = new StudentAndScore;
        $student_exam->exam_id      = $exam->id;
        $student_exam->student_id   = Auth::user()->id;
        $student_exam->total_score  = $student_score;
        $student_exam->save();
        
        return redirect()->route('exam.show', [$classroom, $exam])
            ->with("success","Seluruh jawaban anda pada {$exam->name} - {$classroom->name} berhasil dikirimkan!");
    }

    public function closed(Classroom $classroom, Exam $exam)
    {
        return redirect()->route('exam.show', [$classroom, $exam])
            ->with("warning","{$exam->name} - {$classroom->name} telah ditutup, seluruh jawaban anda berhasil disimpan!");
    }

    public function result(Classroom $classroom, Exam $exam)
    {
        $questions = Question::select("question.*", "student_and_question.answer as answer", "student_and_question.id as answer_id", "student_and_question.score as score")
            ->leftJoin('student_and_question', function ($join) {
                $join->on('question.id', 'student_and_question.question_id');
                $join->on('student_and_question.student_id', DB::raw(Auth::user()->id));
            })                   
            ->where('question.exam_id', $exam->id)
            ->orderBy('id')
            ->get();

        return view('pages.user.exam.result', compact('questions', 'classroom', 'exam'));
    }

    public function exportExamResult(Exam $exam){
        $classroom = Classroom::find($exam->class_id);
        return Excel::download(new ExamResultExport($exam), "Rekap Hasil {$exam->name} - {$classroom->name}.xlsx");
    }
}
