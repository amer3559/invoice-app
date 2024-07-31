<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function get_all_invoices(){
        $invoices = Invoice::with('customer')->orderBy('id', 'DESC')->get();

        return response()->json([
            'invoices' => $invoices
        ],200);
    }

    public function search_invoice(Request $request){
        $search = $request->get('s');
        if ($search != null){
            $invoices = Invoice::with('customer')
                ->where('id', 'LIKE', "%$search%")
                ->get();
            return response()->json([
                'invoices' => $invoices
            ],200);
        }else{
            return $this->get_all_invoices();
        }
    }

    public function create_invoice(){
        $counter = Counter::where('key', 'invoice')->first();

         $invoice = Invoice::orderBy('id', 'DESC')->first();
        if ($invoice){
            $counters = $counter->value + ($invoice->id + 1);
        }else{
            $counters = $counter->value;
        }

        $formData = [
            'number' => $counter->prefix.$counters,
            'customer_id' => null,
            'date' => null,
            'due_date' => date('Y-m-d'),
            'reference' => null,
            'terms_and_conditions' => 'Default Terms and conditions',
            'discount' => 0,
            'items' => [
                [
                    'product_id' => null,
                    'product' => null,
                    'unit_price' => 0,
                    'quantity' => 1,
                ]
            ]
        ];

        return response()->json($formData);

    }
}
