<?php

class Payroll
{
	private $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

	public function create($data)
	{
		$stmt = $this->db->prepare("
			INSERT INTO payrolls
			(
				payroll_period_id,
				employee_id,

				basic_salary,

				allowance_amount,
				overtime_amount,
				bonus_amount,

				deduction_amount,
				cash_advance_deduction,
				bpjs_amount,
				tax_amount,

				gross_salary,
				net_salary,

				attendance_days,
				absent_days,
				late_minutes,
				overtime_minutes,

				status,
				notes
				)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
			");

		return $stmt->execute([
			$data['payroll_period_id'],
			$data['employee_id'],

			$data['basic_salary'] ?? 0,

			$data['allowance_amount'] ?? 0,
			$data['overtime_amount'] ?? 0,
			$data['bonus_amount'] ?? 0,

			$data['deduction_amount'] ?? 0,
			$data['cash_advance_deduction'] ?? 0,
			$data['bpjs_amount'] ?? 0,
			$data['tax_amount'] ?? 0,

			$data['gross_salary'] ?? 0,
			$data['net_salary'] ?? 0,

			$data['attendance_days'] ?? 0,
			$data['absent_days'] ?? 0,
			$data['late_minutes'] ?? 0,
			$data['overtime_minutes'] ?? 0,

			$data['status'] ?? 'draft',
			$data['notes'] ?? ''
		]);
	}
	
	public function exists($periodId, $employeeId)
	{
		$stmt = $this->db->prepare("
			SELECT id
			FROM payrolls
			WHERE payroll_period_id = ?
			AND employee_id = ?
			LIMIT 1
			");

		$stmt->execute([$periodId, $employeeId]);

		return $stmt->fetch();
	}

	public function paginateByPeriod($periodId, $limit = 20, $offset = 0)
	{
		$stmt = $this->db->prepare("
			SELECT 
			p.*,
			e.employee_code,
			e.full_name,
			d.name AS department_name,
			pos.name AS position_name
			FROM payrolls p
			LEFT JOIN employees e ON e.id = p.employee_id
			LEFT JOIN departments d ON d.id = e.department_id
			LEFT JOIN positions pos ON pos.id = e.position_id
			WHERE p.payroll_period_id = ?
			ORDER BY e.full_name ASC
			LIMIT {$limit} OFFSET {$offset}
			");

		$stmt->execute([$periodId]);

		return $stmt->fetchAll();
	}

	public function countByPeriod($periodId)
	{
		$stmt = $this->db->prepare("
			SELECT COUNT(*) AS total
			FROM payrolls
			WHERE payroll_period_id = ?
			");

		$stmt->execute([$periodId]);

		$row = $stmt->fetch();

		return (int) ($row['total'] ?? 0);
	}

	public function find($id)
	{
		$stmt = $this->db->prepare("
			SELECT 
			p.*,
			pp.period_name,
			pp.start_date,
			pp.end_date,
			pp.payroll_date,
			e.employee_code,
			e.full_name,
			d.name AS department_name,
			pos.name AS position_name
			FROM payrolls p
			LEFT JOIN payroll_periods pp ON pp.id = p.payroll_period_id
			LEFT JOIN employees e ON e.id = p.employee_id
			LEFT JOIN departments d ON d.id = e.department_id
			LEFT JOIN positions pos ON pos.id = e.position_id
			WHERE p.id = ?
			LIMIT 1
			");

		$stmt->execute([$id]);

		return $stmt->fetch();
	}

	public function update($id, $data)
	{
		$stmt = $this->db->prepare("
			UPDATE payrolls SET

				basic_salary = ?,

				allowance_amount = ?,
				overtime_amount = ?,
				bonus_amount = ?,

				deduction_amount = ?,
				bpjs_amount = ?,
				tax_amount = ?,

				gross_salary = ?,
				net_salary = ?,

				status = ?,
				notes = ?

				WHERE id = ?
				");

		return $stmt->execute([

			$data['basic_salary'] ?? 0,

			$data['allowance_amount'] ?? 0,
			$data['overtime_amount'] ?? 0,
			$data['bonus_amount'] ?? 0,

			$data['deduction_amount'] ?? 0,
			$data['bpjs_amount'] ?? 0,
			$data['tax_amount'] ?? 0,

			$data['gross_salary'] ?? 0,
			$data['net_salary'] ?? 0,

			$data['status'] ?? 'draft',
			$data['notes'] ?? '',

			$id
		]);
	}
}