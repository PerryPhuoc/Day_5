<?php
// Bắt đầu phiên làm việc để sử dụng $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header data-bs-theme="dark">
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="/day4/frontend">MyShop</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarCollapse">
        
        <!-- DANH SÁCH MENU BÊN TRÁI -->
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="/day4/backend/index.php">Admin</a>
  </li>
<?php endif; ?>

          <li class="nav-item">
            <a class="nav-link" href="/day4/frontend/pages/about.php">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/day4/frontend/pages/contact.php">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/day4/frontend/pages/view_cart.php">View Cart</a>
          </li>
        </ul>

        <!-- CÁC MỤC BÊN PHẢI: Menu người dùng + Form tìm kiếm -->
        <ul class="navbar-nav align-items-center mb-2 mb-md-0">
          <?php if (isset($_SESSION['kh_tendangnhap_logged']) && !empty($_SESSION['kh_tendangnhap_logged'])) : ?>
            <li class="nav-item">
              <span class="nav-link">Welcome, <?= htmlspecialchars($_SESSION['kh_tendangnhap_logged']); ?></span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/day4/frontend/pages/logout.php">Logout</a>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="/day4/frontend/pages/login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/day4/frontend/pages/register.php">Register</a>
            </li>
          <?php endif; ?>
        </ul>

        <!-- FORM TÌM KIẾM -->
        <form class="d-flex ms-3" role="search" action="/day4/frontend/search.php" method="GET">
          <input class="form-control me-2" type="search" name="q" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>

      </div>
    </div>
  </nav>
</header>
