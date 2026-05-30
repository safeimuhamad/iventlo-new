<?php $isEdit = !empty($event['id']); ?>
<div class="card bg-white rounded-10 border border-white mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20 border-bottom">
        <div>
            <h3 class="mb-0"><?= $isEdit ? 'Edit Master Event' : 'Tambah Master Event' ?></h3>
            <p class="text-body fs-14 mb-0">Informasi di bagian ini aman ditampilkan pada Client Portal.</p>
        </div>
        <a href="<?= url('master-events') ?>" class="btn btn-light erp-btn">Kembali</a>
    </div>
    <form method="POST" enctype="multipart/form-data" action="<?= url($isEdit ? 'master-events-update' : 'master-events-store') ?>" class="p-20">
        <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= (int) $event['id'] ?>"><?php endif; ?>
        <input type="hidden" name="existing_cover_image" value="<?= htmlspecialchars($event['cover_image'] ?? '') ?>">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Kode Event <span class="text-danger">*</span></label>
                <input name="event_code" class="form-control" required value="<?= htmlspecialchars($event['event_code'] ?? '') ?>">
            </div>
            <div class="col-md-8 mb-3">
                <label class="form-label">Nama Event <span class="text-danger">*</span></label>
                <input name="title" class="form-control" required value="<?= htmlspecialchars($event['title'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Event (English)</label>
                <input name="title_en" class="form-control" value="<?= htmlspecialchars($event['title_en'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Client <span class="text-danger">*</span></label>
                <input name="client_name" class="form-control" required value="<?= htmlspecialchars($event['client_name'] ?? '') ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <?php foreach (['planning' => 'Perencanaan', 'preparation' => 'Persiapan', 'on_going' => 'Berlangsung', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $value => $label): ?>
                        <option value="<?= $value ?>" <?= ($event['status'] ?? 'planning') === $value ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Progress (%)</label>
                <input type="number" name="progress" min="0" max="100" class="form-control" value="<?= (int) ($event['progress'] ?? 0) ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="event_date" class="form-control" value="<?= htmlspecialchars($event['event_date'] ?? '') ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($event['end_date'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Venue</label>
                <input name="venue" class="form-control" value="<?= htmlspecialchars($event['venue'] ?? '') ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Venue (English)</label>
                <input name="venue_en" class="form-control" value="<?= htmlspecialchars($event['venue_en'] ?? '') ?>">
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">Deskripsi untuk Client</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($event['description'] ?? '') ?></textarea>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">Deskripsi Publik (English)</label>
                <textarea name="description_en" class="form-control" rows="3"><?= htmlspecialchars($event['description_en'] ?? '') ?></textarea>
            </div>
        </div>
        <div class="border rounded-10 p-20 mb-3">
            <h4 class="mb-3">Event Publik & Tiket</h4>
            <div class="row">
                <div class="col-md-3 mb-3 pt-4">
                    <label class="d-block mb-2"><input type="checkbox" name="is_public" value="1" <?= !empty($event['is_public']) ? 'checked' : '' ?>> Tampilkan di website publik</label>
                    <label class="d-block"><input type="checkbox" name="is_paid" value="1" <?= !empty($event['is_paid']) ? 'checked' : '' ?>> Event berbayar</label>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Slug Indonesia</label>
                    <input name="public_slug" class="form-control" placeholder="nama-event" value="<?= htmlspecialchars($event['public_slug'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Slug English</label>
                    <input name="public_slug_en" class="form-control" placeholder="event-name" value="<?= htmlspecialchars($event['public_slug_en'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Status Penjualan</label>
                    <select name="ticket_sales_status" class="form-select">
                        <?php foreach (['closed' => 'Tutup', 'open' => 'Buka Penjualan', 'sold_out' => 'Sold Out'] as $value => $label): ?>
                            <option value="<?= $value ?>" <?= ($event['ticket_sales_status'] ?? 'closed') === $value ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Harga Tiket (Rp)</label>
                    <input type="number" name="ticket_price" min="0" class="form-control" value="<?= htmlspecialchars($event['ticket_price'] ?? 0) ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Kuota Peserta</label>
                    <input type="number" name="participant_quota" min="0" class="form-control" value="<?= (int) ($event['participant_quota'] ?? 0) ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Cover Event Publik</label>
                    <input type="file" name="cover_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                </div>
                <?php if (!empty($event['cover_image'])): ?>
                    <div class="col-12"><img src="<?= uploadAsset($event['cover_image']) ?>" alt="Cover event" style="max-height: 120px; border-radius: 10px;"></div>
                <?php endif; ?>
            </div>
        </div>
        <button class="btn btn-primary text-white erp-btn" type="submit">Simpan Master Event</button>
    </form>
</div>

<?php if ($isEdit): ?>
<?php
$ticketStats = $ticketStats ?? ['sold_tickets' => 0, 'reserved_tickets' => 0, 'attended_count' => 0, 'paid_orders' => 0];
$quota = (int) ($event['participant_quota'] ?? 0);
?>
<div class="card bg-white rounded-10 border border-white mb-4">
    <div class="p-20 border-bottom">
        <h4 class="mb-0">Penjualan Tiket & Kehadiran</h4>
        <p class="text-body fs-14 mb-0">Order visitor, konfirmasi pembayaran, dan check-in peserta event.</p>
    </div>
    <div class="row p-20">
        <div class="col-md-3 mb-3"><div class="border rounded-10 p-3"><p class="text-body mb-1">Kuota</p><h3 class="mb-0"><?= $quota ?></h3></div></div>
        <div class="col-md-3 mb-3"><div class="border rounded-10 p-3"><p class="text-body mb-1">Tiket Terjual</p><h3 class="mb-0"><?= (int) $ticketStats['sold_tickets'] ?></h3></div></div>
        <div class="col-md-3 mb-3"><div class="border rounded-10 p-3"><p class="text-body mb-1">Dalam Pemesanan</p><h3 class="mb-0"><?= (int) $ticketStats['reserved_tickets'] ?></h3></div></div>
        <div class="col-md-3 mb-3"><div class="border rounded-10 p-3"><p class="text-body mb-1">Hadir</p><h3 class="mb-0"><?= (int) $ticketStats['attended_count'] ?></h3></div></div>
    </div>
    <div class="p-20 border-top d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="mb-1">QR Code Check-in Peserta</h4>
            <p class="text-body fs-14 mb-0">QR Code dicetak di lokasi acara. Peserta memindai melalui akun member untuk konfirmasi kehadiran.</p>
        </div>
        <?php if (!empty($event['attendance_token']) && !empty($event['attendance_checkin_enabled'])): ?>
            <a class="btn btn-primary text-white erp-btn" target="_blank" rel="noopener" href="<?= url('master-events-attendance-print', ['id' => (int) $event['id']]) ?>">Cetak QR Code</a>
        <?php else: ?>
            <form method="POST" action="<?= url('master-events-attendance-activate') ?>">
                <input type="hidden" name="event_id" value="<?= (int) $event['id'] ?>">
                <button class="btn btn-primary text-white erp-btn" type="submit">Aktifkan QR Code</button>
            </form>
        <?php endif; ?>
    </div>
    <div class="px-20 pb-20 text-body fs-14">Mode petugas juga tersedia: peserta menunjukkan QR tiket digital, lalu petugas EO memindai QR tersebut untuk memvalidasi dan mencatat kehadiran.</div>
    <div class="default-table-area mx-minus-1">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Order</th><th>Pembeli</th><th>Tiket</th><th>Total</th><th>Status</th><th>Aksi Pembayaran</th></tr></thead>
                <tbody>
                    <?php if (!empty($ticketOrders)): foreach ($ticketOrders as $order): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($order['order_number']) ?></strong><small class="d-block text-body"><?= date('d M Y H:i', strtotime($order['ordered_at'])) ?></small></td>
                            <td><?= htmlspecialchars($order['buyer_name']) ?><small class="d-block text-body"><?= htmlspecialchars($order['buyer_email']) ?><br><?= htmlspecialchars($order['buyer_phone']) ?></small></td>
                            <td><?= (int) $order['quantity'] ?> tiket / <?= (int) $order['checked_in_count'] ?> hadir</td>
                            <td>Rp <?= number_format((float) $order['total_amount'], 0, ',', '.') ?></td>
                            <td>
                                <?= htmlspecialchars(ucfirst($order['payment_status'])) ?>
                                <?php if (!empty($order['payment_proof'])): ?>
                                    <small class="d-block"><a href="<?= url('master-events-ticket-proof', ['id' => (int) $order['id']]) ?>" target="_blank" rel="noopener">Lihat bukti bayar</a></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (in_array($order['payment_status'], ['pending', 'verification'], true)): ?>
                                    <form method="POST" action="<?= url('master-events-ticket-payment') ?>" class="d-flex gap-2">
                                        <input type="hidden" name="event_id" value="<?= (int) $event['id'] ?>">
                                        <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
                                        <button name="payment_status" value="paid" class="btn btn-sm btn-primary text-white">Konfirmasi Bayar</button>
                                        <button name="payment_status" value="cancelled" class="btn btn-sm btn-light">Batalkan</button>
                                    </form>
                                <?php else: ?>- <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center text-body py-4">Belum ada pemesanan tiket.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="p-20 border-top"><h4 class="mb-3">Status Kehadiran Peserta</h4>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Kode Tiket</th><th>Peserta</th><th>Pembayaran</th><th>Kehadiran</th><th>Mekanisme</th></tr></thead>
                <tbody>
                <?php if (!empty($ticketAttendees)): foreach ($ticketAttendees as $attendee): ?>
                    <tr>
                        <td><?= htmlspecialchars($attendee['ticket_code']) ?></td>
                        <td><?= htmlspecialchars($attendee['attendee_name']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($attendee['payment_status'])) ?></td>
                        <td><?= $attendee['check_in_status'] === 'arrived' ? 'Hadir' : 'Belum hadir' ?></td>
                        <td>
                            <?= $attendee['check_in_status'] === 'arrived' ? 'Tercatat' : ($attendee['payment_status'] === 'paid' ? 'Scan QR Code lokasi' : '-') ?>
                        </td>
                    </tr>
                <?php endforeach; else: ?><tr><td colspan="5" class="text-center text-body py-4">Daftar peserta belum tersedia.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card bg-white rounded-10 border border-white mb-4">
    <div class="p-20 border-bottom">
        <h4 class="mb-1">Konten Portal Peserta</h4>
        <p class="text-body fs-14 mb-0">Kelola agenda, pembicara, materi, dokumentasi, Q&A, polling, sertifikat, dan informasi acara.</p>
    </div>
    <form method="POST" enctype="multipart/form-data" action="<?= url('master-events-store-content') ?>" class="p-20 border-bottom">
        <input type="hidden" name="event_id" value="<?= (int) $event['id'] ?>">
        <div class="row">
            <div class="col-md-3 mb-3"><label class="form-label">Modul</label><select name="content_type" class="form-select" required><?php foreach (EventPortalContent::labels() as $key => $label): ?><option value="<?= $key ?>"><?= htmlspecialchars($label) ?></option><?php endforeach; ?></select></div>
            <div class="col-md-5 mb-3"><label class="form-label">Judul</label><input name="title" class="form-control" required></div>
            <div class="col-md-4 mb-3"><label class="form-label">Subjudul / Narasumber</label><input name="subtitle" class="form-control"></div>
            <div class="col-md-4 mb-3"><label class="form-label">Waktu Agenda</label><input type="datetime-local" name="scheduled_at" class="form-control"></div>
            <div class="col-md-4 mb-3"><label class="form-label">Lokasi</label><input name="location" class="form-control"></div>
            <div class="col-md-4 mb-3"><label class="form-label">Lampiran / Foto / PDF</label><input type="file" name="content_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp"></div>
            <div class="col-12 mb-3"><label class="form-label">Deskripsi / Pertanyaan / Pilihan Polling</label><textarea name="description" class="form-control" rows="3"></textarea></div>
        </div>
        <label class="mb-3 d-block"><input type="checkbox" name="visible_to_member" value="1" checked> Tampilkan kepada peserta</label>
        <button class="btn btn-primary text-white erp-btn" type="submit">Simpan Konten</button>
    </form>
    <div class="p-20">
        <?php if (!empty($portalContents)): foreach ($portalContents as $content): ?>
            <div class="d-flex justify-content-between gap-3 border-bottom pb-2 mb-2">
                <div><strong><?= htmlspecialchars(EventPortalContent::labels()[$content['content_type']] ?? $content['content_type']) ?>: <?= htmlspecialchars($content['title']) ?></strong><small class="d-block text-body"><?= $content['visible_to_member'] ? 'Tampil untuk peserta' : 'Internal' ?></small></div>
                <form method="POST" action="<?= url('master-events-delete-content') ?>">
                    <input type="hidden" name="event_id" value="<?= (int) $event['id'] ?>">
                    <input type="hidden" name="id" value="<?= (int) $content['id'] ?>">
                    <button class="btn btn-sm btn-light" type="submit">Hapus</button>
                </form>
            </div>
        <?php endforeach; else: ?><p class="text-body mb-0">Belum ada konten portal peserta.</p><?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card bg-white rounded-10 border border-white h-100">
            <div class="p-20 border-bottom"><h4 class="mb-0">Akses Client Portal</h4></div>
            <form method="POST" action="<?= url('master-events-store-access') ?>" class="p-20 border-bottom">
                <input type="hidden" name="event_id" value="<?= (int) $event['id'] ?>">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">User Client</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Pilih user Client</option>
                            <?php foreach ($clientUsers as $user): ?><option value="<?= (int) $user['id'] ?>"><?= htmlspecialchars($user['name'] . ' - ' . $user['email']) ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3"><label class="form-label">Level</label><select name="access_level" class="form-select"><option value="viewer">Viewer</option><option value="admin">Admin Client</option></select></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Aktif</option><option value="inactive">Nonaktif</option></select></div>
                </div>
                <button class="btn btn-primary text-white erp-btn">Simpan Akses</button>
            </form>
            <div class="p-20">
                <?php if ($accessList): foreach ($accessList as $access): ?>
                    <div class="border-bottom pb-2 mb-2"><strong><?= htmlspecialchars($access['name']) ?></strong><small class="d-block text-body"><?= htmlspecialchars($access['email']) ?> - <?= htmlspecialchars($access['access_level']) ?> / <?= htmlspecialchars($access['status']) ?></small></div>
                <?php endforeach; else: ?><p class="text-body mb-0">Belum ada user Client yang diberi akses.</p><?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card bg-white rounded-10 border border-white h-100">
            <div class="p-20 border-bottom"><h4 class="mb-0">Approval Existing</h4></div>
            <form method="POST" action="<?= url('master-events-store-approval') ?>" class="p-20 border-bottom">
                <input type="hidden" name="event_id" value="<?= (int) $event['id'] ?>">
                <label class="form-label">Kaitkan Approval</label>
                <select name="approval_id" class="form-select mb-3" required>
                    <option value="">Pilih approval existing</option>
                    <?php foreach ($availableApprovals as $approval): ?><option value="<?= (int) $approval['id'] ?>"><?= htmlspecialchars(($approval['reference_no'] ?: ('APP-' . $approval['id'])) . ' - ' . $approval['module_name']) ?></option><?php endforeach; ?>
                </select>
                <button class="btn btn-primary text-white erp-btn">Kaitkan Approval</button>
            </form>
            <div class="p-20">
                <?php if ($linkedApprovals): foreach ($linkedApprovals as $approval): ?>
                    <div class="border-bottom pb-2 mb-2"><strong><?= htmlspecialchars($approval['reference_no'] ?: ('APP-' . $approval['id'])) ?></strong><small class="d-block text-body"><?= htmlspecialchars($approval['module_name'] . ' - ' . $approval['status']) ?></small></div>
                <?php endforeach; else: ?><p class="text-body mb-0">Belum ada approval terhubung.</p><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card bg-white rounded-10 border border-white h-100">
            <div class="p-20 border-bottom"><h4 class="mb-0">Milestone Client</h4></div>
            <form method="POST" action="<?= url('master-events-store-milestone') ?>" class="p-20 border-bottom">
                <input type="hidden" name="event_id" value="<?= (int) $event['id'] ?>">
                <input name="title" class="form-control mb-3" required placeholder="Judul milestone">
                <textarea name="description" class="form-control mb-3" rows="2" placeholder="Deskripsi"></textarea>
                <div class="row"><div class="col-md-5 mb-3"><input type="date" name="due_date" class="form-control"></div><div class="col-md-4 mb-3"><select name="status" class="form-select"><option value="pending">Pending</option><option value="progress">Progress</option><option value="completed">Completed</option></select></div><div class="col-md-3 mb-3"><input type="number" name="sort_order" class="form-control" value="0"></div></div>
                <label class="mb-3 d-block"><input type="checkbox" name="visible_to_client" value="1" checked> Tampilkan ke client</label>
                <button class="btn btn-primary text-white erp-btn">Tambah Milestone</button>
            </form>
            <div class="p-20"><?php if ($milestones): foreach ($milestones as $milestone): ?><div class="border-bottom pb-2 mb-2"><strong><?= htmlspecialchars($milestone['title']) ?></strong><small class="d-block text-body"><?= htmlspecialchars($milestone['status']) ?><?= $milestone['visible_to_client'] ? ' - Visible' : ' - Internal' ?></small></div><?php endforeach; else: ?><p class="text-body mb-0">Belum ada milestone.</p><?php endif; ?></div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card bg-white rounded-10 border border-white h-100">
            <div class="p-20 border-bottom"><h4 class="mb-0">Dokumen Client</h4></div>
            <form method="POST" enctype="multipart/form-data" action="<?= url('master-events-store-document') ?>" class="p-20 border-bottom">
                <input type="hidden" name="event_id" value="<?= (int) $event['id'] ?>">
                <input name="title" class="form-control mb-3" required placeholder="Judul dokumen">
                <select name="category" class="form-select mb-3"><?php foreach (['proposal','quotation','contract','invoice','rundown','layout','report','other'] as $category): ?><option value="<?= $category ?>"><?= ucfirst($category) ?></option><?php endforeach; ?></select>
                <input type="file" name="document_file" class="form-control mb-3" required accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
                <label class="mb-3 d-block"><input type="checkbox" name="visible_to_client" value="1" checked> Tampilkan ke client</label>
                <button class="btn btn-primary text-white erp-btn">Upload Dokumen</button>
            </form>
            <div class="p-20"><?php if ($documents): foreach ($documents as $document): ?><div class="border-bottom pb-2 mb-2"><strong><?= htmlspecialchars($document['title']) ?></strong><small class="d-block text-body"><?= htmlspecialchars($document['category']) ?><?= $document['visible_to_client'] ? ' - Visible' : ' - Internal' ?></small></div><?php endforeach; else: ?><p class="text-body mb-0">Belum ada dokumen.</p><?php endif; ?></div>
        </div>
    </div>
</div>
<?php endif; ?>
