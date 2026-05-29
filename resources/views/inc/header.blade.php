	
  <header class="main-header">
	<div class="d-flex align-items-center logo-box justify-content-start">	
		<!-- Logo -->
		<a href="{{ route('dashboard') }}" class="logo">
		  <!-- logo-->
		  <div class="logo-mini w-50">
			  <span class="light-logo"><img src="/images/logo-pwa.png" alt="logo"></span>
			  <span class="dark-logo"><img src="/images/logo-pwa.png" alt="logo"></span>
		  </div>
		  <div class="logo-lg">
			  <span class="light-logo text-dark fw-bold fs-18">Awwal Lab</span>
		  </div>
		</a>	
	</div>  
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
	  <div class="app-menu">
		<ul class="header-megamenu nav">
			<li class="btn-group nav-item">
				<a href="#" class="waves-effect waves-light nav-link push-btn btn-primary-light" data-toggle="push-menu" role="button">
					<i class="fa fa-bars"></i>
			    </a>
			</li>

		</ul> 
	  </div>
		
      <div class="navbar-custom-menu r-side">
        <ul class="nav navbar-nav">	
            <li class="btn-group d-md-inline-flex d-none align-items-center me-3">
                <div class="text-end me-10 bg-primary-light px-3 py-1 rounded-pill">
                    <span id="header-live-time" class="fs-14 fw-bold text-primary"></span>
                    <br>
                    <small id="header-live-date" class="fs-10 text-mute text-uppercase"></small>
                </div>
            </li>
            
			<!-- User Account-->
			<li class="dropdown user user-menu">
				<a href="#" class="waves-effect waves-light dropdown-toggle w-auto l-h-12 bg-transparent p-0 no-shadow" data-bs-toggle="dropdown" title="User">
					<div class="d-flex pt-1">
						<div class="text-end me-10">
							<p class="pt-5 fs-14 mb-0 fw-700 text-primary">Awwal Lab</p>
							<small class="fs-10 mb-0 text-uppercase text-mute">Admin</small>
						</div>
						<img src="/images/avatar/avatar-1.png" class="avatar rounded-10 bg-primary-light h-40 w-40" alt="" />
					</div>
				</a>
				<ul class="dropdown-menu animated flipInX">
				  <li class="user-body">

					 <a class="dropdown-item" href="{{ route('logout') }}"><i class="fa fa-power-off text-muted me-2"></i> Logout</a>
				  </li>
				</ul>
			</li>	
			<li class="btn-group nav-item d-lg-inline-flex d-none">
				<a href="#" data-provide="fullscreen" class="waves-effect waves-light nav-link full-screen btn-warning-light" title="Full Screen">
					<i class="fa fa-arrows-alt"></i>
			    </a>
			</li>
			  
          <!-- Control Sidebar Toggle Button -->

			
        </ul>
      </div>
    </nav>
  </header>