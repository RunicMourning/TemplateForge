<div class="card mt-3 shadow-sm border-0 rounded-4 overflow-hidden">
    <div class="card-header bg-transparent border-0">
        <h5 class="mb-0 d-flex align-items-center">
            <i class="bi bi-shield-lock-fill me-2 text-primary"></i> Admin Login
        </h5>
    </div>
    <div class="card-body">
        <form method="post" action="admin/">
            <!-- Username Input -->
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text" id="username-addon">
                        <i class="bi bi-person-fill"></i>
                    </span>
                    <input type="text" class="form-control rounded-3" id="username" name="username" placeholder="Enter username" required aria-describedby="username-addon">
                </div>
            </div>
            <!-- Password Input -->
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text" id="password-addon">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password" class="form-control rounded-3" id="password" name="password" placeholder="Enter password" required aria-describedby="password-addon">
                </div>
            </div>
            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary rounded-pill shadow-sm">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </div>
        </form>
    </div>
</div>
