<?php

namespace Modules\Order\Http\Controllers;

use Modules\Core\Http\Controllers\Controller;
use Modules\Order\Services\OrderService;
use Modules\Core\Enums\PermissionEnum;
use Modules\Order\Http\Requests\StoreOrderRequest;
use Modules\Order\Http\Requests\RenewOrderRequest;
use Illuminate\Support\Facades\Log;
use Modules\Core\Enums\TemplateCodeEnum;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
    ) {}

    /**
     * Display active orders
     */
    public function activeOrders()
    {
        can(PermissionEnum::ORDER_VIEW);
        set_breadcrumbs([
            [
                'title' => 'Dịch vụ đang sử dụng',
                'url' => null,
            ],
        ]);

        if (request()->has('customer_id')) {
            $orders = $this->orderService->getActiveOrders(request()->get('customer_id'));
        } else {
            $orders = $this->orderService->getActiveOrders();
        }

        return view('order::active-orders', compact('orders'));
    }

    /**
     * Display expiring orders
     */
    public function expiringOrders()
    {
        can(PermissionEnum::ORDER_VIEW);
        set_breadcrumbs([
            [
                'title' => 'Dịch vụ sắp hết hạn',
                'url' => null,
            ],
        ]);

        if (request()->has('customer_id')) {
            $orders = $this->orderService->getExpiringOrders(request()->get('customer_id'));
        } else {
            $orders = $this->orderService->getExpiringOrders();
        }

        return view('order::expiring-orders', compact('orders'));
    }

    /**
     * Display expired orders
     */
    public function expiredOrders()
    {
        can(PermissionEnum::ORDER_VIEW);
        set_breadcrumbs([
            [
                'title' => 'Dịch vụ đã hết hạn',
                'url' => null,
            ],
        ]);

        if (request()->has('customer_id')) {
            $orders = $this->orderService->getExpiredOrders(request()->get('customer_id'));
        } else {
            $orders = $this->orderService->getExpiredOrders();
        }

        return view('order::expired-orders', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        can(PermissionEnum::ORDER_CREATE);
        set_breadcrumbs([
            [
                'title' => 'Thêm mới dịch vụ',
                'url' => null,
            ],
        ]);

        $categories = $this->orderService->getCategoriesForCreate();
        $customers = $this->orderService->getCustomersForCreate();

        return view('order::create', compact('categories', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        can(PermissionEnum::ORDER_CREATE);

        try {
            $this->orderService->createOrder($request->validated());
            return redirect()->route('orders.active')->with('success', 'Dịch vụ đã được tạo thành công');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra. Vui lòng thử lại!');
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($orderId, $orderServiceId)
    {
        can(PermissionEnum::ORDER_VIEW);

        $order = $this->orderService->getOrderById($orderId, $orderServiceId);
        $orderService = $order->orderServices->where('id', $orderServiceId)->first();

        return view('order::show', compact('order', 'orderService'));
    }

    /**
     * Show renew service form
     */
    public function renew($orderId, $orderServiceId)
    {
        can(PermissionEnum::ORDER_CREATE);
        set_breadcrumbs([
            [
                'title' => 'Dịch vụ đang sử dụng',
                'url' => route('orders.active'),
            ],
            [
                'title' => 'Gia hạn dịch vụ',
                'url' => null,
            ],
        ]);


        $order = $this->orderService->getOrderById($orderId, $orderServiceId);
        $orderService = $order->orderServices->where('id', $orderServiceId)->first();
        $code = generate_code(TemplateCodeEnum::ORDER, 'orders');

        if (!$orderService) {
            return redirect()->route('orders.active')->with('error', 'Không tìm thấy dịch vụ!');
        }

        $services = $this->orderService->getServicesWithProducts();

        return view('order::renew', compact('order', 'orderService', 'services', 'code'));
    }

    /**
     * Store renewed order services
     */
    public function renewUpdate($orderId, $orderServiceId, RenewOrderRequest $request)
    {
        can(PermissionEnum::ORDER_CREATE);

        try {
            $order = $this->orderService->getOrderById($orderId);
            $orderService = $this->orderService->getOrderServiceById($orderServiceId);

            if (!$order || !$orderService) {
                return redirect()->route('orders.active')->with('error', 'Không tìm thấy đơn hàng hoặc dịch vụ!');
            }

            $this->orderService->renewOrderServices($request->validated());

            return redirect()->route('orders.active')->with('success', 'Gia hạn dịch vụ thành công!');
        } catch (\Exception $e) {
            Log::error('Error in renewUpdate: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi gia hạn dịch vụ. Vui lòng thử lại!');
        }
    }
}
