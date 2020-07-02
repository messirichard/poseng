<?php
	require_once('../_config.php');
	require_once(LANG_DIR . LANG . '.php');
	dbConnect($conn);

	define("DATA_TABLE_INVOICES", "invoices");
	define("DATA_TABLE_INVOICES_PAYMENT", "invoices_payment");
		
	//$dev = getDeveloperById($conn,15);
	//changeAccountType($conn,$dev,2,12); 
	//die();
	try {
		require_once(dirname(__FILE__) . '/vendor/midtrans/Veritrans.php');
		Veritrans_Config::$isProduction = PAYMENT_MIDTRANS_PRODUCTION;
		Veritrans_Config::$serverKey = PAYMENT_MIDTRANS_KEY;
		$notif = new Veritrans_Notification();
		$transaction = $notif->transaction_status;
		$type = $notif->payment_type;
		$order_id = $notif->order_id;
		$fraud = $notif->fraud_status;
		$status = 'unpaid';
		
		if ($transaction == 'capture') {
			// For credit card transaction, we need to check whether transaction is challenge by FDS or not
			if ($type == 'credit_card'){
				if ($fraud == 'challenge') $status = 'challenge'; // Challenge by FDS
				else $status = 'success';
			}
		} else if ($transaction == 'settlement') $status = 'settlement';
		else if($transaction == 'pending') $status = 'pending';
		else if ($transaction == 'deny') $status = 'denied';
		else if ($transaction == 'expire') $status = 'expired';
		else if ($transaction == 'cancel') $status = 'cancel';
		else $status = $transaction;
		
		$verified = true;
		if ($status !== 'expire') {
			$statusVerify = Veritrans_Transaction::status($order_id);
			$inputVerify = $order_id . $statusVerify->status_code . $statusVerify->gross_amount . PAYMENT_MIDTRANS_KEY;
			$signatureVerify = openssl_digest($inputVerify, 'sha512');
			$verified = ($signatureVerify == $statusVerify->signature_key);
		}
		
		if (!$verified) { // wrong signature
			echoJSONResult(400,createObject("error",ERROR_TITLE,HTTP_400));
		} else {
			$inv = getInvoiceByOrderId($conn,$order_id);
			if ($inv!==null) {
				$dev = getDeveloperById($conn,$inv->developer_id);
				// if payment is okay then upgrade/downgrade account
				if ($status == 'pending') { 
					foreach($inv->items as $item) {
						changeAccountType($conn,$dev,$item->sku,$item->qty); 
						file_put_contents("test.txt", $item->sku . ' ' . $item->qty);
					}
				}
				
				// update payment status
				if (put($conn,DATA_TABLE_INVOICES,array("status"),array("'" . strtolower($status) . "'"),"order_id","'" . $order_id . "'")) {
					//update status to db
					if ($status == 'success') {
						post($conn,DATA_TABLE_INVOICES_PAYMENT,array("name","detail","paddate","ref_id","invoice_id"),array("'MIDTRANS:" . $statusVerify->va_numbers[0]->bank . "'","'" . $statusVerify->va_numbers[0]->va_number . "'","UTC_TIMESTAMP()","'" . $statusVerify->transaction_id . "'",$inv->id));
					}
				}

				$obj = createObject("ok",OK_TITLE,HTTP_200);
				echoJSONResult(200,$obj);				
			} else echoJSONResult(404,createObject("error",ERROR_TITLE,HTTP_404));			
		}
	} catch (Exception $e) {			
		file_put_contents("ERROR-account-billing-midtrans.txt", $e->getMessage());
		echoJSONResult(500,createObject("error",ERROR_TITLE,$e->getMessage()));
	}

	dbDisconnect($conn);
