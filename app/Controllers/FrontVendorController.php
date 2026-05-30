<?php

class FrontVendorController extends Controller
{
    public function index()
    {
        $this->frontView('frontend/vendor/register', [
            'title' => t('Daftar vendor - Iventlo Event Organizer', 'Vendor registration - Iventlo Event Organizer'),
            'meta_description' => t(
                'Daftar sebagai vendor Iventlo untuk melengkapi profil usaha dan menawarkan produk atau layanan event.',
                'Register as an Iventlo vendor to complete your business profile and offer event products or services.'
            ),
            'meta_robots' => 'index, follow, max-image-preview:large',
        ]);
    }

    public function store()
    {
        $this->ensureVendorProductTable();

        $vendorName = trim($_POST['vendor_name'] ?? '');
        $picName = trim($_POST['pic_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if ($vendorName === '' || $picName === '' || $email === '' || $phone === '') {
            $_SESSION['error'] = t(
                'Nama vendor, PIC, email, dan nomor telepon wajib diisi.',
                'Vendor name, PIC, email, and phone number are required.'
            );
            header('Location: ' . frontUrl('vendor-register'));
            exit;
        }

        $db = Database::connect();
        $vendorCode = 'VDR-PUBLIC-' . date('YmdHis');

        $stmt = $db->prepare("
            INSERT INTO vendors
                (vendor_code, vendor_name, phone, email, address, npwp, pic_name, notes, is_active)
            VALUES
                (:vendor_code, :vendor_name, :phone, :email, :address, :npwp, :pic_name, :notes, 0)
        ");
        $stmt->execute([
            ':vendor_code' => $vendorCode,
            ':vendor_name' => $vendorName,
            ':phone' => $phone,
            ':email' => $email,
            ':address' => trim($_POST['address'] ?? ''),
            ':npwp' => trim($_POST['npwp'] ?? ''),
            ':pic_name' => $picName,
            ':notes' => trim('Pendaftaran mandiri dari website. ' . ($_POST['business_profile'] ?? '')),
        ]);

        $vendorId = (int) $db->lastInsertId();
        $productName = trim($_POST['product_name'] ?? '');

        if ($productName !== '') {
            $product = $db->prepare("
                INSERT INTO vendor_public_products
                    (vendor_id, product_name, category, description, price, unit, status, created_at, updated_at)
                VALUES
                    (:vendor_id, :product_name, :category, :description, :price, :unit, 'pending', NOW(), NOW())
            ");
            $product->execute([
                ':vendor_id' => $vendorId,
                ':product_name' => $productName,
                ':category' => trim($_POST['product_category'] ?? ''),
                ':description' => trim($_POST['product_description'] ?? ''),
                ':price' => (float) preg_replace('/[^0-9.]/', '', $_POST['price'] ?? '0'),
                ':unit' => trim($_POST['unit'] ?? 'project'),
            ]);
        }

        $_SESSION['success'] = t(
            'Pendaftaran vendor berhasil dikirim. Tim Iventlo akan melakukan review sebelum vendor aktif.',
            'Vendor registration has been submitted. The Iventlo team will review it before activation.'
        );

        header('Location: ' . frontUrl('vendor-register'));
        exit;
    }

    private function ensureVendorProductTable()
    {
        $db = Database::connect();
        $db->exec("
            CREATE TABLE IF NOT EXISTS vendor_public_products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                vendor_id INT NOT NULL,
                product_name VARCHAR(180) NOT NULL,
                category VARCHAR(120) NULL,
                description TEXT NULL,
                price DECIMAL(15,2) NOT NULL DEFAULT 0,
                unit VARCHAR(80) NULL,
                status ENUM('pending','active','inactive') NOT NULL DEFAULT 'pending',
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                INDEX idx_vendor_public_products_vendor_id (vendor_id),
                INDEX idx_vendor_public_products_status (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
}
