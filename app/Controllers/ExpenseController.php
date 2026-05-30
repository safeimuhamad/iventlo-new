<?php

class ExpenseController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new Expense();

        activity_log(
            'Keuangan - Pengeluaran',
            'view',
            'Melihat daftar pengeluaran'
        );

        $this->view('expenses/index', [
            'title' => 'Pengeluaran',
            'expenses' => $model->getAll()
        ]);
    }

    public function create()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $bankModel = new BankAccount();
        $coaModel = new ChartOfAccount();

        activity_log(
            'Keuangan - Pengeluaran',
            'create_form',
            'Membuka form tambah pengeluaran'
        );

        $this->view('expenses/create', [
            'title' => 'Tambah Pengeluaran',
            'bankAccounts' => $bankModel->getActive(),
            'expenseAccounts' => $coaModel->getActive()
        ]);
    }

    public function store()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $expenseModel = new Expense();
        $bankModel = new BankAccount();
        $trxModel = new BankTransaction();

        $expenseAccounts = $_POST['expense_account'] ?? [];
        $descriptions = $_POST['item_description'] ?? [];
        $amounts = $_POST['item_amount'] ?? [];

        $grandTotal = 0;

        foreach ($amounts as $amount) {

            $amount = (float) str_replace('.', '', $amount);

            $grandTotal += $amount;
        }

        $bank = $bankModel->find($_POST['bank_account_id'] ?? null);
        $bankAccountCoaId = $bank['coa_id'] ?? null;

        if (!$bankAccountCoaId) {
            $_SESSION['error'] = 'COA rekening bank belum disetting.';
            $this->redirect('expenses-create');
        }

        $db = Database::connect();
        $db->beginTransaction();

        try {
        $expenseNo = $expenseModel->generateNumber();

        $expenseId = $expenseModel->create([
            'expense_no' => $expenseNo,
            'bank_account_id' => $_POST['bank_account_id'],
            'expense_date' => $_POST['expense_date'],
            'payment_method' => $_POST['payment_method'],
            'beneficiary' => $_POST['beneficiary'],
            'billing_address' => $_POST['billing_address'],
            'expense_category' => null,
            'description' => $_POST['description'] ?? '',
            'amount' => $grandTotal,
            'reference_no' => $_POST['reference_no']
        ]);

        foreach ($expenseAccounts as $index => $account) {

            $amount = (float) str_replace('.', '', $amounts[$index] ?? 0);

            if ($amount <= 0) {
                continue;
            }

            $expenseModel->addItem($expenseId, [
                'account_id' => $account,
                'description' => $descriptions[$index] ?? '',
                'amount' => $amount
            ]);
        }

        $trxModel->create([
            'bank_account_id' => $_POST['bank_account_id'],
            'transaction_date' => $_POST['expense_date'],
            'transaction_type' => 'out',
            'reference_type' => 'expense',
            'reference_id' => $expenseId,
            'description' => 'Pengeluaran ' . $expenseId,
            'amount' => $grandTotal
        ]);

        $coaModel = new ChartOfAccount();
        $journalModel = new JournalEntry();

        $journalLines = [];

        foreach ($expenseAccounts as $index => $accountId) {

            $amount = (float) str_replace('.', '', $amounts[$index] ?? 0);

            if ($amount <= 0) {
                continue;
            }

            $journalLines[] = [
                'account_id' => $accountId,
                'debit' => $amount,
                'credit' => 0,
                'description' => $descriptions[$index] ?? ''
            ];
        }

        $journalLines[] = [
            'account_id' => $bankAccountCoaId,
            'debit' => 0,
            'credit' => $grandTotal,
            'description' => 'Pengeluaran kas/bank'
        ];

        $journalModel->create([
            'journal_date' => $_POST['expense_date'],
            'reference_type' => 'expense',
            'reference_id' => $expenseId,
            'description' => $_POST['description'] ?? 'Pengeluaran'
        ], $journalLines);

        $bankModel->decreaseBalance(
            $_POST['bank_account_id'],
            $grandTotal
        );

        $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            throw $e;
        }

        activity_log(
            'Keuangan - Pengeluaran',
            'create',
            'Membuat pengeluaran sebesar Rp ' . number_format($grandTotal, 0, ',', '.'),
            $expenseId,
            $expenseNo
        );

        $this->redirect('expenses');
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('expenses');
        }

        $model = new Expense();

        $expense = $model->find($id);

        if (!$expense) {

            activity_log(
                'Keuangan - Pengeluaran',
                'view_failed',
                'Gagal membuka detail pengeluaran karena data tidak ditemukan',
                $id
            );

            $this->redirect('expenses');
        }

        activity_log(
            'Keuangan - Pengeluaran',
            'view',
            'Melihat detail pengeluaran',
            $id,
            $expense['expense_no'] ?? null
        );

        $this->view('expenses/show', [
            'title' => 'Detail Pengeluaran',
            'expense' => $expense,
            'items' => $model->getItems($id)
        ]);
    }

    public function delete()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('expenses');
        }

        $expenseModel = new Expense();
        $bankModel = new BankAccount();
        $trxModel = new BankTransaction();

        $expense = $expenseModel->find($id);

        if (!$expense) {

            activity_log(
                'Keuangan - Pengeluaran',
                'delete_failed',
                'Gagal menghapus pengeluaran karena data tidak ditemukan',
                $id
            );

            $this->redirect('expenses');
        }

        $db = Database::connect();
        $db->beginTransaction();

        try {
            $bankModel->increaseBalance(
                $expense['bank_account_id'],
                (float) $expense['amount']
            );

            $journalModel = new JournalEntry();

            $journalModel->deleteByReference('expense', $id);
            $trxModel->deleteByReference('expense', $id);

            $expenseModel->deleteItems($id);
            $expenseModel->delete($id);
            $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            throw $e;
        }

        activity_log(
            'Keuangan - Pengeluaran',
            'delete',
            'Menghapus pengeluaran',
            $id,
            $expense['expense_no'] ?? null
        );

        $this->redirect('expenses');
    }

    public function edit()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('expenses');
        }

        $expenseModel = new Expense();
        $bankModel = new BankAccount();

        $expense = $expenseModel->find($id);
        $coaModel = new ChartOfAccount();

        if (!$expense) {

            activity_log(
                'Keuangan - Pengeluaran',
                'edit_failed',
                'Gagal membuka form edit pengeluaran karena data tidak ditemukan',
                $id
            );

            $this->redirect('expenses');
        }

        activity_log(
            'Keuangan - Pengeluaran',
            'edit_form',
            'Membuka form edit pengeluaran',
            $id,
            $expense['expense_no'] ?? null
        );

        $this->view('expenses/edit', [
            'title' => 'Edit Pengeluaran',
            'expense' => $expense,
            'items' => $expenseModel->getItems($id),
            'bankAccounts' => $bankModel->getActive(),
            'expenseAccounts' => $coaModel->getActive()
        ]);
    }

    public function update()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            $this->redirect('expenses');
        }

        $expenseModel = new Expense();
        $bankModel = new BankAccount();
        $trxModel = new BankTransaction();

        $oldExpense = $expenseModel->find($id);

        if (!$oldExpense) {

            activity_log(
                'Keuangan - Pengeluaran',
                'update_failed',
                'Gagal update pengeluaran karena data tidak ditemukan',
                $id
            );

            $this->redirect('expenses');
        }

        $bank = $bankModel->find($_POST['bank_account_id'] ?? null);
        $bankAccountCoaId = $bank['coa_id'] ?? null;

        if (!$bankAccountCoaId) {
            $_SESSION['error'] = 'COA rekening bank belum disetting.';
            $this->redirect('expenses-edit', ['id' => $id]);
        }

        $db = Database::connect();
        $db->beginTransaction();

        try {
        $bankModel->increaseBalance(
            $oldExpense['bank_account_id'],
            (float) $oldExpense['amount']
        );

        $trxModel->deleteByReference('expense', $id);

        $journalModel = new JournalEntry();

        $journalModel->deleteByReference('expense', $id);

        $expenseModel->deleteItems($id);

        $expenseAccounts = $_POST['expense_account'] ?? [];
        $descriptions = $_POST['item_description'] ?? [];
        $amounts = $_POST['item_amount'] ?? [];

        $grandTotal = 0;

        foreach ($amounts as $amount) {

            $amount = (float) str_replace('.', '', $amount);

            $grandTotal += $amount;
        }

        $expenseModel->update($id, [
            'bank_account_id' => $_POST['bank_account_id'],
            'expense_date' => $_POST['expense_date'],
            'payment_method' => $_POST['payment_method'],
            'beneficiary' => $_POST['beneficiary'],
            'billing_address' => $_POST['billing_address'],
            'expense_category' => null,
            'description' => $_POST['description'] ?? '',
            'amount' => $grandTotal,
            'reference_no' => $_POST['reference_no']
        ]);

        foreach ($expenseAccounts as $index => $account) {

            $amount = (float) str_replace('.', '', $amounts[$index] ?? 0);

            if ($amount <= 0) {
                continue;
            }

            $expenseModel->addItem($id, [
                'account_id' => $account,
                'description' => $descriptions[$index] ?? '',
                'amount' => $amount
            ]);
        }

        $trxModel->create([
            'bank_account_id' => $_POST['bank_account_id'],
            'transaction_date' => $_POST['expense_date'],
            'transaction_type' => 'out',
            'reference_type' => 'expense',
            'reference_id' => $id,
            'description' => $_POST['description'] ?? ('Pengeluaran ' . $id),
            'amount' => $grandTotal
        ]);

        $coaModel = new ChartOfAccount();

        $journalLines = [];

        foreach ($expenseAccounts as $index => $accountId) {

            $amount = (float) str_replace('.', '', $amounts[$index] ?? 0);

            if ($amount <= 0) {
                continue;
            }

            $journalLines[] = [
                'account_id' => $accountId,
                'debit' => $amount,
                'credit' => 0,
                'description' => $descriptions[$index] ?? ''
            ];
        }

        $journalLines[] = [
            'account_id' => $bankAccountCoaId,
            'debit' => 0,
            'credit' => $grandTotal,
            'description' => 'Pengeluaran kas/bank'
        ];

        $journalModel->create([
            'journal_date' => $_POST['expense_date'],
            'reference_type' => 'expense',
            'reference_id' => $id,
            'description' => $_POST['description'] ?? 'Pengeluaran'
        ], $journalLines);

        $bankModel->decreaseBalance(
            $_POST['bank_account_id'],
            $grandTotal
        );

        $db->commit();
        } catch (Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            throw $e;
        }

        activity_log(
            'Keuangan - Pengeluaran',
            'update',
            'Mengubah pengeluaran sebesar Rp ' . number_format($grandTotal, 0, ',', '.'),
            $id,
            $oldExpense['expense_no'] ?? null
        );

        $this->redirect('expenses-show', ['id' => $id]);
    }
}
