<div class="card mt-3">
    <div class="card-header">
        <h3 class="mb-0"><i class="bi bi-shield-lock-fill me-2"></i> Admin Login</h3>
    </div>
    <div class="card-body">
        <form method="post" action="admin/">
            <!-- Username Input Group -->
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text" id="username-addon">
                        <i class="bi bi-person-fill"></i>
                    </span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required aria-describedby="username-addon">
                </div>
            </div>
            <!-- Password Input Group -->
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text" id="password-addon">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required aria-describedby="password-addon">
                </div>
            </div>
            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </div>
        </form>
    </div>
</div>
