<?php

if (!function_exists('alert_success')) {
	function alert_success($text)
	{
		$alert = '<div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2" id="alts">
					<div class="d-flex align-items-center">
						<div class="font-35 text-white"><i class="bx bxs-check-circle"></i>
						</div>
						<div class="ms-3">
							<h6 class="mb-0 text-white">Sukses!</h6>
							<div class="text-white">' . $text . '</div>
						</div>
					</div>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>';
		session()->flash('alert', $alert);
	}
}

if (!function_exists('alert_failed')) {
	function alert_failed($text)
	{
		$alert = '<div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2" id="alts">
					<div class="d-flex align-items-center">
						<div class="font-35 text-white"><i class="bx bxs-message-square-x"></i>
						</div>
						<div class="ms-3">
							<h6 class="mb-0 text-white">Gagal!</h6>
							<div class="text-white">' . $text . '</div>
						</div>
					</div>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>';
		session()->flash('alert', $alert);
	}
}

if (!function_exists('alert_warning')) {
	function alert_warning($text)
	{
		$alert = '<div class="alert alert-warning border-0 bg-warning alert-dismissible fade show py-2" id="alts">
					<div class="d-flex align-items-center">
						<div class="font-35 text-dark"><i class="bx bx-info-circle"></i>
						</div>
						<div class="ms-3">
							<h6 class="mb-0 text-dark">Peringatan!</h6>
							<div class="text-dark">' . $text . '</div>
						</div>
					</div>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>';
		session()->flash('alert', $alert);
	}
}

if (!function_exists('show_alert')) {
	function show_alert()
	{
		return session('alert');
	}
}
