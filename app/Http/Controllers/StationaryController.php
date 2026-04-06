<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStationaryPurchaseRequest;
use App\Http\Requests\CreateStationaryRequest;
use App\Http\Requests\CreateStationarySaleRequest;
use App\Http\Requests\CreateStationarySupplierRequest;
use App\Services\StationaryService;
use Illuminate\Http\Request;

class StationaryController extends Controller
{
    public function __construct(private readonly StationaryService $stationaryService) {}

    public function index(Request $request)
    {
        return $this->stationaryService->index($request);
    }

    public function create(CreateStationaryRequest $createStationaryRequest)
    {
        return $this->stationaryService->create($createStationaryRequest);
    }

    public function show($id)
    {
        return $this->stationaryService->show($id);
    }

    public function update($id, Request $request)
    {
        return $this->stationaryService->update($id, $request);
    }

    public function delete($id)
    {
        return $this->stationaryService->delete($id);
    }

    // Sales
    public function getStationarySales(Request $request)
    {
        return $this->stationaryService->getStationarySales($request);
    }

    public function createStationarySale(CreateStationarySaleRequest $createStationarySaleRequest)
    {
        return $this->stationaryService->createStationarySale($createStationarySaleRequest);
    }

    public function getSingleSale($id)
    {
        return $this->stationaryService->getSingleSale($id);
    }

    public function deleteSale($id)
    {
        return $this->stationaryService->deleteSale($id);
    }

    // Supplier
    public function addStationarySupplier(CreateStationarySupplierRequest $createStationarySupplierRequest)
    {
        return $this->stationaryService->addStationarySupplier($createStationarySupplierRequest);
    }

    public function getStationarySuppliers(Request $request)
    {
        return $this->stationaryService->getStationarySuppliers($request);
    }

    public function getSingleSupplier($id)
    {
        return $this->stationaryService->getSingleSupplier($id);
    }

    public function updateSupplier($id, Request $request)
    {
        return $this->stationaryService->updateSupplier($id, $request);
    }

    public function deleteSupplier($id)
    {
        return $this->stationaryService->deleteSupplier($id);
    }

    // Stationary
    public function addStationaryPurchase(CreateStationaryPurchaseRequest $createStationaryPurchaseRequest)
    {
        return $this->stationaryService->addStationaryPurchase($createStationaryPurchaseRequest);
    }

    public function getStationaryPurchases(Request $request)
    {
        return $this->stationaryService->getStationaryPurchases($request);
    }

    public function getSinglePurchase($id)
    {
        return $this->stationaryService->getSinglePurchase($id);
    }

    public function updatePurchase($id, Request $request)
    {
        return $this->stationaryService->updatePurchase($id, $request);
    }

    public function deletePurchase($id)
    {
        return $this->stationaryService->deletePurchase($id);
    }

    // Report
    public function getReport(Request $request)
    {
        return $this->stationaryService->getReport($request);
    }
}
