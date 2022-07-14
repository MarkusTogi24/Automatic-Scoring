<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Http\Helpers\GeneralHelper;
use App\Http\Helpers\KelasHelper as HelpersKelasHelper;
use App\Models\KelasDanMember;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{

    private $generalHelper;
    private $kelasHelper;

    public function __construct()
    {
        $this->generalHelper = new GeneralHelper;
        $this->kelasHelper = new HelpersKelasHelper;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $this->generalHelper->authorize(["GURU", "SISWA"]);
        $kelas = Kelas::all();
        echo $kelas;

        return $kelas;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->generalHelper->authorize(["GURU"]);

        $kelas = new Kelas();
        $kelas->id_guru = 1;
        $kelas->name = $request->name;
        $kelas->enrollment_key = $this->kelasHelper->generate_enrollment_key();

        $kelas->save();

        return $kelas;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function class_enrollment(Request $request)
    {
        $class_id = $this->kelasHelper->get_class_id_from_enrollment_key($request->enrollment_key);

        if ($class_id != null) {
            $class_and_member = new KelasDanMember;
            $class_and_member->id_kelas = $class_id;
            $class_and_member->id_member = Auth::user()->role;
            $class_and_member->save();
        }

        return KelasDanMember::all();
    }
}
