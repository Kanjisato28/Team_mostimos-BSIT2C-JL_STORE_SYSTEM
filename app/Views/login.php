<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>JL Store | Sign In</title>

  <!-- Google Fonts + Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      /* Warm gradient from #ffc107 to #ff9800 */
      background: linear-gradient(145deg, #ffc107 0%, #ff9800 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      position: relative;
      overflow-x: hidden;
    }

    /* animated background overlay with subtle warm tone */
    body::before {
      content: "";
      position: absolute;
      width: 200%;
      height: 200%;
      top: -50%;
      left: -50%;
      background: radial-gradient(circle at 30% 40%, rgba(255, 193, 7, 0.25) 0%, rgba(0, 0, 0, 0.05) 70%);
      animation: slowShift 28s infinite alternate ease-in-out;
      pointer-events: none;
    }

    @keyframes slowShift {
      0% { transform: translate(0%, 0%) rotate(0deg);}
      100% { transform: translate(-5%, -5%) rotate(2deg);}
    }

    .glass-card {
      width: 100%;
      max-width: 480px;
      background: rgba(255, 255, 255, 0.97);
      backdrop-filter: blur(2px);
      border-radius: 2rem;
      box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.35), 0 1px 2px rgba(0,0,0,0.05);
      transition: transform 0.2s ease, box-shadow 0.2s;
      overflow: hidden;
      border: 1px solid rgba(255,255,255,0.4);
    }

    .glass-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 32px 55px -16px rgba(0, 0, 0, 0.4);
    }

    .card-header {
      background: #ffffff;
      padding: 2rem 2rem 0.5rem 2rem;
      text-align: center;
      border-bottom: none;
    }

    /* Brand icon using shop.png image */
    .brand-icon {
      width: 80px;
      height: 80px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.2rem;
      border-radius: 50%;
      background: linear-gradient(135deg, #fff8e7, #fff0d4);
      padding: 12px;
      box-shadow: 0 8px 20px rgba(255, 152, 0, 0.25);
      transition: transform 0.25s ease;
    }

    .brand-icon img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      border-radius: 12px;
    }

    .brand-icon:hover {
      transform: scale(1.03);
    }

    /* Title gradient using warm orange/gold */
    .login-title {
      font-size: 1.75rem;
      font-weight: 700;
      background: linear-gradient(125deg, #e68a00, #ff9800);
      background-clip: text;
      -webkit-background-clip: text;
      color: transparent;
      letter-spacing: -0.3px;
    }

    .login-subtitle {
      font-size: 0.9rem;
      color: #5d5b50;
      margin-top: 0.5rem;
      font-weight: 500;
    }

    .card-body {
      padding: 1.8rem 2rem 2.2rem 2rem;
    }

    /* modern form styling */
    .input-group-modern {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .input-group-modern i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #bfa055;
      font-size: 1.1rem;
      transition: color 0.2s;
      pointer-events: none;
      z-index: 2;
    }

    .input-group-modern input {
      width: 100%;
      padding: 0.9rem 1rem 0.9rem 2.8rem;
      font-size: 0.95rem;
      font-weight: 500;
      border: 1.5px solid #f0e0c0;
      border-radius: 1rem;
      background-color: #ffffff;
      transition: all 0.2s ease;
      font-family: 'Inter', sans-serif;
      color: #3e3a2e;
    }

    .input-group-modern input:focus {
      outline: none;
      border-color: #ff9800;
      box-shadow: 0 0 0 4px rgba(255, 152, 0, 0.2);
    }

    .input-group-modern input::placeholder {
      color: #cfc19e;
      font-weight: 400;
    }

    /* checkbox remember me row */
    .form-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 1.5rem 0 1.8rem;
      flex-wrap: wrap;
      gap: 0.8rem;
    }

    .checkbox-custom {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
      font-size: 0.85rem;
      font-weight: 500;
      color: #5e5a4b;
      user-select: none;
    }

    .checkbox-custom input {
      width: 1rem;
      height: 1rem;
      accent-color: #ff9800;
      margin: 0;
      cursor: pointer;
    }

    /* PRIMARY BUTTON with gradient #ffc107 -> #ff9800 */
    .btn-login {
      background: linear-gradient(105deg, #ffc107, #ff9800);
      border: none;
      padding: 0.85rem 1.5rem;
      font-weight: 600;
      font-size: 0.95rem;
      font-family: 'Inter', sans-serif;
      border-radius: 2rem;
      color: white;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: all 0.2s;
      cursor: pointer;
      width: auto;
      min-width: 130px;
      box-shadow: 0 4px 12px rgba(255, 152, 0, 0.35);
    }

    .btn-login:hover {
      background: linear-gradient(105deg, #e6b000, #e68900);
      transform: scale(1.01);
      box-shadow: 0 8px 18px rgba(255, 152, 0, 0.5);
    }

    .btn-login i {
      font-size: 0.9rem;
    }

    /* alert styling warm theme */
    .alert-modern {
      background: #fff3e0;
      border-left: 5px solid #ff9800;
      padding: 0.8rem 1.2rem;
      border-radius: 1rem;
      margin-bottom: 1.8rem;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.85rem;
      font-weight: 500;
      color: #c45c00;
      animation: fadeSlide 0.3s ease;
    }

    .alert-modern i {
      font-size: 1rem;
      color: #ff9800;
    }

    @keyframes fadeSlide {
      from {
        opacity: 0;
        transform: translateY(-8px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .extra-links {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.75rem;
      color: #a38654;
    }

    hr {
      margin: 1rem 0 0.5rem;
      border: none;
      height: 1px;
      background: #f5e5cf;
    }

    /* responsive */
    @media (max-width: 520px) {
      .card-body {
        padding: 1.5rem;
      }
      .login-title {
        font-size: 1.5rem;
      }
      .btn-login {
        width: 100%;
        justify-content: center;
      }
      .form-actions {
        flex-direction: column;
        align-items: flex-start;
      }
      .brand-icon {
        width: 70px;
        height: 70px;
      }
    }

    /* subtle warm focus glow */
    .input-group-modern:focus-within i {
      color: #ff9800;
    }
  </style>
</head>
<body>
<div class="glass-card">
  <div class="card-header">
    <div class="brand-icon">
      <!-- CUSTOM SHOP PNG ICON -->
      <!-- IMPORTANT: Move shop.png to public/assets/images/shop.png or public/uploads/shop.png -->
      <!-- Option 1 (recommended): public/assets/images/shop.png -->
      <img src="<?= base_url('assets/img/shop.png') ?>" 
           alt="JL Store Logo"
           onerror="this.onerror=null; this.src='<?= base_url('uploads/shop.png') ?>'; this.onerror=function(){this.parentElement.innerHTML='<i class=\'fas fa-store\' style=\'font-size: 2.5rem; color:#ff9800;\'></i>';}">
    </div>
    <div class="login-title">JL Store</div>
    <div class="login-subtitle">Sales & Inventory System · secure access</div>
  </div>
  <div class="card-body">
    
    <!-- Dynamic flash message handling with warm style -->
    <?php 
    // supports CodeIgniter flashdata (error & success)
    $errorMsg = session()->getFlashdata('error');
    $successMsg = session()->getFlashdata('success');
    if ($errorMsg): 
    ?>
      <div class="alert-modern">
        <i class="fas fa-exclamation-triangle"></i>
        <span><?= esc($errorMsg) ?></span>
      </div>
    <?php elseif ($successMsg): ?>
      <div class="alert-modern" style="background:#eef5e8; border-left-color:#ff9800; color:#aa6f20;">
        <i class="fas fa-check-circle"></i>
        <span><?= esc($successMsg) ?></span>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('/auth') ?>" method="post">
      <?= csrf_field() ?>
      
      <!-- Email field with modern icon -->
      <div class="input-group-modern">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" id="email" placeholder="Email address" value="<?= old('email') ?>" required autofocus>
      </div>

      <!-- Password field -->
      <div class="input-group-modern">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="password" placeholder="Password" required>
      </div>

      <!-- Remember + Login button row -->
      <div class="form-actions">
        <label class="checkbox-custom">
          <input type="checkbox" name="remember" id="remember" value="1">
          <span>Keep me signed in</span>
        </label>
        <button type="submit" class="btn-login">
          <i class="fas fa-arrow-right-to-bracket"></i> Sign in
        </button>
      </div>
      
      <div class="extra-links">
        <span>🔒 secure login · JL inventory v2.0</span>
      </div>
    </form>
    <hr />
    <div class="extra-links" style="margin-top: 0.8rem;">
      <i class="fas fa-chart-line"></i> &nbsp; real-time stock & analytics
    </div>
  </div>
</div>

<script>
  (function() {
    const inputs = document.querySelectorAll('.input-group-modern input');
    inputs.forEach(input => {
      input.addEventListener('focus', (e) => {
        e.target.closest('.input-group-modern')?.classList.add('focused');
      });
      input.addEventListener('blur', (e) => {
        e.target.closest('.input-group-modern')?.classList.remove('focused');
      });
    });
  })();
</script>
</body>
</html>