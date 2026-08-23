<?php

namespace App\Services\Backend;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PaymentService
{
    /**
     * Get all payments ordered by latest.
     */
    public function getAllPayments(): Collection
    {
        return Payment::with(['orderStage', 'payer', 'payee'])->latest()->get();
    }

    /**
     * Get payments query for Yajra DataTables.
     */
    public function getPaymentsQuery()
    {
        return Payment::query()->with(['orderStage', 'payer', 'payee'])->latest();
    }

    /**
     * Create a new payment using DB transactions.
     *
     * @throws Throwable
     */
    public function createPayment(array $data): Payment
    {
        DB::beginTransaction();

        try {
            $payment = Payment::create($data);
            DB::commit();

            return $payment;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to create payment: ' . $e->getMessage(), [
                'data'      => $data,
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing payment using DB transactions.
     *
     * @throws Throwable
     */
    public function updatePayment(Payment $payment, array $data): Payment
    {
        DB::beginTransaction();

        try {
            $payment->update($data);
            DB::commit();

            return $payment;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update payment ID ' . $payment->id . ': ' . $e->getMessage(), [
                'data'      => $data,
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a payment using DB transactions.
     *
     * @throws Throwable
     */
    public function deletePayment(Payment $payment): bool
    {
        DB::beginTransaction();

        try {
            $deleted = (bool) $payment->delete();
            DB::commit();

            return $deleted;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Failed to delete payment ID ' . $payment->id . ': ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
