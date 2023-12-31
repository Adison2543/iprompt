<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use Alert;
use App\Models\imported;
use App\Models\impdType;
use App\Models\User;
use App\Models\agencie;
use App\Models\branche;
use App\Models\department;
use App\Models\user_role;
use Illuminate\Support\Facades\Auth;


class ImportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $impd = impdType::all();
        return view('forms/imported', compact('impd'));
    }

    public function addType(Request $request)
    {
        try {
            $addType = impdType::create([
                'name' => $request->doct,
                'val' => $request->doct,
            ]);
            Alert::toast('บันทึกข้อมูลสำเร็จ','success');
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
    }

    public function storeImported (Request $request) {
        // dd($request);
        $imported = new imported;
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $uploadedFile = $request->file('file');
            $originalFilename = $uploadedFile->getClientOriginalName();
            $storedFilePath = $uploadedFile->storeAs('public/uploads', $originalFilename);
        } else {
            Alert::toast('Error uploading file!','error');
        }
        $imported->type = $request->doctype;
        $imported->recoder = $request->recorder;
        $imported->receive_date = $request->recivedate;
        $imported->book_number = $request->book_num;
        $imported->receiver = $request->receiver;
        $imported->receiver_dpm = $request->recive_dpm;
        $imported->from = $request->source;
        $imported->book_subj = $request->book_subj;
        $imported->status = '1';
        $imported->file = $originalFilename ?? '';
        $imported->receiver_agn = $request->recive_agn;
        $imported->receiver_brn = $request->recive_brn;
        $imported->save();
        Alert::toast('Your Form as been Imported!','success');
        return redirect('/tables/imported');
    }

    public function imported() {
        if (Auth::user()->hasAnyRole('employee', 'leader_dpm')) {
            $imported = imported::orderBy('id', 'desc')->where('receiver_dpm', Auth::user()->dpm)->orderBy('id', 'desc')->paginate(10);
            $imp_mou = imported::where('type', 'mou')->where('receiver_dpm', Auth::user()->dpm)->orderBy('id', 'desc')->paginate(10);
            $imp_proj = imported::where('type', 'proj')->where('receiver_dpm', Auth::user()->dpm)->orderBy('id', 'desc')->paginate(10);
            $imp_cont = imported::where('type', 'cont')->where('receiver_dpm', Auth::user()->dpm)->orderBy('id', 'desc')->paginate(10);
            $imp_anno = imported::where('type', 'anno')->where('receiver_dpm', Auth::user()->dpm)->orderBy('id', 'desc')->paginate(10);
        } else {
            $imported = imported::orderBy('id', 'desc')->orderBy('id', 'desc')->paginate(10);
            $imp_mou = imported::where('type', 'mou')->orderBy('id', 'desc')->paginate(10);
            $imp_proj = imported::where('type', 'proj')->orderBy('id', 'desc')->paginate(10);
            $imp_cont = imported::where('type', 'cont')->orderBy('id', 'desc')->paginate(10);
            $imp_anno = imported::where('type', 'anno')->orderBy('id', 'desc')->paginate(10);
        }
        
        $user = User::all();
        return view('/tables/imported', compact('imported', 'user','imp_mou','imp_cont','imp_proj','imp_anno'));
    }

    public function updateStatus(Request $request)
    {
        try {
            // Get the values sent from the JavaScript
            $resName = $request->input('swalInput1');
            $note = $request->input('swalInput2');
            $rowid = $request->input('swalInput3');
            $res = $request->input('res');

            $form = imported::find($rowid);
            if ($form) {
                $form->respondent = $resName;
                $form->note = $note;
                $form->resp_date = date('Y-m-d H:i:s');
                if ($res) {
                    $form->status = '2';
                } else {
                    $form->status = '3';
                }
                $form->save();
            }
            // Return a response (optional)
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            // Log the error for debugging
            // Log::error($e->getMessage());

            // Return an error response
            return response()->json(['error' => 'An error occurred while processing the request.'], 500);
        }
    }

}
