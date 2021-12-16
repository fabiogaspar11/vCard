<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Vcard;
use App\Models\Category;
use App\Models\Transaction;

use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Requests\VcardPut;
use App\Models\DefaultCategory;
use App\Http\Requests\VcardPost;
use App\Http\Requests\PasswordPut;
use App\Http\Requests\VcardDelete;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmationCodePut;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\VcardResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TransactionResource;
use Illuminate\Validation\ValidationException;

class VcardController extends Controller
{
    public function getVcards(Request $request)
    {
        $state = $request->query('state');
        $phone_number = $request->query('phone_number');
        $name = $request->query('name');
        $query = Vcard::query();
        $noQuerString = $state == null && $phone_number == null && $name == null;
        if ($noQuerString) {
            $vcards = Vcard::paginate(3);
        } else {
            if ($state != null) {
                $query->where("blocked", '=', $state);
            }
            if ($phone_number != null) {
                $query->where('phone_number', '=', $phone_number);
            }
            if ($name != null) {
                $query->where('name', '=', $name);
            }
            //$vcards = $query->get();
            $vcards = $query->paginate(3);
        }
        return VcardResource::collection($vcards);
    }

    public function checkVcard(int $vcard)
    {
        $vcardFound = Vcard::find($vcard);
        return $vcardFound == null ? ["response" => "No"] : ["response" =>  "Yes"];
    }
    public function getVcard(Vcard $vcard)
    {
        return new VcardResource($vcard);
    }

    public function storeVcard(VcardPost $request)
    {
        $validated_data = $request->validated();
        $vcard = new Vcard();
        $vcard->fill($validated_data);
        $filename = $vcard->phone_number . "_" . Str::random(6) . '.jpg';

        if ($request->hasFile('photo_url')) {
            $request->file('photo_url')->storeAs('public/fotos', $filename);
            $vcard->photo_url = $filename;
        }

        $vcard->max_debit = 5000.00;
        $vcard->balance = 0.00;
        $vcard->blocked = 0;
        $vcard->password = Hash::make($vcard->password);
        $vcard->confirmation_code = Hash::make($vcard->confirmation_code);

        try {
            $vcard->save();

            $categoriesDefault = DefaultCategory::all();
            foreach ($categoriesDefault as $categoryDef) {
                $category = new Category();
                $category->vcard = $vcard->phone_number;
                $category->type = $categoryDef->type;
                $category->name = $categoryDef->name;
                $category->save();
            }
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception("Error creating the vcard");
        }
        return new VcardResource($vcard);
    }

    public function updateVcard(VcardPut $request, Vcard $vcard)
    {
        $old_photo = $vcard->photo_url;
        $validated_data = $request->validated();
        $vcard->fill($validated_data);

        $filename = $vcard->phone_number . "_" . Str::random(6) . '.jpg';
        if ($request->hasFile('photo_url')) {
            Storage::disk('local')->delete('public/fotos/' . $old_photo);
            $request->file('photo_url')->storeAs('public/fotos', $filename);
            $vcard->photo_url = $filename;
        }

        $vcard->save();
        return new VcardResource($vcard);
    }

    public function alterBlock(Vcard $vcard)
    {
        $vcard->blocked = $vcard->blocked == 1 ? 0 : 1;
        $vcard->save();
        return new VcardResource($vcard);
    }

    public function alterDebitLimit(VcardPut $request, Vcard $vcard)
    {
        if (!isset($request->max_debit)) {
            throw ValidationException::withMessages(['max_debit' => "Max debit is mandatory"]);
        }
        $vcard->max_debit = $request->max_debit;
        $vcard->save();
        return new VcardResource($vcard);
    }

