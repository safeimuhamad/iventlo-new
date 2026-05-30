<?php

class JournalEntryController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $model = new JournalEntry();

        $this->view('journal-entries/index', [
            'title' => 'Jurnal Umum',
            'journals' => $model->getAll()
        ]);
    }

    public function show()
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $this->redirect('journal-entries');
        }

        $model = new JournalEntry();

        $journal = $model->find($id);

        if (!$journal) {
            $this->redirect('journal-entries');
        }

        $this->view('journal-entries/show', [
            'title' => 'Detail Jurnal',
            'journal' => $journal,
            'lines' => $model->getLines($id)
        ]);
    }
}