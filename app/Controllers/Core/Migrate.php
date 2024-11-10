<?php

namespace App\Controllers\Core;

use CodeIgniter\Controller;
use CodeIgniter\Database\Migrations\MigrationRunner;
use CodeIgniter\Config\Services;

class Migrate extends Controller
{
	public function runMigrations()
	{
			// Check environment (only run in development or staging)
			if (ENVIRONMENT !== 'development' && ENVIRONMENT !== 'staging') {
					return 'Migrations are not allowed in this environment.';
			};
	
			if ($this->request->getMethod() === "POST") {
					if (!$this->request->getPost('password') || $this->request->getPost('password') !== 'anjowijowi') {
							return 'Unauthorized';
					};
			
					// Run migrations
					$migrator = Services::migrations();
					if ($migrator->latest()) {
							return 'Migrations were successfully run.';
					} else {
							return 'Migrations failed. Please check the log for errors.';
					};
			};

			return view('core/migration');
	}

}
