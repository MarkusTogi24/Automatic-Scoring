<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Helpers\ExamHelper;
use App\Imports\QuestionsImport;
use App\Action\QuestionsStoreAction;

use Maatwebsite\Excel\Facades\Excel;
use function PHPUnit\Framework\isNan;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Http\Requests\Question\ImportQuestionsRequest;

class QuestionController extends Controller
{

    private $helper;

    public function __construct()
    {
        $this->helper = new ExamHelper;
        $this->middleware('auth');
    }
    
    public function index($classroom_id, $exam_id)
    {
        //
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);

        $questions = Question::all()->where('exam_id', $exam_id);

        return $questions;
    }
    
    public function create($classroom_id, $exam_id)
    {
        //
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);

        return view ('add_question', compact('classroom_id', 'exam_id'));
    }
    
    public function store(StoreQuestionRequest $request, Classroom $classroom, Exam $exam)
    {
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom->id);

        $validated = $request->validated();

        $question = new Question;
        $question->exam_id      = $exam->id;
        $question->question     = $validated['question'];
        $question->answer_key   = $validated['answer_key'];
        $question->max_score    = $validated['max_score'];
        $question->save();

        return redirect()->route('exam.show', [$classroom, $exam])
            ->with("success","Data soal berhasil disimpan!");
    }

    public function upload(ImportQuestionsRequest $request, Classroom $classroom, Exam $exam)
    {
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom->id);

        $temp = $request->file('questionFile' . $request->type)->store('temp');
        $path = storage_path('app') . '/' . $temp;

        $questions = Excel::toCollection(new QuestionsImport, $path);

        try {
            $response = (new QuestionsStoreAction)->importQuestions($questions, $exam);
        } catch (\Exception $exception) {
            return redirect()->back()
                ->with("failed", "Terjadi kesalahan saat akan menyimpan soal, harap coba beberapa saat lagi.");
        } catch (\Error $error) {
            return redirect()->back()
                ->with("failed", "Terjadi kesalahan saat akan menyimpan soal, harap coba beberapa saat lagi.");
        }

        if(is_numeric($response)){
            return redirect()->back()
                ->with("success", "Sebanyak {$response} soal baru berhasil disimpan.");
        }else{
            return redirect()->back()
                ->with("failed", $response);
        }
    }
    
    public function show($classroom_id, $question_id)
    {
        //
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);
        $question = Question::find($question_id);

        return $question;
    }
    
    public function edit($classroom_id, $question_id)
    {
        //
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom_id);
        
        $question = Question::find($question_id);
        return view ('update_question', compact('classroom_id', 'question_id', 'question'));
    }
    
    public function update(UpdateQuestionRequest $request, Classroom $classroom, Exam $exam)
    {
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom->id);
        
        $validated = $request->validated();

        $question             = Question::find($validated['question_id']);
        $question->question   = $validated['question'];
        $question->answer_key = $validated['answer_key'];
        $question->max_score  = $validated['max_score'];
        
        $question->save();

        return redirect()->route('exam.show', [$classroom, $exam])
            ->with("success","Perubahan berhasil disimpan!");
    }
    
    public function destroy(Request $request, Classroom $classroom, Exam $exam)
    {
        $this->helper->authorizing_by_role("GURU");
        $this->helper->authorizing_classroom_member($classroom->id);
        
        $question = Question::find($request->question_id);
        $question->delete();

        return redirect()->route('exam.show', [$classroom, $exam])
            ->with("success","Data soal berhasil dihapus!");
    }

    public function startExam($classroom_id, $exam_id){
        $this->helper->authorizing_exam_question_access_for_student($classroom_id, $exam_id);

        $questions = Question::select("*")->where('exam_id', $exam_id)->get();
        
        return $questions;
    }
}
