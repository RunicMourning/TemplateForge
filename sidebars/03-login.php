  <div class="card shadow-lg mt-3">
    <div class="card-header text-center">
      <h4>Admin Login</h4>
    </div>
    <div class="card-body">
      <form method="post" action="admin/">
        <!-- Username Input Group -->
        <div class="mb-3">
          <div class="input-group">
            <span class="input-group-text" id="username-addon">🙋</span>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required aria-describedby="username-addon">
          </div>
        </div>
        <!-- Password Input Group -->
        <div class="mb-3">
          <div class="input-group">
            <span class="input-group-text" id="password-addon">🔒</span>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required aria-describedby="password-addon">
          </div>
        </div>
        <!-- Submit Button -->
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Login</button>
        </div>
      </form>
    </div>
  </div>
