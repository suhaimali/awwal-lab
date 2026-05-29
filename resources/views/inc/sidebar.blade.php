 <aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
	  	<div class="multinav">
		  <div class="multinav-scroll" style="height: 100%;">	
			  <!-- sidebar menu-->
			  <ul class="sidebar-menu" data-widget="tree">			
				<li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
					<a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
						<i class="fa fa-th-large"></i>
						<span>Dashboard</span>
					</a>
				</li>
				<li class="{{ request()->routeIs('patients*') ? 'active' : '' }}">
				  <a href="{{ route('patients') }}" class="{{ request()->routeIs('patients*') ? 'active' : '' }}">
					<i class="fa fa-user"></i>
					<span>Patients</span>
				  </a>
				</li>
				<li class="{{ request()->routeIs('appointments*') ? 'active' : '' }}">
				  <a href="{{ route('appointments') }}" class="{{ request()->routeIs('appointments*') ? 'active' : '' }}">
					<i class="fa fa-calendar-plus-o"></i>
					<span>Book Appointment</span>
				  </a>
				</li>			

				<li class="{{ request()->routeIs('lab-tests*') ? 'active' : '' }}">
				  <a href="{{ route('lab-tests.index') }}" class="{{ request()->routeIs('lab-tests*') ? 'active' : '' }}">
					<i class="fa fa-flask"></i>
					<span>Lab Tests (Billing)</span>
				  </a>
				</li>
				<li class="{{ request()->routeIs('test-parameters*') ? 'active' : '' }}">
				  <a href="{{ route('test-parameters.index') }}" class="{{ request()->routeIs('test-parameters*') ? 'active' : '' }}">
					<i class="fa fa-cog"></i>
					<span>Test Parameters</span>
				  </a>
				</li>
				<li class="{{ request()->routeIs('categories*') ? 'active' : '' }}">
				  <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories*') ? 'active' : '' }}">
					<i class="fa fa-tags"></i>
					<span>Master Categories</span>
				  </a>
				</li>
				<li class="{{ request()->routeIs('sub-categories*') ? 'active' : '' }}">
				  <a href="{{ route('sub-categories.index') }}" class="{{ request()->routeIs('sub-categories*') ? 'active' : '' }}">
					<i class="fa fa-list-ul"></i>
					<span>Sub-Categories</span>
				  </a>
				</li>

				<li class="{{ request()->routeIs('reports*') ? 'active' : '' }}">
				  <a href="{{ route('reports') }}" class="{{ request()->routeIs('reports*') ? 'active' : '' }}">
					<i class="fa fa-bar-chart"></i>
					<span>Test Reports</span>
				  </a>
				</li>
				<li class="{{ request()->routeIs('payments*') ? 'active' : '' }}">
				  <a href="{{ route('payments') }}" class="{{ request()->routeIs('payments*') ? 'active' : '' }}">
					<i class="fa fa-money"></i>
					<span>Payments & Billing</span>
				  </a>
				</li>
				<li class="{{ request()->routeIs('income-report*') ? 'active' : '' }}">
				  <a href="#" onclick="openIncomeReport(event)" class="{{ request()->routeIs('income-report*') ? 'active' : '' }}">
					<i class="fa fa-line-chart"></i>
					<span>Income Report</span>
				  </a>
				</li>
			  </ul>

			  <div class="sidebar-widgets">
			 	<div class="copyright text-center m-25">
					<p><strong class="d-block">Awwal Lab software</strong> © 2026 All Rights Reserved</p>
				</div>
			  </div>
		  </div>
		</div>
    </section>
  </aside>

  <!-- Income Report Password Modal -->
  <div class="modal center-modal fade" id="modal-income-password" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Authentication Required</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<div class="form-group">
				<label>Enter Password</label>
				<input type="password" id="income-password-input" class="form-control" placeholder="Password">
			</div>
			<div id="income-password-error" class="text-danger mt-2" style="display:none;">Incorrect password.</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-primary" onclick="verifyIncomePassword()">Submit</button>
		  </div>
		</div>
	  </div>
  </div>

  <script>
	function openIncomeReport(e) {
		e.preventDefault();
		$('#income-password-input').val('');
		$('#income-password-error').hide();
		$('#modal-income-password').modal('show');
	}

	function verifyIncomePassword() {
		const pass = $('#income-password-input').val();
		if (pass === 'safwan@123') {
			$('#modal-income-password').modal('hide');
			window.location.href = "{{ route('income-report') }}?auth=safwan";
		} else {
			$('#income-password-error').show();
		}
	}
  </script>
