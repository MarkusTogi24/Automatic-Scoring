<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        CREATE VIEW classroom_member_exam_question_views AS
        (
            SELECT
            classroom.id as classroom_id,
            classroom_and_member.id as member_id,
            exam.id as exam_id,
            question.id as question_id, question.question as question, question.answer_key as answer_key, question.max_score as max_score
  
            FROM `classroom_and_member`
            RIGHT JOIN classroom ON classroom_and_member.classroom_id = classroom.id
            RIGHT JOIN exam ON classroom.id = exam.class_id
            RIGHT JOIN question ON question.exam_id = exam.id
        )
      ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classroom_member_exam_question_view');
    }
};
