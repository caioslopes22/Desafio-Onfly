<?php

namespace App\Http\Controllers;

use App\Models\TravelRequest;
use App\Notifications\TravelRequestStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TravelRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $this->authorize('list', TravelRequest::class);
        $user = auth('api')->user();

        $query = TravelRequest::query();
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }

        if ($status = $request->query('status')) {
            $query->whereIn('status', (array)$status);
        }
        if ($destination = $request->query('destination')) {
            $query->where('destination', 'like', "%$destination%");
        }

        $dateField = $request->query('date_field', 'created'); // created|travel
        $from = $request->query('from');
        $to = $request->query('to');

        if ($from || $to) {
            if ($dateField === 'travel') {
                if ($from) $query->whereDate('departure_date', '>=', $from);
                if ($to)   $query->whereDate('return_date', '<=', $to);
            } else {
                if ($from) $query->whereDate('created_at', '>=', $from);
                if ($to)   $query->whereDate('created_at', '<=', $to);
            }
        }

        return $query->orderByDesc('id')->paginate(15);
    }

    public function show(TravelRequest $travelRequest)
    {
        $this->authorize('view', $travelRequest);
        return $travelRequest;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'requester_name' => ['required','string','max:255'],
            'destination' => ['required','string','max:255'],
            'departure_date' => ['required','date','after_or_equal:today'],
            'return_date' => ['required','date','after_or_equal:departure_date'],
        ]);

        $data['user_id'] = auth('api')->id();
        $data['status'] = 'solicitado';

        $travel = TravelRequest::create($data);
        return response()->json($travel, 201);
    }

    public function updateStatus(Request $request, TravelRequest $travelRequest)
    {
        $this->authorize('updateStatus', $travelRequest);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['aprovado','cancelado'])]
        ]);

        if ($validated['status'] === 'cancelado' && $travelRequest->status === 'aprovado') {
            return response()->json(['message' => 'Não é possível cancelar um pedido já aprovado.'], 422);
        }

        $travelRequest->update(['status' => $validated['status']]);

        $travelRequest->user->notify(new TravelRequestStatusChanged($travelRequest));

        return response()->json($travelRequest);
    }
}
