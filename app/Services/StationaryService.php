<?php

namespace App\Services;

use App\Http\Resources\StationaryPurchaseResource;
use App\Models\Stationary;
use App\Traits\HttpResponses;
use App\Http\Resources\StationaryResource;
use App\Http\Resources\StationarySaleResource;
use App\Models\StationaryPurchase;
use App\Models\StationarySale;
use App\Models\StationarySupplier;
use Illuminate\Support\Facades\DB;

class StationaryService
{
    use HttpResponses;

    public function index($request)
    {
        $schId = $request->query('sch_id');
        $campus = $request->query('campus');

        if (blank($schId) || blank($campus)) {
            return $this->error(null, 'School id & Campus not found', 404);
        }

        $stationaries = Stationary::with(['stationarySales', 'stationaryPurchases'])
            ->where('sch_id', $schId)
            ->where('campus', $campus)
            ->paginate(25);

        $data = StationaryResource::collection($stationaries);

        return $this->withPagination($data, 'Stationaries list');
    }

    public function create($request)
    {
        $user = userAuth();
        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

        if ($request->hasFile('image')) {
            $imagePath = uploadImage($request->image, 'stationary', $cleanSchId);
        }

        $uniqueId = generateUniqueId();

        Stationary::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'name' => $request->name,
            'unique_id' => $uniqueId,
            'cost_price' => $request->cost_price,
            'selling_price' => $request->selling_price,
            'quantity' => $request->quantity,
            'image' => $imagePath['url'] ?? null,
            'image_id' => $imagePath['file_id'] ?? null,
        ]);

        return $this->success(null, 'Created successfully', 201);
    }

    public function show($id)
    {
        $user = userAuth();

        $stationary = Stationary::with(['stationarySales', 'stationaryPurchases'])
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $id)
            ->first();

        if (! $stationary) {
            return $this->error(null, 'Stationary not found', 404);
        }

        return $this->success(new StationaryResource($stationary), 'Stationary detail');
    }

    public function update($id, $request)
    {
        $user = userAuth();

        $stationary = Stationary::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $id)
            ->first();

        if (! $stationary) {
            return $this->error(null, 'Stationary not found', 404);
        }

        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

        if ($request->hasFile('image')) {
            $imagePath = uploadImage($request->image, 'stationary', $cleanSchId);
        }

        $stationary->update([
            'name' => $request->name ?? $stationary->name,
            'cost_price' => $request->cost_price ?? $stationary->cost_price,
            'selling_price' => $request->selling_price ?? $stationary->selling_price,
            'quantity' => $request->quantity ?? $stationary->quantity,
            'image' => $imagePath['url'] ?? $stationary->image,
            'image_id' => $imagePath['file_id'] ?? $stationary->image_id,
        ]);

        return $this->success(null, 'Stationary updated successfully');
    }

    public function delete($id)
    {
        $user = userAuth();

        $stationary = Stationary::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $id)
            ->first();

        if (! $stationary) {
            return $this->error(null, 'Stationary not found', 404);
        }

        $stationary->delete();

        return $this->success(null, 'Stationary deleted successfully');
    }

    public function getStationarySales($request)
    {
        $schId = $request->query('sch_id');
        $campus = $request->query('campus');

        if (blank($schId) || blank($campus)) {
            return $this->error(null, 'School id & Campus not found', 404);
        }

        $stationarySales = StationarySale::with(['class', 'student'])
            ->where('sch_id', $schId)
            ->where('campus', $campus)
            ->paginate(25);

        $data = StationarySaleResource::collection($stationarySales);

        return $this->withPagination($data, 'Stationaries sales list');
    }

    public function createStationarySale($request)
    {
        $user = userAuth();

        if (blank($request->sales)) {
            return $this->error(null, 'No sales data provided.');
        }

        try {
            return DB::transaction(function () use ($request, $user) {
                foreach ($request->sales as $saleData) {

                    $stationary = Stationary::where('sch_id', $user->sch_id)
                        ->where('campus', $user->campus)
                        ->where('id', $saleData['stationary_id'])
                        ->lockForUpdate()
                        ->first();

                    if (! $stationary) {
                        throw new \Exception('Stationary not found!');
                    }

                    if ($stationary->quantity < $saleData['quantity']) {
                        throw new \Exception('Insufficient stock');
                    }

                    StationarySale::create([
                        'sch_id' => $user->sch_id,
                        'campus' => $user->campus,
                        'stationary_id' => $saleData['stationary_id'],
                        'class_id' => $saleData['class_id'],
                        'student_id' => $saleData['student_id'],
                        'date' => $saleData['date'],
                        'quantity' => $saleData['quantity']
                    ]);

                    $stationary->decrement('quantity', $saleData['quantity']);
                }

                return $this->success(null, 'Created successfully', 201);
            });
        } catch (\Exception $e) {
            return $this->error(null, "An error occured: {$e->getMessage()}", 400);
        }
    }

    public function getSingleSale($id)
    {
        $user = userAuth();

        $stationarySale = StationarySale::with(['class', 'student'])
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $id)
            ->first();

        if (! $stationarySale) {
            return $this->error(null, 'Stationary sale not found', 404);
        }

        return $this->success(new StationarySaleResource($stationarySale), 'Stationary sale detail');
    }

    public function deleteSale($id)
    {
        $user = userAuth();

        $stationarySale = StationarySale::with(['class', 'student'])
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $id)
            ->first();

        if (! $stationarySale) {
            return $this->error(null, 'Stationary sale not found', 404);
        }

        try {
            return DB::transaction(function () use ($user, $stationarySale) {
                $stationary = Stationary::where('sch_id', $user->sch_id)
                    ->where('campus', $user->campus)
                    ->where('id', $stationarySale->stationary_id)
                    ->lockForUpdate()
                    ->first();

                $stationary?->increment('quantity', $stationarySale->quantity);

                $stationarySale->delete();

                return $this->success(null, 'Stationary sale deleted');
            });
        } catch (\Exception $e) {
            return $this->error(null, "An error occurred: {$e->getMessage()}", 400);
        }
    }

    // Supplier
    public function addStationarySupplier($request)
    {
        $user = userAuth();

        StationarySupplier::create([
            ...$request->validated(),
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
        ]);

        return $this->success(null, 'Created successfully', 201);
    }

    public function getStationarySuppliers($request)
    {
        $schId = $request->query('sch_id');
        $campus = $request->query('campus');

        if (blank($schId) || blank($campus)) {
            return $this->error(null, 'School id & Campus not found', 404);
        }

        $suppliers = StationarySupplier::where('sch_id', $schId)
            ->where('campus', $campus)
            ->paginate(25);

        $suppliers->getCollection()->transform(function ($supplier) {
            return [
                'id' => $supplier->id,
                'first_name' => $supplier->first_name,
                'last_name' => $supplier->last_name,
                'phone_number' => $supplier->phone_number,
                'address' => $supplier->address,
                'amount_owed' => $supplier->amount_owed,
                'amount_paid' => $supplier->amount_paid,
                'created_at' => $supplier->created_at->toDateString(),
            ];
        });

        return $this->withPagination($suppliers, 'Stationary suppliers list');
    }

    public function getSingleSupplier($id)
    {
        $user = userAuth();

        $supplier = StationarySupplier::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $id)
            ->first();

        if (! $supplier) {
            return $this->error(null, 'Supplier not found', 404);
        }

        $data = [
            'id' => $supplier->id,
            'sch_id' => $supplier->sch_id,
            'campus' => $supplier->campus,
            'first_name' => $supplier->first_name,
            'last_name' => $supplier->last_name,
            'phone_number' => $supplier->phone_number,
            'address' => $supplier->address,
            'amount_owed' => $supplier->amount_owed,
            'amount_paid' => $supplier->amount_paid,
            'created_at' => $supplier->created_at->toDateString(),
        ];

        return $this->success($data, 'Supplier detail');
    }

    public function updateSupplier($id, $request)
    {
        $user = userAuth();

        $supplier = StationarySupplier::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $id)
            ->first();

        if (! $supplier) {
            return $this->error(null, 'Supplier not found', 404);
        }

        $supplier->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'amount_owed' => $request->amount_owed,
            'amount_paid' => $request->amount_paid,
        ]);

        return $this->success(null, 'Supplier detail updated successfully');
    }

    public function deleteSupplier($id)
    {
        $user = userAuth();

        $supplier = StationarySupplier::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $id)
            ->first();

        if (! $supplier) {
            return $this->error(null, 'Supplier not found', 404);
        }

        $supplier->delete();

        return $this->success(null, 'Supplier deleted');
    }

    // Purchase
    public function addStationaryPurchase($request)
    {
        $user = userAuth();

        $items = $request->items;

        $stationaryIds = collect($items)->pluck('stationary_id')->unique();

        $stationaryItems = Stationary::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->whereIn('id', $stationaryIds)
            ->get()
            ->keyBy('id');

        foreach ($stationaryIds as $id) {
            if (! $stationaryItems->has($id)) {
                return $this->error(null, "Stationary with ID {$id} not found", 404);
            }
        }

        try {
            return DB::transaction(function () use ($user, $items, $stationaryItems) {
                $now = now();
                $purchases = [];

                foreach ($items as $item) {
                    $purchases[] = [
                        'sch_id' => $user->sch_id,
                        'campus' => $user->campus,
                        'stationary_supplier_id' => $item['stationary_supplier_id'],
                        'date_supplied' => $item['date_supplied'],
                        'stationary_id' => $item['stationary_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                StationaryPurchase::insert($purchases);

                foreach ($items as $item) {
                    $stationaryItems[$item['stationary_id']]->increment('quantity', $item['quantity']);
                }

                return $this->success(null, 'Created successfully', 201);
            });
        } catch (\Exception $e) {
            return $this->error(null, "An error occured: {$e->getMessage()}", 400);
        }
    }

    public function getStationaryPurchases($request)
    {
        $schId = $request->query('sch_id');
        $campus = $request->query('campus');

        if (blank($schId) || blank($campus)) {
            return $this->error(null, 'School id & Campus not found', 404);
        }

        $stationaryPurchases = StationaryPurchase::with(['stationarySupplier'])
            ->where('sch_id', $schId)
            ->where('campus', $campus)
            ->paginate(25);

        $data = StationaryPurchaseResource::collection($stationaryPurchases);

        return $this->withPagination($data, 'Stationaries purchase list');
    }

    public function getSinglePurchase($id)
    {
        $user = userAuth();

        $purchase = StationaryPurchase::with(['stationarySupplier'])
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $id)
            ->first();

        if (! $purchase) {
            return $this->error(null, 'Purchase not found', 404);
        }

        return $this->success(new StationaryPurchaseResource($purchase), 'Purchase list');
    }

    public function updatePurchase($id, $request)
    {
        $user = userAuth();

        $purchase = StationaryPurchase::with(['stationarySupplier'])
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $id)
            ->first();

        if (! $purchase) {
            return $this->error(null, 'Purchase not found', 404);
        }

        try {
            return DB::transaction(function () use ($user, $purchase, $request) {
                $oldStationary = Stationary::where('sch_id', $user->sch_id)
                    ->where('campus', $user->campus)
                    ->where('id', $purchase->stationary_id)
                    ->lockForUpdate()
                    ->first();

                $stationaryChanged = $request->stationary_id &&
                    $request->stationary_id != $purchase->stationary_id;

                if ($stationaryChanged) {
                    $oldStationary?->decrement('quantity', $purchase->quantity);

                    $newStationary = Stationary::where('sch_id', $user->sch_id)
                        ->where('campus', $user->campus)
                        ->where('id', $request->stationary_id)
                        ->lockForUpdate()
                        ->first();

                    if (! $newStationary) {
                        throw new \Exception('New stationary item not found.');
                    }

                    $newStationary->increment('quantity', $request->quantity ?? $purchase->quantity);
                } else {
                    $oldQty = $purchase->quantity;
                    $newQty = $request->quantity ?? $oldQty;
                    $delta  = $newQty - $oldQty;

                    if ($delta > 0) {
                        $oldStationary?->increment('quantity', $delta);
                    } elseif ($delta < 0) {
                        $oldStationary?->decrement('quantity', abs($delta));
                    }
                }

                $purchase->update([
                    'stationary_supplier_id' => $request->stationary_supplier_id ?? $purchase->stationary_supplier_id,
                    'date_supplied' => $request->date_supplied ?? $purchase->date_supplied,
                    'stationary_id' => $request->stationary_id ?? $purchase->stationary_id,
                    'quantity' => $request->quantity ?? $purchase->quantity,
                    'price' => $request->price ?? $purchase->price,
                ]);

                return $this->success(null, 'Purchase updated successfully');
            });
        } catch (\Exception $e) {
            return $this->error(null, "An error occurred: {$e->getMessage()}", 400);
        }
    }

    public function deletePurchase($id)
    {
        $user = userAuth();

        $purchase = StationaryPurchase::with(['stationarySupplier'])
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $id)
            ->first();

        if (! $purchase) {
            return $this->error(null, 'Purchase not found', 404);
        }

        try {
            return DB::transaction(function () use ($user, $purchase) {
                $stationary = Stationary::where('sch_id', $user->sch_id)
                    ->where('campus', $user->campus)
                    ->where('id', $purchase->stationary_id)
                    ->lockForUpdate()
                    ->first();

                $stationary?->decrement('quantity', $purchase->quantity);

                $purchase->delete();

                return $this->success(null, 'Purchase deleted successfully');
            });
        } catch (\Exception $e) {
            return $this->error(null, "An error occurred: {$e->getMessage()}", 400);
        }
    }

    // Report
    public function getReport($request)
    {
        $schId = $request->query('sch_id');
        $campus = $request->query('campus');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $stationaryId = $request->query('stationary_id');

        if (blank($schId) || blank($campus) || blank($startDate) || blank($endDate)) {
            return $this->error(null, 'School ID, Campus, Start date & End date are required', 422);
        }

        $query = Stationary::where('sch_id', $schId)
            ->where('campus', $campus);

        if (! blank($stationaryId)) {
            $query->where('id', $stationaryId);
        }

        $report = $query->paginate(25);

        $report->getCollection()->transform(function ($stationary) use ($startDate, $endDate) {

            $totalPurchasedInPeriod = StationaryPurchase::where('stationary_id', $stationary->id)
                ->whereBetween('date_supplied', [$startDate, $endDate])
                ->sum('quantity');

            $totalIssuedInPeriod = StationarySale::where('stationary_id', $stationary->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('quantity');

            $openingBalance = $stationary->quantity - $totalPurchasedInPeriod + $totalIssuedInPeriod;
            $availableBalance = $openingBalance + $totalPurchasedInPeriod;
            $actualBalance = $availableBalance - $totalIssuedInPeriod;

            return [
                'id' => $stationary->id,
                'name' => $stationary->name,
                'unique_id' => $stationary->unique_id,
                'opening_balance' => max(0, $openingBalance),
                'total_purchased' => $totalPurchasedInPeriod,
                'total_issued' => $totalIssuedInPeriod,
                'available_balance' => max(0, $availableBalance),
                'actual_balance' => max(0, $actualBalance),
                'current_quantity' => $stationary->quantity,
                'period' => [
                    'from' => $startDate,
                    'to' => $endDate,
                ],
            ];
        });

        return $this->withPagination($report, 'Stationary report generated successfully');
    }
}
