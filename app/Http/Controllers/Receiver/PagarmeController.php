<?php

namespace App\Http\Controllers\Receiver;

use App\Http\Controllers\Controller;
use App\Http\Traits\apiResponser;
use App\Models\Transaction;
use App\Services\PagarmeRequestService;
use Illuminate\Http\Request;

class PagarmeController extends Controller
{
    use apiResponser;
    public function watch(Request $request)
    {
        $ret['success'] = false;
        $this->log($request->all(), "Recebimento de Postback");
        $pagarme = new PagarmeRequestService;

        $postbackPayload = $request->all();
        $signature = $request->server('HTTP_X_HUB_SIGNATURE', '');

        if($pagarme->validatePostback($postbackPayload, $signature)) {
            $ret['error_message'] = "Permissão negada";
        } elseif(($transaction_data = $request->get("transaction")) && collect($transaction_data)->get("status", false) != false){

            $transaction = Transaction::where("pagarme_id", collect($transaction_data)->get('id', 0))->first();

            if(!empty($transaction) ){
                $transaction->status = $transaction_data['status'];
                $transaction->save();
                $ret['success'] = true;
            } else {
                $ret['error_message'] = __("Transação não encontrada.");
            }
        } else {
            $ret['error_message'] = __("Formato inválido");
        }

        return $ret['success'] ? $this->success($transaction, __("Transação alterada com sucesso")) : $this->error($ret['error_message'], 403);
    }

    
}
