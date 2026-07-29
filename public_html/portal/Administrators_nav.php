
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">AM Training Institute</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/portal/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Announcements.php">Announcements</a>
        </li>        
		 <li class="nav-item">
          <a class="nav-link" href="Registration.php">Registration</a>
        </li>  
	  <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Catalogs
              </a>      
         <ul class="dropdown-menu">			
			<li>
			  <a class="dropdown-item d-flex gap-2 align-items-center" href="users.php">
				<svg class="bi" width="16" height="16"><use xlink:href="#person-fill"/></svg>
				Users
			  </a>
			</li>						
			<li><hr class="dropdown-divider"></li>	
			<li>
			  <a class="dropdown-item d-flex gap-2 align-items-center" href="Programs.php">
				<svg class="bi" width="16" height="16"><use xlink:href="#lock-fill"/></svg>
				Programs
			  </a>
			</li>
			<li>
			  <a class="dropdown-item d-flex gap-2 align-items-center" href="Classes.php">
				<svg class="bi" width="16" height="16"><use xlink:href="#person-fill"/></svg>
				Classes
			  </a>
			</li>			
			<li><hr class="dropdown-divider"></li>				
			<li>
			  <a class="dropdown-item dropdown-item-danger d-flex gap-2 align-items-center" href="#">
				<svg class="bi" width="16" height="16"><use xlink:href="#door-closed-fill"/></svg>
				
			  </a>
			</li>
		  </ul>  
		  </li>      
      <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?=$_SESSION["UserName"]?>
              </a>      
         <ul class="dropdown-menu">			
			<li>
			  <a class="dropdown-item d-flex gap-2 align-items-center" href="#">
				<svg class="bi" width="16" height="16"><use xlink:href="#person-fill-gear"/></svg>
				Update Profile
			  </a>
			</li>
			<li>
			  <a class="dropdown-item d-flex gap-2 align-items-center" href="/cp/">
				<svg class="bi" width="16" height="16"><use xlink:href="#lock-fill"/></svg>
				Change Password
			  </a>
			</li>			
			<li><hr class="dropdown-divider"></li>
			<li>
			  <a class="dropdown-item dropdown-item-danger d-flex gap-2 align-items-center" href="/logout/">
				<svg class="bi" width="16" height="16"><use xlink:href="#door-closed-fill"/></svg>
				Log Out
			  </a>
			</li>
		  </ul>  
		  </li>      
    </div>
  </div>
</nav>