    public function destroyVcard(VcardDelete $request, Vcard $vcard)
    {
        if (auth()->user()->user_type != 'A') {
            if (!isset($request->password)) {
                throw ValidationException::withMessages(['password' => "Password is mandatory"]);
            }
            if (!isset($request->confirmation_code)) {
                throw ValidationException::withMessages(['confirmation_code' => "PIN is mandatory"]);
            }
            if (!Hash::check($request->password, $vcard->password)) {
                throw ValidationException::withMessages(['password' => "Password is not correct"]);
            }
            if (!Hash::check($request->confirmation_code, $vcard->confirmation_code)) {
                throw ValidationException::withMessages(['confirmation_code' => "PIN is not correct"]);
            }
        }
        if ($vcard->balance > 0) {
            throw ValidationException::withMessages(['balance' => "Vcard cannot be deleted - Balance is bigger than 0.00"]);
        }
        $transactions = $vcard->transactions;
        $numberTransactions = count($transactions);
        try {
            if ($numberTransactions > 0) {
                foreach ($transactions as $transaction) {
                    $transaction->delete();
                }
                $vcard->delete();
            }
            if ($numberTransactions == 0) {
                $categories = $vcard->categories;
                foreach ($categories as $category) {
                    $category->forceDelete();
                }
                $vcard->forceDelete();
            }
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception("Error deleting the vcard");
        }

        return new VcardResource($vcard);
    }

    public function getVcardLastTransaction(Vcard $vcard)
    {
        $transactions = $vcard->transactions;
        if ($transactions->isEmpty()) {
            return [
                "error" => "This vcard doesn't have any transactions yet"
            ];
        }
        $transaction =  $transactions->sortByDesc('datetime')->first();
        return new TransactionResource($transaction);
    }

    public function getVcardTransactions(Request $request, Vcard $vcard)
    {
        $beginDate = $request->query('beginDate');
        $endDate = $request->query('endDate');
        $transactionType = $request->query('transactionType');
        $orderByAmount = $request->query('orderByAmount');
        $orderByMostRecent = $request->query('orderByMostRecent');

        $noQuerString = $beginDate == null && $endDate == null && $transactionType == null && $orderByAmount == null && $orderByMostRecent == null;
        if ($noQuerString) {
            //$transactions = $vcard->transactions->sortByDesc('datetime');
            $transactions = Transaction::orderBy('datetime', 'desc')
                ->where('vcard', $vcard->phone_number)
                ->paginate(10);

            if ($transactions->isEmpty()) {
                return [
                    "error" => "This vcard doesn't have any transactions yet"
                ];
            }
        } else {
            if ($beginDate != null && $endDate != null) {
                if (strtotime($beginDate) > strtotime($endDate)) {
                    throw ValidationException::withMessages(['filterOrderBy' => "Begin date cannot be bigger than end date"]);
                }
                if (strtotime($beginDate) == strtotime($endDate)) {
                    throw ValidationException::withMessages(['filterOrderBy' => "Begin date cannot be equal to end date"]);
                }
            }
            if (($beginDate != null &&  $endDate == null) || ($endDate != null &&  $beginDate == null)) {
                throw ValidationException::withMessages(['filterOrderBy' => "Begin Date or End Date is missing"]);
            }
            if ($transactionType != null && $transactionType != 'C' && $transactionType != 'D') {
                throw ValidationException::withMessages(['filterOrderBy' => "Transaction type can only be Credit (C) or Debit (D)"]);
            }
            $query = Transaction::query();
            $query->where("vcard", '=', $vcard->phone_number);
            if ($beginDate != null) {
                $query->where("date", '>=', $beginDate);
            }
            if ($endDate != null) {
                $query->where('date', '<=', $endDate);
            }
            if ($transactionType != null) {
                $query->where('type', '=', $transactionType);
            }
            if ($orderByAmount && !$orderByMostRecent) {
                $query->orderBy('value', 'asc');
            }
            if ($orderByMostRecent && !$orderByAmount) {
                $query->orderBy('date', 'desc');
            }
            if ($orderByMostRecent && $orderByAmount) {
                $query->orderBy('date', 'desc')->orderBy('value', 'asc');
            }

            $transactions = $query->paginate(10);
            //$transactions = $query->get();
        }

        return TransactionResource::collection($transactions);
    }

