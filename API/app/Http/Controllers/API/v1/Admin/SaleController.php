<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateSaleRequest;
use App\Http\Requests\Admin\SaleUpdateRequest;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function __construct(
        protected SaleService $saleService
    ) {}

    public function store(CreateSaleRequest $request)
    {
        $user = Auth::user();
        $sale = $this->saleService->create($request->validated(), $user);
        return ApiResponse::success($sale, 'Sale created successfully!', 201);
    }

    public function index()
    {
        $sales = $this->saleService->allPaginated();
        return ApiResponse::success($sales, 'Sales retrieved successfully');
    }

    public function show($id){
        $sale = $this->saleService->find($id);
        return ApiResponse::success($sale, 'Sale retrieved successfully');
    }

    public function update(SaleUpdateRequest $request, Sale $id)
    {
        $sale = $this->saleService->update($id, $request->validated());
        return ApiResponse::success($sale,'Sale updated successfully!');
    }

    public function destroy(Sale $id)
    {
        $this->saleService->delete($id);
        return ApiResponse::success(null, 'Sale deleted successfully');
    }

    public function toggleActive(Sale $id)
    {
        $sale = $this->saleService->toggleActive($id);
        return ApiResponse::success($sale, 'Sale status toggled successfully');
    }
}
