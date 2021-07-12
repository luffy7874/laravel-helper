<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Session;
use Exception;

class RazorPayController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('proceedToPay');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));

            } catch (Exception $e) {
                return  $e->getMessage();
                session()->put('error',$e->getMessage());
                return redirect()->back();
            }
        }

        session()->put('success', 'Payment successful');
        return redirect()->back();
    }
}