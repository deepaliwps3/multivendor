<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Services\Backend\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    /**
     * Inject PaymentService dependency.
     */
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Display a listing of the payments using Yajra DataTables server-side handling.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = $this->paymentService->getPaymentsQuery();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('payer', function ($row) {
                    return $row->payer?->name ?? '-';
                })
                ->editColumn('payee', function ($row) {
                    return $row->payee?->name ?? '-';
                })
                ->editColumn('amount', function ($row) {
                    return number_format((float) $row->amount, 2);
                })
                ->editColumn('status', function ($row) {
                    $badges = [
                        'pending'  => 'bg-warning',
                        'released' => 'bg-success',
                        'failed'   => 'bg-danger',
                        'refunded' => 'bg-secondary',
                    ];
                    $class = $badges[$row->status] ?? 'bg-secondary';

                    return '<span class="badge ' . $class . '">' . ucfirst($row->status) . '</span>';
                })
                ->editColumn('released_at', function ($row) {
                    return $row->released_at?->format('d M Y, h:i A') ?? '-';
                })
                ->addColumn('actions', function ($row) {
                    $editBtn = '<button class="btn btn-sm btn-info me-1 edit-payment-btn" data-id="' . $row->id . '" data-order_stage_id="' . $row->order_stage_id . '" data-payer_id="' . $row->payer_id . '" data-payee_id="' . $row->payee_id . '" data-amount="' . $row->amount . '" data-status="' . $row->status . '" data-released_at="' . $row->released_at . '">
                                    <i data-feather="edit-2" class="feather-icon"></i> Edit
                                </button>';
                    $deleteUrl = route('payments.destroy', $row->id);
                    $deleteBtn = '<button class="btn btn-sm btn-danger delete-payment-btn" data-url="' . $deleteUrl . '">
                                    <i data-feather="trash-2" class="feather-icon"></i> Delete
                                </button>';

                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('backend.payments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created payment in storage using DB transaction service.
     */
    public function store(PaymentRequest $request): RedirectResponse|PaymentResource|JsonResponse
    {
        try {
            $payment = $this->paymentService->createPayment($request->validated());

            if ($request->wantsJson()) {
                return new PaymentResource($payment);
            }

            return redirect()->route('payments.index')->with('success', 'Payment created successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to create payment.'], 500);
            }

            return back()->withInput()->with('error', 'Failed to create payment. Please try again.');
        }
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment): PaymentResource
    {
        return new PaymentResource($payment->load(['orderStage', 'payer', 'payee']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified payment in storage using DB transaction service.
     */
    public function update(PaymentRequest $request, Payment $payment): RedirectResponse|PaymentResource|JsonResponse
    {
        try {
            $updatedPayment = $this->paymentService->updatePayment($payment, $request->validated());

            if ($request->wantsJson()) {
                return new PaymentResource($updatedPayment);
            }

            return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to update payment.'], 500);
            }

            return back()->withInput()->with('error', 'Failed to update payment. Please try again.');
        }
    }

    /**
     * Remove the specified payment from storage using DB transaction service.
     */
    public function destroy(Request $request, Payment $payment): RedirectResponse|JsonResponse
    {
        try {
            $this->paymentService->deletePayment($payment);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Payment deleted successfully.']);
            }

            return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
        } catch (Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Failed to delete payment.'], 500);
            }

            return back()->with('error', 'Failed to delete payment. Please try again.');
        }
    }
}
