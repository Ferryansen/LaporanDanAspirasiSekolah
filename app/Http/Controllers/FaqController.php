<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    function seeAllFaq() {
        $faqs = Faq::all();

        return view('support.faqView', compact('faqs'));
    }

    function updateFaqForm($Faq_id) {
        $currFaq = Faq::findOrFail($Faq_id);

        return view('support.adminHeadmasterStaff.updateFaqView', compact('currFaq'));
    }

    function updateFaq(Request $request, $faq_id) {
        $currFaq = Faq::findOrFail($faq_id);

        $rules = [
            'question' => 'required|max:250',
            'answer' => 'required|max:1000',
        ];

        $messages = [
            'question.required' => 'Jangan lupa masukin pertanyaannya yaa',
            'question.max' => 'Pertanyaan yang kamu masukin cuman bisa maksimal 250 karakter nih',
            
            'answer.required' => 'Jangan lupa masukin jawabannya yaa',
            'answer.max' => 'jawaban yang kamu masukin cuman bisa maksimal 1000 karakter nih',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = [
            'question' => $request->question,
            'answer' => $request->answer,
            'updatedBy' => Auth::user()->name,
        ];

        $currFaq->update($credentials);
        return redirect()->route('faq.seeall')->with('successMessage', 'FAQ berhasil diperbarui');
    }

    function createFaqForm() {
        return view('support.adminHeadmasterStaff.createFaqView');
    }

    function createFaq(Request $request) {
        $rules = [
            'question' => 'required|max:250',
            'answer' => 'required|max:1000',
        ];

        $messages = [
            'question.required' => 'Jangan lupa masukin pertanyaannya yaa',
            'question.max' => 'Pertanyaan yang kamu masukin cuman bisa maksimal 250 karakter nih',
            
            'answer.required' => 'Jangan lupa masukin jawabannya yaa',
            'answer.max' => 'jawaban yang kamu masukin cuman bisa maksimal 1000 karakter nih',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = [
            'question' => $request->question,
            'answer' => $request->answer,
            'createdBy' => Auth::user()->name,
        ];

        Faq::create($credentials);
        return redirect()->route('faq.seeall')->with('successMessage', 'FAQ berhasil ditambahkan');
    }

    function deleteFaq($faq_id) {
        $faq = Faq::findOrFail($faq_id);
        $faq->delete();

        return redirect()->route('faq.seeall')->with('successMessage', 'FAQ berhasil dihapus');
    }
}