    public function getVcardCategories(Request $request, Vcard $vcard)
    {
        $type = $request->query('type');
        if ($type != null) {
            $query = Category::query();
            $query->where("vcard", '=', $vcard->phone_number);
            $query->where("type", '=', $type);
            $query->orderBy('id', 'desc');
            $categories = $query->get();
        } else {
            $categories = Category::orderBy('id', 'asc')
                ->where('vcard', $vcard->phone_number)
                ->paginate(5);
        }
        if ($categories->isEmpty()) {
            return response()->json([
                'error' => 'This vcard does not have any categories yet'
            ], 404);
        }

        return CategoryResource::Collection($categories);
    }

    public function getVcardPhoto(Vcard $vcard)
    {
        return $vcard->photo_url;
    }

    public function piggyBankState(Vcard $vcard)
    {
        return $vcard->custom_data == null ? ["response" => false] : ["response" => true];
    }

    public function getPiggyBankBalance(Vcard $vcard)
    {
        return $vcard->custom_data;
    }

    public function createPiggyBank(Vcard $vcard)
    {
        if ($vcard->custom_data != null)
            return "Vcard already has a piggy bank";

        $piggyBank = array();
        $piggyBank["balance"] = 0;

        $json = json_encode($piggyBank);
        $vcard->custom_data =  $json;
        $vcard->save();
        return $piggyBank["balance"];
    }

    public function piggyBankOperation(Vcard $vcard, Request $request)
    {
        $piggyBank = json_decode($vcard->custom_data);
        $currentBalance = $piggyBank->balance;
        $requestAmount = $request->amount;

        if (!isset($requestAmount)) {
            throw ValidationException::withMessages(['amount' => "Value is mandatory"]);
        }

        if (!str_contains($requestAmount, '.')) {
            $requestAmount .= ".00";
        } else {
            $requestAmount = floor($request->amount * 100) / 100;
            if (!str_contains($requestAmount, '.')) {
                $requestAmount .= ".00";
            }
        }

        if (!is_numeric($requestAmount)) {
            throw ValidationException::withMessages(['amount' => "Value must be a number"]);
        }

        if ($requestAmount <= 0) {
            throw ValidationException::withMessages(['amount' => "Value must be a positive number"]);
        }

        $stringSplited = "";
        try {
            $stringSplited = explode('.', $request->amount)[1];
        } catch (Exception $e) {
        }

        if (strlen($stringSplited) > 2) {
            throw ValidationException::withMessages(['amount' => "Value must be a two decimal places"]);
        }

        if ($vcard->balance < $requestAmount && $request->type == 'C') {
            throw ValidationException::withMessages(['amount' => "Money value cannot be superior than your balance"]);
        }

        if ($currentBalance < $requestAmount && $request->type == 'D') {
            throw ValidationException::withMessages(['amount' => "Money value cannot be superior than your piggy bank balance"]);
        }

        if ($request->type == 'C') {
            $newBalance = $currentBalance + number_format($requestAmount, 2);
            $vcard->balance -= number_format($requestAmount, 2);
        } else if ($request->type == 'D') {
            $newBalance = $currentBalance - number_format($requestAmount, 2);
            $vcard->balance += number_format($requestAmount, 2);
        }

        $newPiggyBank = array();
        $newPiggyBank["balance"] = number_format($newBalance, 2);

        $json = json_encode($newPiggyBank);
        $vcard->custom_data = $json;
        $vcard->save();

        return $newBalance;
    }

    public function changePassword(Vcard $vcard, PasswordPut $request)
    {

        if (!Hash::check($request->old_password, $vcard->password)) {
            throw ValidationException::withMessages(['password' => "Confirmation of password failed"]);
        }
        if ($request->old_password == $request->password) {
            throw ValidationException::withMessages(['password' => "Old password and new password cannot be equal"]);
        }
        $vcard->password = Hash::make($request->password);
        $vcard->save();
        return $vcard;
    }

