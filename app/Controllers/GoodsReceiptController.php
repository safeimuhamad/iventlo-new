<?php

class GoodsReceiptController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('goods_receipt.view');

        $search = trim($_GET['search'] ?? '');

        $model = new GoodsReceipt();

        $limit = 10;
        $currentPage = max(1, (int) ($_GET['p'] ?? 1));
        $offset = ($currentPage - 1) * $limit;

        activity_log(
            'Purchasing - Goods Receipt',
            'view',
            'Melihat daftar penerimaan barang'
        );

        $status = $_GET['status'] ?? '';

        $totalData = $model->countAll($search, $status);
        $totalPages = (int) ceil($totalData / $limit);

        $this->view('goods-receipts/index', [
            'title' => 'Penerimaan Barang',
            'items' => $model->getPaginated($limit, $offset, $search, $status),
            'search' => $search,
            'status' => $status,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalData' => $totalData,
            'limit' => $limit
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('goods_receipt.create');

        $model = new GoodsReceipt();

        $purchaseOrderId = $_GET['purchase_order_id'] ?? null;
        $purchaseOrder = null;
        $purchaseOrderItems = [];

        if ($purchaseOrderId) {
            $poModel = new PurchaseOrder();

            $purchaseOrder = $poModel->find($purchaseOrderId);
            $purchaseOrderItems = $model->getPurchaseOrderItems($purchaseOrderId);

            if (!$purchaseOrder) {
                $_SESSION['error'] = 'Purchase order tidak ditemukan.';
                $this->redirect('goods-receipts-create');
            }
        }

        activity_log(
            'Purchasing - Goods Receipt',
            'create_form',
            'Membuka form penerimaan barang'
        );

        $this->view('goods-receipts/create', [
            'title' => 'Tambah Penerimaan Barang',
            'receiptNumber' => $model->generateNumber(),
            'purchaseOrders' => $model->getReceivablePurchaseOrders(),
            'purchaseOrder' => $purchaseOrder,
            'purchaseOrderItems' => $purchaseOrderItems,
            'purchaseOrderId' => $purchaseOrderId
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('goods_receipt.create');

        $purchaseOrderId = $_POST['purchase_order_id'] ?? null;

        if (!$purchaseOrderId) {
            $_SESSION['error'] = 'Purchase order wajib dipilih.';
            $this->redirect('goods-receipts-create');
        }

        $parseQty = function ($value) {
            $value = trim((string) ($value ?? '0'));

            if ($value === '') {
                return 0;
            }

            return (float) str_replace(',', '.', $value);
        };

        $grModel = new GoodsReceipt();
        $poModel = new PurchaseOrder();

        $purchaseOrder = $poModel->find($purchaseOrderId);

        if (!$purchaseOrder) {
            $_SESSION['error'] = 'Purchase order tidak ditemukan.';
            $this->redirect('goods-receipts-create');
        }

        $poItemIds = $_POST['purchase_order_item_id'] ?? [];
        $itemNames = $_POST['item_name'] ?? [];
        $qtyOrdered = $_POST['qty_ordered'] ?? [];
        $qtyReceived = $_POST['qty_received'] ?? [];
        $unitNames = $_POST['unit_name'] ?? [];
        $notes = $_POST['item_notes'] ?? [];

        $receiptNumber = $_POST['receipt_number'] ?? $grModel->generateNumber();

        $receiptId = $grModel->create([
            'receipt_number' => $receiptNumber,
            'purchase_order_id' => $purchaseOrderId,
            'receipt_date' => $_POST['receipt_date'] ?? date('Y-m-d'),
            'status' => 'received',
            'notes' => $_POST['notes'] ?? '',
            'created_by' => $_SESSION['user_id'] ?? null
        ]);

        foreach ($poItemIds as $index => $poItemId) {
            $received = $parseQty($qtyReceived[$index] ?? 0);

            if ($received <= 0) {
                continue;
            }

            $ordered = $parseQty($qtyOrdered[$index] ?? 0);

            $grModel->addItem($receiptId, [
                'purchase_order_item_id' => $poItemId,
                'item_name' => $itemNames[$index] ?? '',
                'qty_ordered' => $ordered,
                'qty_received' => $received,
                'unit_name' => $unitNames[$index] ?? 'unit',
                'notes' => $notes[$index] ?? ''
            ]);

            $poModel->updateReceivedQty($poItemId, $received);
        }

        $poModel->updateStatusByReceive($purchaseOrderId);

        activity_log(
            'Purchasing - Goods Receipt',
            'create',
            'Membuat penerimaan barang: ' . $receiptNumber,
            $receiptId,
            $receiptNumber
        );

        $_SESSION['success'] = 'Penerimaan barang berhasil dibuat.';

        $this->redirect('goods-receipts-show', ['id' => $receiptId]);
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('goods_receipt.view');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('goods-receipts');
        }

        $model = new GoodsReceipt();

        $item = $model->find($id);

        if (!$item) {
            activity_log(
                'Purchasing - Goods Receipt',
                'view_failed',
                'Gagal membuka detail penerimaan barang karena data tidak ditemukan',
                $id
            );

            $_SESSION['error'] = 'Penerimaan barang tidak ditemukan.';
            $this->redirect('goods-receipts');
        }

        activity_log(
            'Purchasing - Goods Receipt',
            'view',
            'Melihat detail penerimaan barang: ' . ($item['receipt_number'] ?? '-'),
            $id,
            $item['receipt_number'] ?? null
        );

        $this->view('goods-receipts/show', [
            'title' => 'Detail Penerimaan Barang',
            'item' => $item,
            'items' => $model->getItems($id)
        ]);
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        requirePermission('goods_receipt.delete');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('goods-receipts');
        }

        $grModel = new GoodsReceipt();
        $poModel = new PurchaseOrder();

        $item = $grModel->find($id);

        if (!$item) {
            $_SESSION['error'] = 'Penerimaan barang tidak ditemukan.';
            $this->redirect('goods-receipts');
        }

        $receiptItems = $grModel->getItems($id);

        foreach ($receiptItems as $receiptItem) {
            $poModel->decreaseReceivedQty(
                $receiptItem['purchase_order_item_id'],
                (float) ($receiptItem['qty_received'] ?? 0)
            );
        }

        $grModel->delete($id);

        $poModel->updateStatusByReceive($item['purchase_order_id']);

        activity_log(
            'Purchasing - Goods Receipt',
            'delete',
            'Menghapus penerimaan barang dan rollback qty PO: ' . ($item['receipt_number'] ?? '-'),
            $id,
            $item['receipt_number'] ?? null
        );

        $_SESSION['success'] = 'Penerimaan barang berhasil dihapus dan qty PO dikembalikan.';

        $this->redirect('goods-receipts');
    }
}