    public function changeConfirmationCode(Vcard $vcard, ConfirmationCodePut $request)
    {

        if (!Hash::check($request->old_password, $vcard->password)) {
            throw ValidationException::withMessages(['password' => "Confirmation of password failed"]);
        }

        if (Hash::check($request->confirmation_code, $vcard->confirmation_code)) {
            throw ValidationException::withMessages(['confirmation_code' => "Old confirmation code and new confirmation code cannot be equal"]);
        }
        $vcard->confirmation_code = Hash::make($request->confirmation_code);
        $vcard->save();
        return $vcard;
    }

    public function getNotifications(Vcard $vcard)
    {
        $notifications =  json_decode($vcard->custom_options);
        if ($notifications->notificationList == null) {
            throw ValidationException::withMessages(['notifications' => "There are no notifications"]);
        }
        return $notifications->notificationList;
    }

    public function changeNotificationState(Vcard $vcard)
    {
        $notifications =  json_decode($vcard->custom_options);
        if ($notifications->notificationState == "false") {
            $notifications->notificationState = "true";
        } else {
            $notifications->notificationState = "false";
        }
        $json = json_encode($notifications);
        $vcard->custom_options = $json;
        $vcard->save();

        return $notifications->notificationState;
    }

    
    public function notificationsState(Vcard $vcard)
    {
        if ($vcard->custom_options == null) {
            $notifications = array();
            $notifications["notificationList"] = array();
            $notifications["notificationState"] = true;

            $json = json_encode($notifications);
            $vcard->custom_options = $json;
            $vcard->save();
        }

        $notifications =  json_decode($vcard->custom_options);
        return $notifications->notificationState;
    }

    public function newNotification(Vcard $vcard, Request $request)
    {
        if ($vcard->custom_options == null) {
            $notifications = array();
            $notifications["notificationList"] = array();
            $notifications["notificationState"] = false;
            $json = json_encode($notifications);
            $vcard->custom_options = $json;
            $vcard->save();
        }

        $notifications =  json_decode($vcard->custom_options);
        if ($notifications->notificationList == null) {
            $id = 0;
        } else {
            $id = count($notifications->notificationList);
        }

        $newNotification = array();
        $newNotification["id"] = $id;
        $newNotification["message"] = $request->message;
        $newNotification["readed"] = false;
        $newNotification["date"] = date("Y/m/d H:i:s");
        $newNotification["viewed"] = false;


        array_push($notifications->notificationList, $newNotification);

        $json = json_encode($notifications);
        $vcard->custom_options = $json;
        $vcard->save();

        return $notifications;
    }


    public function readNotification(Vcard $vcard, Request $request)
    {
        $notifications =  json_decode($vcard->custom_options);
        if ($notifications->notificationList == null) {
            throw ValidationException::withMessages(['notifications' => "There are no notifications"]);
        }
        $id = $request->id;

        if ($notifications->notificationList[$id]->readed == false) {
            $notifications->notificationList[$id]->readed = true;
        } else {
            $notifications->notificationList[$id]->readed = false;
        }

        $json = json_encode($notifications);
        $vcard->custom_options = $json;
        $vcard->save();

        return $notifications;
    }

    public function viewNotifications(Vcard $vcard)
    {
        $notifications =  json_decode($vcard->custom_options);
        for ($i = 0; $i < count($notifications->notificationList); $i++) {
            $notifications->notificationList[$i]->viewed = true;
        }

        $json = json_encode($notifications);
        $vcard->custom_options = $json;
        $vcard->save();

        return $notifications;
    }

    public function countNonviewNotifications(Vcard $vcard)
    {
        $notifications =  json_decode($vcard->custom_options);
        if ($notifications == null) {
            return 0;
        }

        $count = 0;
        for ($i = 0; $i < count($notifications->notificationList); $i++) {
            if ($notifications->notificationList[$i]->viewed == false) {
                $count++;
            }
        }
        return $count;
    }
}
